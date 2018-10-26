<?php
/**
 * Created by PhpStorm.
 * User: andywu
 * Date: 2018/10/15
 * Time: 10:59 PM
 */



namespace App\IFace;

use LINE\LINEBot\MessageBuilder;

interface IMessageBuilder
{

    public function getMessageBuilder():MessageBuilder;

}