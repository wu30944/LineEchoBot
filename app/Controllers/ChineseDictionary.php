<?php
namespace App\Controllers;
use App\Dictionary;
use App\Idiom;
use Log;

class ChineseDictionary {

  private $userText;
  private $rule;
  private $keyword;

  public function __construct($userText) {
    $this->userText = $userText;
    return $this;
  }
  
  public function check(){

  //==================解釋====================
  foreach(['查詢','查','什麼是','解釋'] as $keyword){
    if(preg_match("/$keyword(.+)/uim", $this->userText, $matches)){
      $this->userText = rtrim($matches[1],'?');
      $this->rule=1;
      return true;
    }
   }

    $subfix = ['是什麼$','是什麼意思'];
    foreach($subfix as $keyword){
      if(preg_match("/(.+)$keyword/uim", $this->userText, $matches)){
        $this->userText = rtrim($matches[1],'?');
        $this->rule=1;
        return true;
      }
    }
    //==================查部首====================
    $subfix = ['是什麼部首$','是什麼部$','什麼部','部首是什麼','的部首$','部首$','是什麼部首$'];
    foreach($subfix as $keyword){
      if(preg_match("/(.+)$keyword/uim", $this->userText, $matches)){
        $this->userText = rtrim($matches[1],'?');
        $this->rule=2;
        return true;
      }
    }
    //===================讀音========================
    $subfix = ['怎唸','怎念','怎讀','怎麼讀','怎麼念','怎麼唸','讀做什麼','讀音','注音'];
    foreach($subfix as $keyword){
      if(preg_match("/(.+)$keyword/uim", $this->userText, $matches)){
        $this->userText = rtrim($matches[1],'?');
        $this->rule=3;
        return true;
      }
    }
    //===================拼音=========================
    $subfix = ['怎麼拼','拼音','拼'];
    foreach($subfix as $keyword){
      if(preg_match("/(.+)$keyword/uim", $this->userText, $matches)){
        $this->userText = rtrim($matches[1],'?');
        $this->rule=4;
        return true;
      }
    }
    //===================筆劃=========================
    $subfix = ['有幾劃','幾劃'];
    foreach($subfix as $keyword){
      if(preg_match("/(.+)$keyword/uim", $this->userText, $matches)){
        $this->userText = rtrim($matches[1],'?');
        $this->rule=5;
        return true;
      }
    }
    //===================的成語=========================
    $subfix = ['的成語'];
    foreach($subfix as $keyword){
      if(preg_match("/(.+)$keyword/uim", $this->userText, $matches)){
        $this->userText = rtrim($matches[1],'?');
        $this->rule=7;
        return true;
      }
    }
    //==================成語判定=======================
    if(mb_strlen($this->userText,"UTF-8")==4) {
        Log::info("四個字的成語");
        $this->rule=6;
        return true;
    }

    $subfix = ['典故','出處','同義','反義','故事','用法','語義','說明'];
    foreach($subfix as $keyword){
        if(preg_match("/(.+)$keyword/uim", $this->userText, $matches)){
          $this->userText=rtrim($matches[1],'?');
          $this->rule=6;
          $this->keyword=$keyword;
          return true;
        }
    }
    //==============================================

    return false;
  }

  public function 解釋(){
    if(!$this->check()) return "";
    Log::info("ChineseDictionary Rule:".$this->rule);
    if($this->rule==1){
      try{
        $word = Dictionary::where('word',$this->userText)->get();
        if($word->count()==1){
          return "字典解釋\r\n".$word->first()->explanation;
        }
        $reply='';
        foreach($word as $item){
          $reply.="字典解釋:\r\n".$item->explanation;
        }
                  //return $word->explanation;
        return $reply;
      }catch(ModelNotFoundException $e){
        return "";
      }
    }elseif($this->rule==2){
      return $this->部首();
    }elseif($this->rule==3){
      return $this->注音讀音();
    }elseif($this->rule==4){
      return $this->拼音();
    }elseif($this->rule==5){
      return $this->筆劃();
    }elseif($this->rule==7){
      //一的成語
      return $this->什麼的成語();
    }elseif($this->rule==6){
      return $this->成語();
    }
     return "";
  }

 public function  成語(){

            switch($this->keyword){
            case '說明':
                try{
                    $answer = Idiom::where('word',$this->userText)->firstOrFail()->origin_explanation;
                    if($answer=='') return sprintf("這是個成語，但我不懂這個%s。",$this->keyword);
                    return $answer;
                }catch(ModelNotFoundException $e){
                    return "";
                }
                break;
            case '用法':
                try{
                    $answer = Idiom::where('word',$this->userText)->firstOrFail()->origin_explanation;
                    if($answer=='') return sprintf("這是個成語，但我不懂這個%s。",$this->keyword);
                    return $answer;
                }catch(ModelNotFoundException $e){
                    return "";
                }
                break;
            case '語義':
                try{
                    $answer = Idiom::where('word',$this->userText)->firstOrFail()->origin_explanation;
                    if($answer=='') return sprintf("這是個成語，但我不懂這個%s。",$this->keyword);
                    return $answer;
                }catch(ModelNotFoundException $e){
                    return ""; 
                }
                break;
            case '典故':
                try{
                    $answer = Idiom::where('word',$this->userText)->firstOrFail()->story;
                    if($answer=='') return sprintf("這是個成語，但我不懂這個%s。",$this->keyword);
                    return $answer;
                }catch(ModelNotFoundException $e){
                    return ""; 
                }
                break;
            case '故事':
                try{
                    return Idiom::where('word',$this->userText)->firstOrFail()->story_explanation;
                }catch(ModelNotFoundException $e){
                    return "";
                }
                break;
            case '出處':
                try{
                   return Idiom::where('word',$this->userText)->firstOrFail()->origin;
                }catch(ModelNotFoundException $e){
                    return "";
                }
                break;
            case '同義':
                try{
                    $answer = Idiom::where('word',$this->userText)->firstOrFail()->synonymous;
                    if($answer=='') return "我想不出同義字";
                    return $answer;
                }catch(ModelNotFoundException $e){
                    return "";
                }
                break;
            case '反義':
                try{
                  $answer = Idiom::where('word',$this->userText)->firstOrFail()->opposite;
                  if($answer=='') return sprintf("這是成語，不過應該沒有%s字。",$this->keyword);
                  return $answer;
                }catch(ModelNotFoundException $e){
                    return "";
                }
                break;
            default:
                    Log::info($this->userText);
                    $word = Idiom::where('word',$this->userText);
                    Log::info("找到:".$word->count());
                    if($word->count()!=0) {
                        return $word->firstOrFail()->origin_explanation;
                    }else{
                        return "";
                    }
            break;
            }
            return "";

 }

 public function 什麼的成語(){

  try{
    $answer = Idiom::where('word','like',"$this->userText%")->get();
    if($answer=='') return sprintf("這是個成語，但我不懂這個%s。",$this->keyword);
    $reply="";
    foreach($answer as $item){
      $reply.="$item->word\n";
    }
    return $reply;
  }catch(ModelNotFoundException $e){
    return "";
  }

}

  public function 注音讀音(){

  	try{
  		$first_word = mb_substr(rtrim($this->userText,"?"),0,1,"UTF-8");
  		$word = Dictionary::where('word',$first_word)->firstOrFail();

      $multitone=[$word->phonetic,$word->multitone];
      if(preg_match('/(二)|(三)/uim', $word->phonetic))
      {
        $multitone=[$word->multitone,$word->phonetic];
      }

  		return implode("\n",$multitone);
  	}catch(ModelNotFoundException $e){
  		return "";
  	}

  }

  public function 部首(){

  	if(mb_strlen($this->userText)>1){
  		return "你是要查那個字的部首?";
  	}
  	try{
  		$word = Dictionary::where('word',rtrim($this->userText,'?'))->firstOrFail();
  		return $word->radical;
  	}catch(ModelNotFoundException $e){
  		return "";
  	}

  }

  public function 拼音(){
  	try{
  		$word = Dictionary::where('word',rtrim($this->userText,'?'))->firstOrFail();
  		return $word->pinyin;
  	}catch(ModelNotFoundException $e){
  		return "";
  	}
  }

  public function 筆劃(){
  	try{
  		$first_word = mb_substr(rtrim($this->userText,"?"),0,1,"UTF-8");
  		$word = Dictionary::where('word',$first_word)->firstOrFail();
  		return "\"".$this->userText."\"有".$word->strokes."劃。";
  	}catch(ModelNotFoundException $e){
  		return "我不懂這個字的發音耶";
  	}
  }

  public function 人名(){

  	try{
  		$word = Dictionary::where('word',$this->userText)->firstOrFail();
  		return $word->explanation;
  	}catch(ModelNotFoundException $e){
  		return "我不認識他";
  	}

  }

}