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
        return view('welcome');
    }

    /**
     * jiebaProcess
     *
     * @return Response
     */
    public function jiebaProcess(Request $request)
    {

        ini_set('memory_limit', '600M');

        $paragraph = $request->input('paragraph');

        Jieba::init(array(
            'mode'=>'default',
            'dict'=>'samll'
        ));
        Finalseg::init();

        $seg_list = Jieba::cut($paragraph);
        $paragraph_processed = implode(' / ', $seg_list);

        return $paragraph_processed;

    }
}