<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mikomagni\SimpleLikes\Models\SimpleLike;

/**
 * Migrate guest likes to authenticated user on login/registration.
 */
class MigrateGuestLikesListener
{
    public function handle($event): void
    {
        // Validate required data
        if (!$event->user || !request()->ip() || !request()->userAgent()) {
            return;
        }

        $userId = (string) $event->user->id();
        $guestId = 'guest_' . hash('sha256', request()->ip() . '|' . request()->userAgent());

        // Fetch guest likes
        $guestLikes = SimpleLike::where('user_id', $guestId)
            ->where('user_type', 'guest')
            ->get();

        if ($guestLikes->isEmpty()) {
            return;
        }

        // Get existing user likes to avoid duplicates
        $existingEntryIds = SimpleLike::where('user_id', $userId)
            ->whereIn('entry_id', $guestLikes->pluck('entry_id'))
            ->pluck('entry_id');

        DB::transaction(function () use ($guestLikes, $userId, $existingEntryIds) {
            $migrated = false;

            foreach ($guestLikes as $like) {
                if ($existingEntryIds->contains($like->entry_id)) {
                    $like->delete(); // Remove duplicate
                    continue;
                }

                $like->update([
                    'user_id' => $userId,
                    'user_type' => 'authenticated',
                ]);

                // Clear cache for this entry
                Cache::forget("simple_likes_display_{$like->entry_id}_{$userId}");
                Cache::forget("simple_likes_count_{$like->entry_id}");
                
                $migrated = true;
            }

            if ($migrated) {
                session(['wishlist_migrated' => true]);
            }
        });
    }
}
