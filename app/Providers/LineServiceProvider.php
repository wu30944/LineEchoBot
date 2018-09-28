<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use App\Services\LineBotService;

class LineServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->lineBotRegister();
        $this->lineBotServiceRegister();
        $this->LineBotInstance();

    }

    private function LineBotInstance(){
        $channelSecret = env('LINEBOT_CHANNEL_SECRET');
        $channelToken = env('LINEBOT_CHANNEL_TOKEN');
        $apiEndpointBase = env('LINEBOT_API_ENDPOINT_BASE');
        $bot = new LINEBot(new CurlHTTPClient($channelToken), [
            'channelSecret' => $channelSecret,
            'endpointBase' => $apiEndpointBase, // <= Normally, you can omit this
        ]);
        $this->app->instance('LINE\LINEBot', $bot);
    }

    private function lineBotRegister()
    {
        $channelSecret = env('LINEBOT_CHANNEL_SECRET');
        $channelToken = env('LINEBOT_CHANNEL_TOKEN');
        $apiEndpointBase = env('LINEBOT_API_ENDPOINT_BASE');
        $bot = new LINEBot(new CurlHTTPClient($channelToken), [
            'channelSecret' => $channelSecret,
            'endpointBase' => $apiEndpointBase, // <= Normally, you can omit this
        ]);
        $this->app->singleton('LINE\LINEBot', $bot);
    }

    private function lineBotServiceRegister()
    {
        $this->app->singleton(LineBotService::class, function () {
            return new LineBotService(env('LINEBOT_USER_ID'));
        });
    }
}
