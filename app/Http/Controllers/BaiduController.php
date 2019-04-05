<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class BaiduController extends Controller
{
    //
    public function baidu_suggest(Request $request)
    {
      $wd = $request->input('wd');
      $client = new Client([
           // Base URI is used with relative requests
           'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
           // You can set any number of default request options.
           'timeout'  =>  1
           ]);
           $response = $client->request('get', 'http://suggestion.baidu.com/su?wd='.$wd.'&p="3"&cb="cb"');
           $contents = (string) $response->getBody();
           $contents = iconv("gb2312","utf-8",$contents);
           return $contents;
    }
}
