<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/18
 * Time: 10:58 PM
 */

namespace App\Handler;


use LINE\LINEBot\Event\PostbackEvent;
use App\Handler\EventHandler;
use Log;
use App\Services\MessageBuilderService;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use App\Services\BotBrainService;


class PostbackEventHandler extends EventHandler
{

    public function __construct($bot, $req, PostbackEvent $event)
    {
        $this->bot = $bot;
        $this->req = $req;
        $this->event = $event;
    }
    //處理PostbackEvent
    public function handle()
    {
        Log::info("PostbackEvent");
        $jsonObj = json_decode($this->req->getBody());

        //回答的使用者
//        if(property_exists($this->jsonObj->{"events"}[0]->{"source"}->{"userId"})){
//            $userId=$jsonObj->{"events"}[0]->{"source"}->{"userId"};
//        }
//
//
//        if(property_exists($jsonObj->{"events"}[0]->{"source"},'groupId')){
//          $groupId=$jsonObj->{"events"}[0]->{"source"}->{"groupId"};
//      }
//
//      if(property_exists($jsonObj->{"events"}[0]->{"source"},'roomId')){
//          $roomId=$jsonObj->{"events"}[0]->{"source"}->{"roomId"};
//      }
//
//      $data=$jsonObj->{"events"}[0]->{"postback"}->{"data"};
//        error_log($data);
////      $dataObj = explode('|',$data);
////      Log::info("User:".$userId." Answer:".$dataObj[1]." question_id:".$dataObj[0]);
//        $objMessageBuilder = new MessageBuilderService();
        $strPostback = $jsonObj->{"events"}[0]->{"postback"}->{"data"};
        $dataObj = explode('|',$strPostback);
        $strPostBackCode = $dataObj[0];
        $strPostBackMessage = $dataObj[1];
        $arrayObj = null;

        if($strPostBackCode=="1")
        {
            $botBrainService = new BotBrainService($strPostBackMessage);
            $arrayObj = $botBrainService->handle();

        }else if($strPostBackCode=="2"){
            $arrayObj = ['MessageBuilder'=>new TextMessageBuilder($strPostBackMessage)];
        }



        if(is_null($arrayObj))
            return null;

        if(array_key_exists('MessageBuilder',$arrayObj)){

            if (env('APP_ENV') == 'testing')
            {
                return $this->pushMessage($arrayObj['MessageBuilder']);
            }else{
                return $this->replyMessage($arrayObj['MessageBuilder']);
            }

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