<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/16
 * Time: 9:56 PM
 */

namespace App\Services;

use App\IFace\IMessageBuilder;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Log;

class BandService implements IMessageBuilder
{
    private $strDuty;
    private $strTime;

    private $googleDocContent;

    public function __construct($strDuty=null,$strTime=null)
    {
        $this->strDuty = $this->getDuty($strDuty);
        $this->strTime = $this->getDate($strTime);
        $this->googleDocContent = $this->GoogleDocContent(env('GOOGLE_DOC_URL', ''));
    }

    private function GoogleDocContent($googleDocUrl)
    {
        if ($googleDocUrl != "") {
            $json = file_get_contents($googleDocUrl);
            $data = json_decode($json);

            return  $data->{'feed'}->{'entry'};
        }
    }

    private function getDuty($strDuty)
    {
        $primarySign = array("領唱", "主領", "帶領", "帶唱","唱");
        $keyboard = array("鋼琴", "keyboard", "彈琴", "琴");
        $guitar = array("吉他", "guitar", "及他","吉他手");
        $bass = array("貝士", "bass", "BASS",'BASS手');
        $drum = array("drum", "Drum", "打鼓", "鼓手",'鼓');

        if (in_array($strDuty,$primarySign)) {
            return '領唱';
        } else if (in_array($strDuty,$keyboard)){
            return '鋼琴';
        }else if (in_array($strDuty,$guitar)){
            return '吉他';
        }else if (in_array($strDuty,$bass)){
            return 'bass';
        }else if (in_array($strDuty,$drum)){
            return '鼓';
        }else {
            return 'all';
        }
    }

    private function getDate($strTime)
    {
        if ($strTime == '本週' || $strTime == '當週' || $strTime == '這禮拜' || $strTime == '這次') {
            return date('Y-m-d', strtotime('next sunday', strtotime('today')));
        }if ($strTime == '下週' || $strTime == '下次' || $strTime == '下禮拜' ) {
            return date('Y-m-d', strtotime('next sunday', strtotime('today')));
        }else{

            $strTime = str_replace('-','',$strTime);
            $strTime = str_replace('/','',$strTime);
            $strTime = str_replace(' ','',$strTime);
            return date('Y-m-d', strtotime($strTime));
        }
    }

    private function getCondictionData()
    {
        foreach ($this->googleDocContent as $item) {

            if ($item->{'gsx$日期'}->{'$t'} == $this->strTime) {
                if($this->strDuty=="all"){
                    return $item->{'gsx$all'}->{'$t'};
                }else{
                    return $item->{'gsx$'.$this->strDuty}->{'$t'};
                }
            }

        }
        return null;//str_replace('%',$this->strTime,trans('default.NonSunday'));
    }

    public function getMessageBuilder(): MessageBuilder
    {

        // TODO: Implement getMessageBuilder() method.
        if($this->strTime== date('Y-m-d', strtotime('19700101'))){
            return new TextMessageBuilder(trans('default.DateFormat'));
        }else{
            $strReplyContent = $this->getCondictionData();
        }

        if(empty($strReplyContent)){
            return new TextMessageBuilder(trans('default.NotFind'));
        }else{
            return new TextMessageBuilder(trans('default.Search'), $strReplyContent, trans('default.ResultMessage'));
        }

    }

}
