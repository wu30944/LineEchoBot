<?php
namespace App\Controllers;
use LINE\LINEBot\Event\PostbackEvent;
use App\Controllers\EventHandler;
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
        $userId=$this->req->{"events"}[0]->{"source"}->{"userId"};

        if(property_exists($this->req->{"events"}[0]->{"source"},'groupId')){
          $groupId=$this->req->{"events"}[0]->{"source"}->{"groupId"};
      }

      if(property_exists($this->req->{"events"}[0]->{"source"},'roomId')){
          $roomId=$this->req->{"events"}[0]->{"source"}->{"roomId"};
      }

      $data=$this->req->{"events"}[0]->{"postback"}->{"data"};
      $dataObj = explode('|',$data);
      Log::info("User:".$userId." Answer:".$dataObj[1]." question_id:".$dataObj[0]);
      return  $this->replyText("收到了，您回答".$dataObj[1]);

  }
}