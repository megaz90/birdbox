<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function a_user_has_projects()
    {
        //Given we have a user
        $user = User::factory()->create();

        //User should have a project "$user->projects" and it should be a
        //collection instance
        $this->assertInstanceOf(Collection::class, $user->projects);
    }
}
