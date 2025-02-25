<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Faker\Factory as Faker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;
use App\Models\User;
use Tests\Browser\Pages\Login;

abstract class DuskTestCase extends BaseTestCase
{

    /**
     * @var string
     */
    public string $basicPassword = '';

    /**
     * @var User
     */
    public User $user;

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
        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }

        $faker = Faker::create();
        $this->basicPassword = env('DUSK_DEFAULT_USER_PASSWORD') ?: $faker->password(8);

        if(env('DUSK_DEFAULT_ACTOR'))
        {
            $user = User::where('email', '=', env('DUSK_DEFAULT_ACTOR'))->first();
            if(!$user)
            {
                $user = User::create([
                    'name' => $faker->name(),
                    'email' => env('DUSK_DEFAULT_ACTOR'),
                    'password' => Hash::make(env('DUSK_DEFAULT_USER_PASSWORD') ?: $faker->password(10)),
                ]);
            }
        } else
        {
            do {
                $email = $faker->unique()->safeEmail;
                $user = User::where('email', $email)->first();
            } while($user);
            $user = User::create([
                'name' => $faker->name(),
                'email' => $email,
                'password' => Hash::make(env('DUSK_DEFAULT_USER_PASSWORD') ?: $faker->password(10)),
            ]);
        }
        $this->user = $user;
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

    /**
     * @param Browser $browser
     * @return Browser
     */
    protected function loginUser(Browser $browser): Browser
    {
        $browser->visit(new Login)
            ->fillInLoginForm($this->user->email, $this->basicPassword)
            ->click('@submit');

        return $browser;
    }
}
