<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mikomagni\SimpleLikes\Models\SimpleLike;

/**
 * Migrate guest likes to authenticated user on login/registration.
 */
class MigrateGuestLikesListener
{
    public function handle($event)
    {
        try {
            if (!$event->user || !request()->ip() || !request()->userAgent()) {
                return;
            }

            $userId = (string) $event->user->id();
            $guestId = 'guest_' . hash('sha256', request()->ip() . '|' . request()->userAgent());
            
            $guestLikes = SimpleLike::where('user_id', $guestId)
                ->where('user_type', 'guest')
                ->get();
            
            if ($guestLikes->isEmpty()) {
                return;
            }
            
            DB::transaction(function () use ($guestLikes, $userId) {
                $migrated = false;
                
                foreach ($guestLikes as $like) {
                    $exists = SimpleLike::where('entry_id', $like->entry_id)
                        ->where('user_id', $userId)
                        ->exists();
                    
                    if (!$exists) {
                        $like->update(['user_id' => $userId, 'user_type' => 'authenticated']);
                        $migrated = true;
                        
                        Cache::forget("simple_likes_display_{$like->entry_id}_{$userId}");
                        Cache::forget("simple_likes_count_{$like->entry_id}");
                    }
                }
                
                if ($migrated) {
                    session(['wishlist_migrated' => true]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Guest likes migration failed', ['error' => $e->getMessage()]);
        }
    }
}
