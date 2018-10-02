<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/2
 * Time: 10:45 PM
 */

namespace App\Controllers;

use Illuminate\Http\Request;


class BandServeController
{

    private $office;
    private $time;

    private $result;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct($office = '', $time = '')
    {

        $this->office = $this->getDuty($office);
        $this->time = $this->getDate($time);

        $this->result=$this->GoogleDocContent(env('GOOGLE_DOC_URL', ''));
    }

    private function GoogleDocContent($googleDocUrl)
    {
        if ($googleDocUrl != "") {
            $json = file_get_contents($googleDocUrl);
            $data = json_decode($json);

           return  $data->{'feed'}->{'entry'};
        }
    }

    public function getGoogleDocContent()
    {
        return $this->result;
    }

    public function getCondictionData()
    {
        foreach ($this->result as $item) {

            if ($item->{'gsx$日期'}->{'$t'} == $this->time) {
                if($this->office=="all"){
                    return $item->{'gsx$all'}->{'$t'};
                }else{
                    return $item->{'gsx$'.$this->office}->{'$t'};
                }
            }

        }
    }

    private function getDate($strTime)
    {
        if ($strTime == '本週' || $strTime == '當週' || $strTime == '這禮拜' || $strTime == '這次') {
            return date('Y-m-d', strtotime('next sunday', strtotime('today')));
        }else{
            return date('Y-m-d', strtotime('next sunday', strtotime('today')));
        }
    }

    private function getDuty($strDuty)
    {
        $primarySign = array("領唱", "主領", "帶領", "帶唱");
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


}