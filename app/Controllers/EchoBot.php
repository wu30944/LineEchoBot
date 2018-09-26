<?php
namespace App\Controllers;

use Log;

class EchoBot {
  private $userText;
  public function __construct($userText) {
    $this->userText = $userText;
    return $this;
  }

  //檢測正規式語法規則，或是簡單的if
  public function check(){
   if($this->userText!='') {
    return true;
   }
   return false;
  }
  //查詢Model
  public function 解釋(){
    //不符合回應的規則，吐空白
    if(!$this->check()) return "";
    //可以把$this->userText丟給model查詢。
    //吐回結果給使用者。
    //這裡吐回使用者傳進來的文字
    return $this->userText;
  }
}