<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Register extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('register', [], false);
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }

    /**
     * @param Browser $browser
     * @param string $name
     * @param string $email
     * @param string $password
     * @return void
     */
    public function fillInRegisterForm(Browser $browser, string $name, string $email, string $password): void
    {
        $browser->visit($this->url())
            ->type('@name', $name)
            ->type('@email', $email)
            ->type('@password', $password)
            ->type('@password_confirmation', $password);
    }
}
