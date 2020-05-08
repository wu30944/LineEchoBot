<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/17
 * Time: 10:29 PM
 */

namespace App\Handler;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use App\Handler\EventHandler;
use App\Controllers\BotBrainController;
use App\Services\BotBrainService;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Log;


class TextMessageEventHandler extends EventHandler
{
    protected $brain;

    public function __construct($bot, $req, TextMessage $event)
    {
        $this->bot = $bot;
        $this->req = $req;
        $this->event = $event;
    }

    /**
     * @return null
     */
    public function handle()
    {
        // TODO: Implement handle() method.
        //使用者的文字
        $userText = $this->event->getText();

        if($userText=="請問userid是什麼"){
            return $this->replyMessage(new TextMessageBuilder($this->getUserId()));
        }

        $botBrainService = new BotBrainService($userText);
        $arrayObj = $botBrainService->handle();

        if(is_null($arrayObj)) return null;


        if(array_key_exists('MessageBuilder',$arrayObj)){

            if (env('APP_ENV') == 'testing')
            {
                return $this->pushMessage($arrayObj['MessageBuilder']);
            }else{
                return $this->replyMessage($arrayObj['MessageBuilder']);
            }

        }elseif(array_key_exists('sticker',$arrayObj)){
            if (env('APP_ENV') == 'testing')
            {
                return $this->pushMessage($arrayObj['MessageBuilder']);
            }else{
                return $this->replyMessage($arrayObj['MessageBuilder']);
            }
//            $obj= $arrayObj['sticker'];
//            return $this->replySticker($obj['packageId'],$obj['stickerId']);
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