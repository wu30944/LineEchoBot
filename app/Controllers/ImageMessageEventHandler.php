<?php
namespace App\Controllers;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use App\Controllers\EventHandler;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Log;

class ImageMessageEventHandler extends EventHandler
{

    public function __construct($bot, $req, ImageMessage $event)
    {
        $this->bot = $bot;
        $this->req = $req;
        $this->event = $event;
    }

    public function handle()
    {
        Log::info('ImageMessage');
        $this->messageId=$this->getMessageId();
        $content= $this->getLineContentByMessageId($this->messageId);
        //Log::info($content_type);
        Storage::put($this->messageId, $content);

    }

  //API用AccessToken取Image內容
  protected function getLineContentByMessageId($messageId)
  {
      $access_token=$this->getAccessToken();

      $http = new Client;
      $api_url=sprintf("https://api.line.me/v2/bot/message/%s/content",$this->messageId);
      $response = $http->request('GET', $api_url, [
      'headers' => [
          'Authorization' => 'Bearer '.$access_token,
      ],
      ]);
      return $response->getBody();
  }

}