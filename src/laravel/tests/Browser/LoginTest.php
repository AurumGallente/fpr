<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{

    /**
     * @return void
     * @throws \Throwable
     */
    public function testLogin(): void
    {
        $this->browse(function (Browser $browser) {
            $email = $this->user->email;
            $password = $this->basicPassword;
            $browser->visit(new Login)
                ->fillInLoginForm($email, $password)
                ->click('@submit')
                ->assertRouteIs('dashboard');
        });
    }
}
