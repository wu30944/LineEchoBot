<?php
namespace App\Controllers;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use App\Controllers\EventHandler;
use Log;

class StickerMessageEventHandler extends EventHandler
{

    public function __construct($bot, $req, StickerMessage $event)
    {
        $this->bot = $bot;
        $this->req = $req;
        $this->event = $event;
    }

    public function handle()
    {
     Log::info('StickerMessage');
     $packageId=$this->getPackageId();
     $stickerId=$this->getStickerId();
     Log::info("packageId:".$packageId." stickerId:".$stickerId);
     if($packageId=='1142048'&&$stickerId=='5793212'){
        $resp = $this->replyText('生日快樂!!');
    }
}

}