<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/1
 * Time: 11:32 PM
 */

namespace App\Handler;

use Log;


class LuisHandler
{


// NOTE: Be sure to uncomment the following line in your php.ini file.
// ;extension=php_openssl.dll

// **********************************************
// *** Update or verify the following values. ***
// **********************************************

// The ID of a public IoT LUIS app that recognizes intents for turning on and off lights
    private $appId = "";

// Replace with your endpoint key.
// You can use the authoring key instead of the endpoint key.
// The authoring key allows 1000 endpoint queries a month.
    private $endpointKey = "";

// The endpoint URI below is for the West US region.
// If your subscription is in a different region, update accordingly.
    private $endpoint = "";

// The LUIS query term
    private $term = "turn on the left light";

    private $headers = "";


    private $luisJsonResult;

    private $weeks;


    public function __construct($strQuery=null)
    {
         $this->appId = env('LUIS_APP_ID');

         $this->endpointKey = env('LUIS_END_POINT_KEY');

         $this->endpoint = "https://westus.api.cognitive.microsoft.com/luis/v2.0/apps/";

         $this->headers="Ocp-Apim-Subscription-Key: ";

         $this->weeks=$this->get_week( date('Y',strtotime('now')));

         $this->luisJsonResult = $this->getAnalyzeResult($strQuery);
    }


    private function AnalyzeText($query) {
        // Prepare HTTP request
        // NOTE: Use the key 'http' even if you are making an HTTPS request. See:
        // http://php.net/manual/en/function.stream-context-create.php
        $headers = $this->headers.$this->endpointKey."\r\n";
        $options = array ( 'http' => array (
            'header' => $headers,
            'method' => 'GET',
            'ignore_errors' => true));

        // build query string
        $qs = http_build_query( array (
                "q" => $query,
                "verbose" => "false",
            )
        );

        $url = $this->endpoint . $this->appId . "?" . $qs;
//        print($url);

        // Perform the Web request and get the JSON response
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

    private function getAnalyzeResult($strQuery){
        // check length of key
        if (strlen($this->endpointKey) == 32) {

            $json = $this->AnalyzeText($strQuery);
            $this->luisJsonResult =json_decode($json,true);

            Log::info($this->luisJsonResult);
            return $this->luisJsonResult;

        } else {

            print("Invalid LUIS key!\n");
            print("Please paste yours into the source code.\n");


        }

    }

    function getTopScoringIntent(){
        if(!empty($this->luisJsonResult)){
            return $this->luisJsonResult['topScoringIntent']['intent'];
        }
    }

    function getIntentScore(){
        if(!empty($this->luisJsonResult)){
            return $this->luisJsonResult['topScoringIntent']['score'];
        }
    }

    function getEntity($strType){
        if(!empty($this->luisJsonResult)){
            foreach ($this->luisJsonResult['entities'] as $item){
                if($item['type']==$strType){
                    return $item['entity'];
                }
            }
        }

    }

    function getQuery(){
        if(!empty($this->luisJsonResult))
        {
            return $this->luisJsonResult['query'];
        }
    }

    private function get_week($year) {
        $year_start = $year . "-01-01";
        $year_end = $year . "-12-31";
        $startday = strtotime($year_start);
        if (intval(date('N', $startday)) != '1') {
            $startday = strtotime("next monday", strtotime($year_start)); //获取年第一周的日期
        }
        $year_mondy = date("Y-m-d", $startday); //获取年第一周的日期

        $endday = strtotime($year_end);
        if (intval(date('W', $endday)) == '7') {
            $endday = strtotime("last sunday", strtotime($year_end));
        }

        $num = intval(date('W', $endday));
        for ($i = 1; $i <= $num; $i++) {
            $j = $i -1;
            $start_date = date("Y-m-d", strtotime("$year_mondy $j week "));

            $end_day = date("Y-m-d", strtotime("$start_date +6 day"));

            $week_array[$i] = array (
                str_replace("-",
                    ".",
                    $start_date
                ), str_replace("-", ".", $end_day));
        }
        return $week_array;
    }



}