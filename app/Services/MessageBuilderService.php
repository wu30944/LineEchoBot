<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/6
 * Time: 11:32 PM
 */

namespace App\Services;

use LINE\LINEBot\MessageBuilder;


class MessageBuilderService
{
    private $messageBuilder;

    public function __construct(MessageBuilder $messageBuilder=null)
    {
        $this->messageBuilder = $messageBuilder;
    }

    public function setMessageBuilder(MessageBuilder $messageBuilder){
        $this->messageBuilder = $messageBuilder;
    }

    public function getMessageBuilder(){
        if(is_null($this->messageBuilder))
            return null;
        else
             return $this->messageBuilder;
    }

}