<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Project;
use Tests\DuskTestCase;
use Faker\Factory as Faker;

class ProjectTest extends DuskTestCase
{

    /**
     * @return void
     * @throws \Throwable
     */
    public function testCreate(): void
    {
        $namePrefix = 'test_cr ';
        $data = $this->createProjectForFurtherTesting($namePrefix);

        $this->assertDatabaseHas('projects', ['name' => $data['name'], 'description' => $data['description']]);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testUpdate(): void
    {
        $namePrefix = 'test_upd ';
        $data = $this->createProjectForFurtherTesting($namePrefix);
        $project = \App\Models\Project::where('name', '=', $data['name'])->orderBy('id', 'desc')->first();
        $this->browse(function (Browser $browser) use ($project) {
            $browser->click('@edit'.$project->id)
                ->assertRouteIs('projects.show', ['id' => $project->id])
                ->click('@edit'.$project->id)
                ->assertRouteIs('projects.edit', ['id' => $project->id])
                ->append('name', '_upd')
                ->append('description', '_upd')
                ->click('@submit')
                ->assertRouteIs('projects.index');
        });
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => $data['name'].'_upd', 'description' => $data['description'].'_upd']);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function testDelete(): void
    {
        $namePrefix = 'test_del ';
        $data = $this->createProjectForFurtherTesting($namePrefix);
        $project = \App\Models\Project::where('name', '=', $data['name'])->orderBy('id', 'desc')->first();
        $this->browse(function (Browser $browser) use ($project) {
            $browser->click('@edit'.$project->id)
                ->assertRouteIs('projects.show', ['id' => $project->id])
                ->click('@delete')
                ->assertRouteIs('projects.index')
                ->assertNotPresent('@edit'.$project->id);
        });
        $this->assertDatabaseMissing('projects', ['id' => $project->id, 'deleted_at' => null]);
    }

    /**
     * @param string $namePrefix
     * @return array
     * @throws \Throwable
     */
    protected function createProjectForFurtherTesting(string $namePrefix): array
    {
        $faker = Faker::create();
        $name = $namePrefix. $faker->words(2, true);
        $description = $faker->sentence();
        $this->browse(function (Browser $browser) use ($name, $description) {
            $this->loginUser($browser)
                ->visit(new Project)
                ->assertSee('Create new')
                ->click('@create_project')
                ->assertRouteIs('projects.create')
                ->fillInCreateForm($name, $description)
                ->click('@submit')
                ->assertRouteIs('projects.index');
        });

        return ['name' => $name, 'description' => $description];
    }
}
