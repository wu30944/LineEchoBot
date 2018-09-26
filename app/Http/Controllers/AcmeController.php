<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcmeController extends Controller
{
    public function __invoke($filename){
        $path = sprintf("%s/.well-known/acme-challenge/%s",public_path(),$filename);
        if(preg_match('/[A-Za-z0-9]/uim', $filename)){
            try{
                return file_get_contents($path);
            } catch (\Exception $e){
                throw new \Exception('無法取得檔案!!');
            }
        }
        return "";
    }
}
