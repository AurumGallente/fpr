<?php

namespace Tests\Browser;

use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\Register;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testLogin(): void
    {
        $this->browse(function (Browser $browser) {
            $email = env('DUSK_DEFAULT_ACTOR');
            $user = User::where('email', $email)->first();
            $password = $this->basicPassword;

            if(!$user)
            {
                $faker = Faker::create();
                $name = $faker->name;
                $browser->visit(new Login)
                    ->fillInRegisterForm($name, $email, $password)
                    ->click('@submit');
            }

            $browser->visit(new Login)
                ->fillInLoginForm($email, $password)
                ->click('@submit')
                ->assertRouteIs('dashboard');
        });
    }
}
