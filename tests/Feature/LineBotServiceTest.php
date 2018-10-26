<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/9/25
 * Time: 8:10 PM
 */
namespace Tests\Feature\Services;

use App\Services\LineBotService;
use Tests\TestCase;

use Log;

use LINE\LINEBot\Event\UnknownEvent;

use LINE\LINEBot\Event\MessageEvent\UnknownMessage;
use LINE\LINEBot\Event\MessageEvent\BaseEvent;

use App\Handler\TextMessageEventHandler;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use App\Controllers\LineTemplate;
use App\Services\IntentActionService;
use App\Handler\LuisHandler;



class LineBotServiceTest extends TestCase
{
    /** @var  LineBotService */
    private $lineBotService;
    private $bot;
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->lineBotService = app(LineBotService::class);
        $this->bot = resolve('LINE\LINEBot');
    }

    public function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function testPushMessage()
    {

        $this->markTestSkipped('OK!');

        $json = file_get_contents('/Users/andywu/Documents/Code/LineEchoBot/tests/TestJson/TestMessage.json');
        $data = json_decode($json,true);
        $objTextMessage = new TextMessage($data);


        $handler = new TextMessageEventHandler($this->bot, '1', $objTextMessage);

        $strReplyText = $handler->handle();
//        Log::info($strReplyText);
//        $response = $this->lineBotService->pushMessage('Test from laravel.');

//        if (is_string($content)) {
            $content = new TextMessageBuilder($strReplyText);
//        }
        $response = $this->bot->pushMessage(env('LINEBOT_USER_ID'),$content);

        $this->assertEquals(200, $response->getHTTPStatus());

    }
    
    public function testPushButton(){
        $this->markTestSkipped('OK!');
        $response = $this->bot->pushMessage(env('LINEBOT_USER_ID'),new TemplateMessageBuilder(
                'alt test',
                new ButtonTemplateBuilder(
                    'button title',
                    'button button',
                    'https://example.com/thumbnail.jpg',
                    [
                        new PostbackTemplateActionBuilder('postback label', 'post=back'),
                        new MessageTemplateActionBuilder('message label', 'test message'),
                        new UriTemplateActionBuilder('uri label', 'https://example.com'),
                    ]
                )
            )
        );
        $this->assertEquals(200, $response->getHTTPStatus());
    }


    public function testPushConfirm(){

        $this->markTestSkipped('OK!');
        $json = file_get_contents('/Users/andywu/Documents/Code/LineEchoBot/tests/TestJson/TestMessage.json');
        $data = json_decode($json,true);
        $objTextMessage = new TextMessage($data);
        $handler = new TextMessageEventHandler($this->bot, '1', $objTextMessage);

        $strReplyText = $handler->handle();

//        $objConfirm = new  LineTemplate();

//            new ConfirmTemplateBuilder('test',
//            [new PostbackTemplateActionBuilder('postback label', 'post=back'),
//                new PostbackTemplateActionBuilder('postback label', 'post=back'),]
//        );

//        Log::info($objConfirm->buildTemplate());

//        $response = $this->bot->pushMessage(env('LINEBOT_USER_ID'),new TemplateMessageBuilder(
//            'alt test',
//            new ButtonTemplateBuilder(
//                '確認',
//                $strReplyText,
//                'https://example.com/thumbnail.jpg',
//                [
//                    new MessageTemplateActionBuilder('對', '請問本週吉他是誰'),
//                ]
//            )
//        ));
        $this->assertEquals(200, $strReplyText->getHTTPStatus());
    }

    public function testIntentActionService(){

        $json = file_get_contents('/Users/andywu/Documents/Code/LineEchoBot/tests/TestJson/TestMessage.json');
        $data = json_decode($json,true);
        $objTextMessage = new TextMessage($data);

        $handler = new TextMessageEventHandler($this->bot, '1', $objTextMessage);

        $strReplyText = $handler->handle();

        $this->assertEquals(200, $strReplyText->getHTTPStatus());

    }

}