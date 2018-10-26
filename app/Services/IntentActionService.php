<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/15
 * Time: 10:57 PM
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
use App\Services\BandService;
use App\Services\ServePeopleService;
use Log;

class IntentActionService implements IMessageBuilder
{

    private $objLuis;

    private $returnMessageBuilder;


    public function __construct(LuisHandler $Luis)
    {
        $this->objLuis = $Luis ;
    }



   public function DecideReplyContent(){

        $strIntent = $this->objLuis->getTopScoringIntent();
        $strIntentScore = $this->objLuis->getIntentScore();


        if($strIntentScore > 0.7)
        {
            if($strIntent=="詢問服事人員"){
                $strDutyType = $this->objLuis->getEntity("職務類型");
                $strAsk = $this->objLuis->getEntity("詢問");
                $strTime = $this->objLuis->getEntity("時間");

                $servePeopleService = new ServePeopleService();
                $this->returnMessageBuilder=$servePeopleService->AskServicePeople($strDutyType,$strAsk,$strTime);
//                $this->AskServicePeople();

            }else if($strIntent == "詢問講道主題"){

            }else if($strIntent == "問候"){

            }
            return true;

        }else{
            Log::info($strIntentScore);
            return false;

        }
   }



    private function NotUnderstandSyntax(){

        $strQuery = $this->objLuis->getQuery();

        $this->returnMessageBuilder = new TextMessageBuilder(str_replace('%', $strQuery, trans('default.NotUnderstand')));

    }




    public function getMessageBuilder(): MessageBuilder
    {
        // TODO: Implement getMessageBuilder() method.

        return $this->returnMessageBuilder;
    }




}