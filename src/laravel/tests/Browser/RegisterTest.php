<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Register;
use Tests\DuskTestCase;
use Illuminate\Routing\Route;
use Faker\Factory as Faker;
use App\Models\User;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testRegister(): void
    {
        $this->browse(function (Browser $browser) {
            $faker = Faker::create();
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
    }
}
