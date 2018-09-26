<?php
namespace App\Controllers;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use App\Controllers\EventHandler;
use App\Controllers\BotBrainController;
use App\Controllers\UpdateUserInfo;
use Log;

class TextMessageEventHandler extends EventHandler
{
    protected $brain;
    public function __construct($bot, $req, TextMessage $event)
    {
        $this->bot = $bot;
        $this->req = $req;
        $this->event = $event;
        $this->brain = new BotBrainController;
    }

    public function handle()
    {
      //使用者的文字
      $userText = $this->event->getText();

      $arrayObj = $this->brain->handle($userText);
      if(is_null($arrayObj)) return null;
      /*
      Log::info('TextMessage');
      Log::info('textMessage Type:'.$this->event->getType());
      Log::info('userText:'.$userText);
      Log::info('userId:'.$this->getUserId());
      */

      if(array_key_exists('text',$arrayObj)){
        Log::info("回傳文字:".$arrayObj['text']);
        return $this->replyText($arrayObj['text']);
      }elseif(array_key_exists('sticker',$arrayObj)){
        $obj= $arrayObj['sticker'];
        return $this->replySticker($obj['packageId'],$obj['stickerId']);
      }elseif(array_key_exists('location', $arrayObj)){
        Log::info("回傳地址");
        $obj = $arrayObj['location'];
        return $this->replyLocation($obj['title'],$obj['address'],$obj['latitude'],$obj['longitude']);
      }elseif(array_key_exists('image', $arrayObj)){
        Log::info("回傳圖檔");
        $obj = $arrayObj['image'];
        return $this->replyImage($obj['originalContentUrl'],$obj['previewImageUrl']);
      }
  }

}