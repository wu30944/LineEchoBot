<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/20
 * Time: 11:08 AM
 */

namespace App\Services;

use App\IFace\IMessageBuilder;
use LINE\LINEBot\MessageBuilder;
use App\Handler\LuisHandler;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use App\Services\BandService;
use Log;


class ServePeopleService
{
    public  function  AskServicePeople($strDutyType=null,$strAsk=null,$strTime=null){

        Log::info($strTime);
        if(!empty($strAsk))
        {
            if(empty($strDutyType)) {

                $strContent = str_replace('%',$strTime,trans('default.AskDuty'));
                return $this->TextMessageBuilder($strContent);

            }else if(empty($strTime)){

                return $this->AskTimeButtonMessageBuilder($strDutyType,$strAsk);


            }else{

                $bandService = new BandService($strDutyType,$strTime);

                return $bandService->getMessageBuilder();
            }
        }else{

            Log::info('$strDutyType:'.$strDutyType.' $strAsk:'.$strAsk.' $strTime:'.$strTime);

            $title=str_replace('%', $strTime.$strDutyType, trans('default.NotAsk'));
            $yesContent = str_replace('%', $strTime . $strDutyType, trans('default.Who'));
            $noContent = trans('default.TalkOtherThing');
            return $this->ConfirmTemplateBuilder($title,$yesContent,$noContent);

        }

    }

    private function ConfirmTemplateBuilder($title,$yesContent,$noContent){

        return new TemplateMessageBuilder(trans('default.Check'),
            new ConfirmTemplateBuilder($title, [
                new PostbackTemplateActionBuilder(trans('default.Yes'), $yesContent),
                new PostbackTemplateActionBuilder(trans('default.No'), $noContent),
            ])
        );
    }

    private function TextMessageBuilder($content){
        return new TextMessageBuilder($content);
    }

    private function AskTimeButtonMessageBuilder($dutyType,$ask){

        $thisWeek = trans('default.ThisWeek');
        $nextWeek = trans('default.NextWeek');
        $neither = trans('default.Neither');
        $neitherMessage = trans('default.NeitherMessage');

        return new TemplateMessageBuilder(
            '時間詢問',
            new ButtonTemplateBuilder(
                '時間詢問',
                '你想問的是什麼時候',
                'https://example.com/thumbnail.jpg',
                [
                    new PostbackTemplateActionBuilder($thisWeek, '1|'.$thisWeek.$dutyType.$ask),
                    new PostbackTemplateActionBuilder($nextWeek, '1|'.$nextWeek.$dutyType.$ask),
                    new PostbackTemplateActionBuilder($neither, '2|'.$neitherMessage)
                ]
            )
        );
    }
}