<?php

namespace Tests\Unit\Listeners;

use App\Listeners\SyncGuestLikes;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Mikomagni\SimpleLikes\Models\SimpleLike;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Contracts\Auth\User;
use Statamic\Events\UserRegistered;
use Tests\TestCase;

class SyncGuestLikesTest extends TestCase
{
    use RefreshDatabase;

    private string $userId = 'user-123';
    private string $ip = '192.168.1.100';
    private string $userAgent = 'Mozilla/5.0 Test';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create simple_likes table for testing
        Schema::create('simple_likes', function ($table) {
            $table->id();
            $table->string('entry_id');
            $table->string('user_id');
            $table->string('user_type');
            $table->timestamps();
        });
        
        Request::instance()->server->set('REMOTE_ADDR', $this->ip);
        Request::instance()->headers->set('User-Agent', $this->userAgent);
    }

    #[Test]
    public function it_migrates_guest_likes_on_login(): void
    {
        $guestId = $this->guestId();
        $this->createGuestLike($guestId, 'entry-1');
        
        (new SyncGuestLikes)->handle($this->loginEvent());

        $this->assertDatabaseHas('simple_likes', [
            'entry_id' => 'entry-1',
            'user_id' => $this->userId,
            'user_type' => 'authenticated',
        ]);
        
        $this->assertTrue(session('likes_synced'));
    }

    #[Test]
    public function it_migrates_multiple_likes(): void
    {
        $guestId = $this->guestId();
        $this->createGuestLike($guestId, 'entry-1');
        $this->createGuestLike($guestId, 'entry-2');
        
        (new SyncGuestLikes)->handle($this->loginEvent());

        $this->assertEquals(2, SimpleLike::where('user_id', $this->userId)->count());
    }

    #[Test]
    public function it_removes_duplicate_guest_likes(): void
    {
        $guestId = $this->guestId();
        $this->createAuthLike($this->userId, 'entry-1');
        $this->createGuestLike($guestId, 'entry-1');
        
        (new SyncGuestLikes)->handle($this->loginEvent());

        $this->assertEquals(1, SimpleLike::where('entry_id', 'entry-1')->count());
        $this->assertDatabaseMissing('simple_likes', ['user_id' => $guestId]);
    }

    #[Test]
    public function it_does_nothing_when_no_guest_likes(): void
    {
        (new SyncGuestLikes)->handle($this->loginEvent());

        $this->assertDatabaseCount('simple_likes', 0);
        $this->assertFalse(session()->has('likes_migrated'));
    }

    #[Test]
    public function it_handles_missing_user(): void
    {
        $event = Mockery::mock(Login::class);
        $event->user = null;

        (new SyncGuestLikes)->handle($event);

        $this->assertDatabaseCount('simple_likes', 0);
    }

    private function guestId(): string
    {
        return 'guest_' . hash('sha256', $this->ip . '|' . $this->userAgent);
    }

    private function createGuestLike(string $guestId, string $entryId): void
    {
        SimpleLike::create([
            'entry_id' => $entryId,
            'user_id' => $guestId,
            'user_type' => 'guest',
        ]);
    }

    private function createAuthLike(string $userId, string $entryId): void
    {
        SimpleLike::create([
            'entry_id' => $entryId,
            'user_id' => $userId,
            'user_type' => 'authenticated',
        ]);
    }

    private function loginEvent(): Login
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('id')->andReturn($this->userId);
        
        return new Login('statamic', $user, false);
    }
}
