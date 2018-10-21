<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/20
 * Time: 9:01 PM
 */

namespace App\Services;

use App\IFace\IMessageBuilder;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;


class StickerService implements IMessageBuilder
{
    private $packageId;
    private $stickerId;

    public function DecideReplyContent($query){

        //回傳貼圖
        //參考連結
        //https://developers.line.me/media/messaging-api/messages/sticker_list.pdf
        if ($query == "棒" || $query == "讚" || $query == "讚讚"||stristr($query, "讚唷")
            ||stristr($query, "很讚") || stristr($query, "很棒")) {
           $this->packageId = "1";
           $this->stickerId = "14";
        }else if ($query == "哈" || $query == "哈哈" || stristr($query, "哈哈")) {
            $this->packageId = "1";
            $this->stickerId = "100";
        }else if ($query == "笑死了" || stristr($query, "笑死")) {
            $this->packageId = "1";
            $this->stickerId = "110";
        }else if (stristr($query,"蛤") || $query == "什麼" || $query == "??" || stristr($query, "什麼意思")) {
            $this->packageId = "2";
            $this->stickerId = "149";
        }else if (stristr($query,"88")||stristr($query,"掰掰") || $query == "再見" || stristr($query, "bye")) {
            $this->packageId = "1";
            $this->stickerId = "408";
        }else if (stristr($query, "好冷")||stristr($query, "很冷")|| stristr($query, "溫度很低")) {
            $this->packageId = "2";
            $this->stickerId = "29";
        }else if (stristr($query, "辛苦了")|| stristr($query, "謝謝")||stristr($query, "感謝")||stristr($query, "感恩")) {
            $this->packageId = "2";
            $this->stickerId = "41";
        }else if (stristr($query, "ok")|| stristr($query, "沒問題")|| $query=="可以" || $query=="行") {
            $this->packageId = "2";
            $this->stickerId = "179";
        }else if (stristr($query, "拜託")) {
            $this->packageId = "1";
            $this->stickerId = "4";
        }else if (stristr($query, "wu-bot")||stristr($query, "wu bot")) {
            $this->packageId = "1";
            $this->stickerId = "114";
        }

        if(empty($this->packageId) || empty($this->stickerId)){
            return false;
        }else{
            return true;
        }

    }


    public function GetMessageBuilder(): MessageBuilder
    {
        // TODO: Implement getMessageBuilder() method.
        if(!empty($this->stickerId) && !empty($this->packageId)){
            return new StickerMessageBuilder($this->packageId, $this->stickerId);
        }else{

        }

    }


}