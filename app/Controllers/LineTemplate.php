<?php
namespace App\Controllers;
use LINE\LINEBot;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;

class LineTemplate {
  public function __construct(){
    
  }  
  public function confirm($question_id="",$question="是否同意?"){
    /*
    //這個會直接顯示
    MessageTemplateActionBuilder('同意','y');
    */
    $messageBuilder = new TemplateMessageBuilder(
                        '問卷調查',
                        new ConfirmTemplateBuilder($question, [
                            new PostbackTemplateActionBuilder('是', $question_id.'|y'),
                            new PostbackTemplateActionBuilder('否', $question_id.'|n'),
                        ]));
    return $messageBuilder;
  }

}