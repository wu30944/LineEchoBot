<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/9/27
 * Time: 9:37 PM
 */

namespace App\Http\Controllers;

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Illuminate\Http\Request;
use Fukuball\Jieba\Posseg;
use Fukuball\Jieba\JiebaAnalyse;
use Illuminate\Support\Facades\Redis;
use App\Handler\LuisHandler;
use Symfony\Component\Debug\Debug;
use App\Controllers\BandServeController;

class JiebaController extends Controller
{

    /*
        |--------------------------------------------------------------------------
        | Welcome Controller
        |--------------------------------------------------------------------------
        |
        | This controller renders the "marketing page" for the application and
        | is configured to only allow guests. Like most of the other sample
        | controllers, you are free to modify or remove it as you desire.
        |
        */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('Welcome');
        $json = file_get_contents('https://spreadsheets.google.com/feeds/list/11ikBGVGKRN_zyoCJ4XRlBa0ye_cQ4tPMogVMRVRT-_I/od6/public/values?alt=json');
        $data = json_decode($json);
//        $result = $data;
//        echo $json;
        $rows = $data->{'feed'}->{'entry'};
//        foreach($rows as $row) {
//            echo '<p>';
//            $date = $row->{'gsx$日期'}->{'$t'};
//            $primarySing = $row->{'gsx$領唱'}->{'$t'};
//            $piano = $row->{'gsx$鋼琴'}->{'$t'};
//            $bass = $row->{'gsx$bass'}->{'$t'};
//            $guitar = $row->{'gsx$吉他'}->{'$t'};
//            $drum = $row->{'gsx$鼓'}->{'$t'};
//            $assistantSing = $row->{'gsx$配唱'}->{'$t'};
//            echo '日期：'.$date.' 領唱:'.$primarySing .' 鋼琴：'.$piano. ' BASS:' . $bass . ' 吉他：'.$guitar.' 鼓：'.$drum .' 配唱：'.$assistantSing;
//            echo '</p>';
//        }
        $strGuitar = '吉他';
        foreach ($rows as $item) {
//            $keywords = explode(',', $item['gsx$keyword']['$t']);
            if ($item->{'gsx$日期'}->{'$t'} == '2018-10-07') {
                if($strGuitar=="ALL"){
                    return $item->{'gsx$ALL'}->{'$t'};
                }else{
                    return $item->{'gsx$'.$strGuitar}->{'$t'};
                }
            }
        }


        return 1;
    }

    /**
     * jiebaProcess
     *
     * @return Response
     */
    public function jiebaProcess(Request $request)
    {


        $paragraph = $request->input('paragraph');

        $handlerLius = new LuisHandler();
        $handlerLius->getAnalyzeResult($paragraph);
        if($handlerLius->getTopScoringIntent()=="詢問服事人員"){

            $strDutyType = $handlerLius->getEntity('職務類型');
            $strTime = $handlerLius->getEntity('時間');

            if(empty($strDutyType)){
                $strDutyType="";
            }
            if(empty($strTime)){
                $strTime="";
            }
            $bandServe = new BandServeController($strDutyType,$strTime);
            $replyMessage = $bandServe->getCondictionData();

            echo $replyMessage;

        }



        return $handlerLius->getTopScoringIntent().$handlerLius->getEntity('職務類型');


    }
}