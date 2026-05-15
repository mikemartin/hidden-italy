<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mikomagni\SimpleLikes\Models\SimpleLike;

class SyncGuestLikes
{
    public function handle(Login $event): void
    {
        if (! $event->user || ! request()->ip() || ! request()->userAgent()) {
            return;
        }

        $userId = (string) $event->user->id();
        $guestId = 'guest_'.hash('sha256', request()->ip().'|'.request()->userAgent());

        $guestLikes = SimpleLike::where('user_id', $guestId)
            ->where('user_type', 'guest')
            ->get();

        if ($guestLikes->isEmpty()) {
            return;
        }

        $existingEntryIds = SimpleLike::where('user_id', $userId)
            ->whereIn('entry_id', $guestLikes->pluck('entry_id'))
            ->pluck('entry_id');

        $migrated = DB::transaction(function () use ($guestLikes, $userId, $existingEntryIds) {
            $count = 0;

            foreach ($guestLikes as $like) {
                if ($existingEntryIds->contains($like->entry_id)) {
                    $like->delete();

                    continue;
                }

                $like->update([
                    'user_id' => $userId,
                    'user_type' => 'authenticated',
                ]);

                $count++;
            }

            return $count;
        });

        // Cache + session writes must happen outside the transaction —
        // sessions don't participate in the DB transaction, and clearing
        // cache before commit would let a concurrent reader repopulate
        // it with pre-migration data.
        foreach ($guestLikes as $like) {
            Cache::forget("simple_likes_display_{$like->entry_id}_{$userId}");
            Cache::forget("simple_likes_display_{$like->entry_id}_{$guestId}");
            Cache::forget("simple_likes_count_{$like->entry_id}");
        }

        if ($migrated > 0) {
            session(['likes_synced' => true]);
        }
    }
}
