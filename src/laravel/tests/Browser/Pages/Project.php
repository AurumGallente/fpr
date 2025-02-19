<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Project extends Page
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return route('projects.index', [], false);
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
     * @param string $description
     * @return Browser
     * @throws \Facebook\WebDriver\Exception\ElementClickInterceptedException
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     */
    public function fillInCreateForm(Browser $browser, string $name, string $description)
    {
        $browser
        ->type('@name', $name)
        ->select('@language_id')
        ->type('@description', $description);
        return $browser;
    }
}
