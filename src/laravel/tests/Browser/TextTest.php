<?php

namespace Tests\Browser;

use App\Models\Project;
use App\Models\Text;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;

class TextTest extends ProjectTest
{

    /**
     * @return void
     * @throws \Throwable
     */
    public function testCreate(): void
    {
        $namePrefix = 'test_cr ';
        $data = $this->createProjectForFurtherTesting($namePrefix);
        $project = Project::where('name', '=', $data['name'])->orderBy('id', 'desc')->first();
        $faker = Faker::create();
        $content = $faker->paragraphs(rand(3,5), true);
        $content = trim(preg_replace('/\s+/', ' ', $content));
        $this->browse(function (Browser $browser) use ($project, $content) {
            $browser->click('@edit'.$project->id)
                ->assertRouteIs('projects.show', ['id' => $project->id])
                ->click('@create_text')
                ->assertRouteIs('projects.texts.create', ['id' => $project->id])
                ->type('@content', $content)
                ->click('@submit')
                ->assertRouteIs('projects.show', ['id' => $project->id]);
        });

        $this->assertDatabaseHas('projects', ['name' => $data['name'], 'description' => $data['description']]);
        $this->assertDatabasehas('texts', ['project_id' => $project->id, 'content' => $content, 'user_id' => $this->user->id]);

        $text = Text::where('content', '=', $content)
            ->where('project_id', $project->id)
            ->where('user_id', $this->user->id)
            ->orderBy('id', 'desc')
            ->first();
        $this->browse(function (Browser $browser) use ($text) {
            $browser->assertPresent('@text_'.$text->id)
                ->click('@text_'.$text->id)
                ->assertRouteIs('texts.show', ['id' => $text->id])
                ->assertSee($text->content);
        });
    }

    /**
     * @return void
     */
    public function testEdit(): void
    {
        // no edit for texts
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        // no deletions for texts
    }
}
