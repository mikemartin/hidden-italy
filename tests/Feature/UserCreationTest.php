<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Statamic\Facades\User as StatamicUser;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_key_on_unsaved_user_does_not_recurse(): void
    {
        $user = new User;

        $this->assertNull($user->getKey());
        $this->assertSame('', $user->id());
    }

    public function test_eloquent_user_can_be_created(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertTrue($user->exists);
        $this->assertSame((string) $user->getKey(), $user->id());
    }

    public function test_statamic_user_facade_can_create_user(): void
    {
        $user = StatamicUser::make()
            ->email('statamic@example.com')
            ->set('name', 'Statamic User')
            ->password('password123');

        $user->save();

        $this->assertNotNull($user->id());
        $this->assertDatabaseHas('users', ['email' => 'statamic@example.com']);
    }
}
