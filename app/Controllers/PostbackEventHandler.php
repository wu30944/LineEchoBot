<?php
namespace App\Controllers;
use LINE\LINEBot\Event\PostbackEvent;
use Log;

class PostbackEventHandler implements EventHandler
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
          //回答的使用者
        $userId=$this->jsonObj->{"events"}[0]->{"source"}->{"userId"};

        if(property_exists($this->jsonObj->{"events"}[0]->{"source"},'groupId')){
          $groupId=$this->jsonObj->{"events"}[0]->{"source"}->{"groupId"};
      }

      if(property_exists($this->jsonObj->{"events"}[0]->{"source"},'roomId')){
          $roomId=$this->jsonObj->{"events"}[0]->{"source"}->{"roomId"};
      }

      $data=$this->jsonObj->{"events"}[0]->{"postback"}->{"data"};
      $dataObj = explode('|',$data);
      Log::info("User:".$userId." Answer:".$dataObj[1]." question_id:".$dataObj[0]);
      $resp = $this->replyText("收到了，您回答".$dataObj[1]);

  }
}