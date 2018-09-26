<?php

use Illuminate\Http\Request;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('text', function(Request $request){

    $message=$request->message;
    $to=$request->to;//UserId or GroupId
    $bot = resolve('LINE\LINEBot');
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
    $resp=$bot->pushMessage($to, $textMessageBuilder);

    if($resp->isSucceeded()){
        return response("Succeeded","200");
    }

    $type='application/json';
    return response($resp->getRawBody(),"200")
        ->header('Content-Type', $type);;
});

Route::get('profile/{userId}', function(Request $request){

    $token = env('LINEBOT_CHANNEL_TOKEN');
      $http = new Client;
    
      $api_url=sprintf("https://api.line.me/v2/bot/profile/%s",$request->userId);
      $response = $http->request('GET', $api_url, [
	  'headers' => [
	      'Authorization' => 'Bearer '.$token,
	  ],
      ]);

      $response = json_decode((string) $response->getBody(), true);
      return $response;

});

Route::get('group/{groupId}/member/{userId}', function(Request $request){

    $token = env('LINEBOT_CHANNEL_TOKEN');
      $http = new Client;
      $api_url=sprintf("https://api.line.me/v2/bot/group/%s/member/%s", $request->groupId, $request->userId);
      $response = $http->request('GET', $api_url, [
	  'headers' => [
	      'Authorization' => 'Bearer '.$token,
	  ],
      ]);
      $response = json_decode((string) $response->getBody(), true);
      return $response;
});
