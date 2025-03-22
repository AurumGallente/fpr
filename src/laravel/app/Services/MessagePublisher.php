<?php

namespace App\Services;

use GuzzleHttp\Client as HttpClient;
use denis660\Centrifugo\Centrifugo;

class MessagePublisher
{

    /**
     * @var MessagePublisher
     */
    private static MessagePublisher $self;

    /**
     * @var HttpClient
     */
    private static HttpClient $httpClient;

    /**
     * @var Centrifugo
     */
    public private(set) Centrifugo $Centrifugo;

    /**
     * @param array $config
     * @param HttpClient $httpClient
     */
    private function __construct(array $config, HttpClient $httpClient)
    {
        $this->Centrifugo = new Centrifugo($config, $httpClient);
    }

    /**
     * @return MessagePublisher
     */
    public static function getInstance(): MessagePublisher
    {
        if(!self::$self)
        {
            self::$self = new MessagePublisher(self::getConfig(), self::getClient());
        }

        return self::$self;
    }
    /**
     * @return array
     */
    private static function getConfig(): array
    {
        return [
            'url' => env('CENTRIFUGO_URL'),
            'api_key' => env('CENTRIFUGO_API_KEY'),
            'token_hmac_secret_key'=>env('CENTRIFUGO_TOKEN_HMAC_SECRET_KEY')
        ];
    }

    /**
     * @return HttpClient
     */
    private static function getClient(): HttpClient
    {
        if(!self::$httpClient)
        {
            self::$httpClient = new HttpClient([]);
        }
        return self::$httpClient;
    }

    /**
     * @return Centrifugo
     */
    public function getMessenger(): Centrifugo
    {
        return $this->Centrifugo;
    }

}
