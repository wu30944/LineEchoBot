<?php

namespace App\Controllers;

use App\Link;
use App\Memory;
use App\User;
use App\Dictionary; //字典
use App\Idiom; //成語
use App\Controllers\Memorize;
use Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Controllers\ChineseDictionary;
use App\Handler\LuisHandler;
use App\Controllers\BandServeController;


class BotBrainController extends Memorize
{

  public function handle($userText){

  //中文字典
  /*
  設計概念:
  一、傳入文字，由Controller決定傳入的文字是否需回應。
  二、透過一個check的method，檢測pattern格式，判斷是否需回應。
  三、需回應時，丟給相應的mehtods進行model的查詢，
  有查到就吐出回應結果的存文字，查不到回傳空白。
  */
  $answer = (new ChineseDictionary($userText))->解釋();
  if(''!=$answer) return ['text'=>$answer];


  //回傳貼圖
  //參考連結
  //https://developers.line.me/media/messaging-api/messages/sticker_list.pdf
  if($userText=="讚") {
    return ['sticker'=>["packageId"=>"1","stickerId"=>"14"]];
  }
  if($userText=="哈"||$userText=="哈哈"){
    return ['sticker'=>["packageId"=>"1","stickerId"=>"100"]];
  }
  if($userText=="?") {
    return ['sticker'=>["packageId"=>"2","stickerId"=>"149"]];
  }

  if($userText=="再見"||$userText=="bye bye"){
    return ['sticker'=>["packageId"=>"1","stickerId"=>"408"]];
  }

  //回地圖Sample
  //https://www.latlong.net/
  if($userText=="101") { 
    Log::info("回傳地址");
    return ['location'=>["title"=>"台北101","address"=>"台北市信義區信義路五段7號","latitude"=>"25.033964","longitude"=>"121.564472"]];
  }
  
 if($userText=="抽") {
   //需給https的圖檔連結
   $collection = collect([
    'https://mydomain.test/images/img1.png',
    'https://mydomain.test/images/img2.png',
    'https://mydomain.test/images/img3.png',
    'https://mydomain.test/images/img4.png',
    'https://mydomain.test/images/img5.png',
    'https://mydomain.test/images/img6.png',
    'https://mydomain.test/images/img7.png',
   ]);
   //隨機圖檔
   $image_url = $collection->random();
   $originalContentUrl=$image_url;
   $previewImageUrl=$image_url;
   return ['image'=>["originalContentUrl"=>$originalContentUrl,"previewImageUrl"=>$previewImageUrl]];

  } 

  $handlerLius = new LuisHandler();
  $luisResult=$handlerLius->getAnalyzeResult($userText);

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
    return  ['text'=>array(
        array(
            'type' => 'text',
            'text' => $userText.'讓我查一下…',
        ),
        array(
            'type' => 'text',
            'text' => '以下為'.$strTime.'服事人員 :'.$replyMessage,
        ))];

  }

  //只要有文字，就回傳相同的文字
  $answer = (new EchoBot($userText))->解釋();
  if(''!=$answer) return ['text'=>$answer];


  }



}