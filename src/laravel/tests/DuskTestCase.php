<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;
use App\Models\User;

abstract class DuskTestCase extends BaseTestCase
{

    /**
     * @var string
     */
    public string $basicPassword = '';
    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {

    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->basicPassword = env('DUSK_DEFAULT_USER_PASSWORD');

    }


    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
                '--whitelisted-ips=""',
                '--disable-dev-shm-usage',
            ]);
        })->all());


        return RemoteWebDriver::create(
            env('DUSK_SERVER_URL'),
            DesiredCapabilities::chrome()
                ->setCapability(ChromeOptions::CAPABILITY, $options)
        );
    }
}
