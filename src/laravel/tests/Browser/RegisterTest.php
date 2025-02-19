<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Register;
use Tests\DuskTestCase;
use Faker\Factory as Faker;
use App\Models\User;

class RegisterTest extends DuskTestCase
{

    /**
     * @return void
     * @throws \Throwable
     */
    public function testRegister(): void
    {
        $email = '';
        $faker = Faker::create();
        $this->browse(function (Browser $browser) use (&$email, $faker) {
            $name = $faker->name;
            $password = $this->basicPassword;
            do {
                $email = $faker->unique()->safeEmail;
                $user = User::where('email', $email)->first();
            } while($user);

            $browser->visit(new Register)
                ->fillInRegisterForm($name, $email, $password)
                ->click('@submit')
                ->assertRouteIs('dashboard');
        });
        $user = User::where('email', $email)->first();
        // Check if UserObserver created permissions for a new user
        $this->assertDatabaseHas('permission_user', ['user_id' => $user->id]);
    }

}
