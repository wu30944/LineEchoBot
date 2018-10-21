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

class BotBrainService
{
    private $query;
//    private $returnObj;

    public function __construct($strQuery)
    {
        $this->query  = $strQuery ;
    }

    public function handle()
    {

        $strQuery = $this->query ;

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