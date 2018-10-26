<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/17
 * Time: 10:41 PM
 */

namespace App\Services;

use App\Handler\LuisHandler;
use App\Services\IntentActionService;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use App\Services\StickerService;
use Log;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class BotBrainService
{
    private $query;
//    private $returnObj;
    private $event;

    public function __construct($strQuery,TextMessage $event)
    {
        $this->query  = $strQuery ;
        $this->event = $event;
    }

    public function handle()
    {

        $strQuery = $this->query ;

        if($strQuery=="userid"){
            return ['MessageBuilder'=> new TextMessageBuilder($this->event->getUserId())];
        }



        $strStickerService = new StickerService();
        $isReplySticker = $strStickerService->DecideReplyContent($strQuery);
        if($isReplySticker){
            return ['MessageBuilder'=> $strStickerService->GetMessageBuilder()];
        }

        $luisHandler = new LuisHandler($strQuery);
        $intentService = new IntentActionService($luisHandler);

        $isReply = $intentService->DecideReplyContent();


        if($isReply){
           return   ['MessageBuilder' => $intentService->getMessageBuilder()];
        }

    }

    private function StickerMessageBuilder($packageID,$stickerId){
        $stickerMessageBuilder = new StickerMessageBuilder($packageID, $stickerId);
        return ['MessageBuilder'=>$stickerMessageBuilder];
    }
}