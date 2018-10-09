<?php
namespace App\Controllers;
use LINE\LINEBot\Event\PostbackEvent;
use App\Controllers\EventHandler;
use Log;
use App\Services\MessageBuilderService;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

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
        $objMessageBuilder = new MessageBuilderService();
        $strPostback = $jsonObj->{"events"}[0]->{"postback"}->{"data"};
        $objMessageBuilder->setMessageBuilder( new TextMessageBuilder($strPostback));

        if (env('APP_ENV') == 'testing')
        {
            return $this->pushMessage($objMessageBuilder->getMessageBuilder());
        }else{
            error_log($jsonObj->{"events"}[0]->{"postback"}->{"data"});
            return $this->replyMessage($objMessageBuilder->getMessageBuilder());
        }

  }
}