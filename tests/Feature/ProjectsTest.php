<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guest_may_not_create_project()
    {
        $attributes = Project::factory()->raw();

        $this->post('/project', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guest_may_not_view_project()
    {
        $this->get('/projects')->assertRedirect('login');
    }

    /** @test */
    public function guest_may_not_view_a_single_project()
    {
        $project = Project::factory()->create();

        $this->get($project->path())->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_cannot_see_other_user_projects()
    {
        $this->be(User::factory()->create());

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->be(User::factory()->create());

        $this->withoutExceptionHandling();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function a_user_can_create_a_project()
    {

        //Not using factory because in factory user is also created
        //and we're making user in test for the sake of authentication
        //this gives us error so we're using just attributes.
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/project', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        //Create a user and act as authenticated user
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/project', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/project', $attributes)->assertSessionHasErrors('description');
    }
}
