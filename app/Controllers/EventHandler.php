<?php

namespace App\Controllers;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use Log;

abstract class EventHandler
{
    abstract protected function handle();
    protected $bot;
    protected $event;
    protected $req;
    protected $jsonObj;
    private $replyToken;

    public function getEvent(){ return $this->event; }
    public function getBot(){ return $this->bot; }
    public function getReq(){ return $this->req; }

    protected function getAccessToken(){
        return env("LINEBOT_CHANNEL_TOKEN");
    }

    public function getUserId(){
        $this->jsonObj = json_decode($this->getReq()->getBody());
        if(property_exists($this->jsonObj->{"events"}[0]->{"source"},'userId')){
            return $this->jsonObj->{"events"}[0]->{"source"}->{"userId"};
        }else{
            return "";
        }
    }
    //stidker使用
    public function getPackageId(){
        $this->jsonObj = json_decode($this->getReq()->getBody());
        if(property_exists($this->jsonObj->{"events"}[0]->{"message"},'packageId')){
            return $this->jsonObj->{"events"}[0]->{"message"}->{"packageId"};
        }else{
            return "";
        }
    }

    public function getStickerId(){
        $this->jsonObj = json_decode($this->getReq()->getBody());
        if(property_exists($this->jsonObj->{"events"}[0]->{"message"},'stickerId')){
            return $this->jsonObj->{"events"}[0]->{"message"}->{"stickerId"};
        }else{
            return "";
        }
    }

    public function getMessageId(){
        $this->jsonObj = json_decode($this->getReq()->getBody());
        if(property_exists($this->jsonObj->{"events"}[0]->{"message"},'id')){
            return $this->jsonObj->{"events"}[0]->{"message"}->{"id"};
        }else{
            return "";
        }
    }

    public function getReplyToken() {
      return $this->getEvent()->getReplyToken();
    }

    public function replyMessage($replyMessageBuilder){
        return $this->getBot()->replyMessage($this->getReplyToken(),$replyMessageBuilder);
    }

    public function pushMessage($replyMessageBuilder){
        return $this->getBot()->pushMessage(env('LINEBOT_USER_ID'),$replyMessageBuilder);
    }

    public function replyText($replyMessage){
        return $this->getBot()->replyText($this->getReplyToken(), $replyMessage);
    }

    //回傳圖片
    public function replyImage($originalContentUrl,$previewImageUrl){
        $messageBuilder = new ImageMessageBuilder($originalContentUrl, $previewImageUrl);
        $resp = $this->getBot()->replyMessage($this->getReplyToken(), $messageBuilder);
        if ($resp->isSucceeded()) {
            return $resp;
        }
        return null;
    }

    //回傳Sticker 
    //https://developers.line.me/media/messaging-api/messages/sticker_list.pdf 
    //https://developers.line.me/en/docs/messaging-api/reference/#sticker
    public function replySticker($packageId, $stickerId){
        $messageBuilder = new StickerMessageBuilder($packageId, $stickerId);
        $resp = $this->bot->replyMessage($this->getReplyToken(), $messageBuilder);
        if ($resp->isSucceeded()) {
            return 'Successed!';
        }
        Log::info($resp->getRawBody());
    }
    //回傳影片
    public function replyVideo($originalContentUrl,$previewImageUrl){
        $messageBuilder = new VideoMessageBuilder($originalContentUrl, $previewImageUrl);
        $resp = $this->getBot()->replyMessage($this->getReplyToken(), $messageBuilder);
        if ($resp->isSucceeded()) {
            return 'Successed!';
        }
        Log::info($resp->getRawBody());
    }

    //回傳聲音
    public function replyAudio($originalContentUrl,$duration){
        $messageBuilder = new AudioMessageBuilder($originalContentUrl, $duration);
        $resp = $this->getBot()->replyMessage($this->getReplyToken(), $messageBuilder);
        if ($resp->isSucceeded()) {
            return 'Successed!';
        }
        Log::info($resp->getRawBody());
    }

    //回傳地址
    public function replyLocation($title, $address, $latitude, $longitude){
        $messageBuilder = new LocationMessageBuilder($title, $address, $latitude, $longitude);
        $resp = $this->bot->replyMessage($this->getReplyToken(), $messageBuilder);
          if ($resp->isSucceeded()) {
            return 'Successed!';
          }
        Log::info($resp->getRawBody());
    }
}