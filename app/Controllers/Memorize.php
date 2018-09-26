<?php
namespace App\Controllers;

use App\User;

class Memorize {

  private $user;
  private $noProfile=false;
  private $displayName;
  private $pictureUrl;
  private $userId;
  private $roomId;
  private $groupId;

  //建立使用者資料用

public function setNoProfile($userId)
{
  $this->noProfile=true;
}

public function setLineUserId($userId){
  $this->userId=$userId;
}

public function setGroupId($groupId){
    $this->groupId=$groupId;
}
public function setRoomId($roomId){
    $this->roomId=$roomId;
}

  public function replyText($answer){
    return ['text'=>$answer];
  }
}