<?php

namespace App\Http\Controllers;

use App\Doubandatas;
use App\Maoyandatas;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class DoubandatasController extends Controller
{
    //
    public function doubandatas()
    {
      // $handlerStack = HandlerStack::create(new CurlHandler());
      // $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      // for($i=150;$i<=225;$i++){
      //   $client = new Client([
      //        // Base URI is used with relative requests
      //        'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
      //        // You can set any number of default request options.
      //        'timeout'  => 2 ,
      //        'handler' => $handlerStack
      //        ]);
      //        $vod_search = 'vod-search';
      //        $response = $client->request('get', 'https://movie.douban.com/top250?start='.$i.'&filter=');
      //        $contents = (string)$response->getBody();
      //        $this->douban($contents);
      //        dump('douban----第'.$i.'页');
      //        $i=$i+24;
      //        sleep(1);
      //        return $contents;
      // }
      // for($i=150;$i<=150;$i++){
      //   $client = new Client([
      //        // Base URI is used with relative requests
      //        'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
      //        // You can set any number of default request options.
      //        'timeout'  => 2 ,
      //        'handler' => $handlerStack
      //        ]);
      //        $vod_search = 'vod-search';
      //        $response = $client->request('get', 'https://api.douban.com/v2/movie/top250?start='.$i.'&filter=');
      //        $contents = (string)$response->getBody();
      //        // $this->douban($contents);
      //        // dump('douban----第'.$i.'页');
      //        // $i=$i+24;
      //        sleep(1);
      //        return $contents->subjects;
      // }
      // header("Content-type:text/html;charset=utf-8");
      // $url = "https://api.douban.com/v2/movie/top250?start=0&count=5";
      // $ch = curl_init();
      // //设置参数
      // curl_setopt($ch, CURLOPT_URL, $url);
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      // $result = curl_exec($ch);
      // $result = json_decode($result,true);
      // // $result = $result['subjects'];
      // var_dump($result);


    $q = 1;
    for ($i=0; $i<=248; $i++) {

    $url = "https://api.douban.com/v2/movie/top250?start=".$i."&count=50";
    $ch = curl_init();
    //设置参数
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //调用接口

    $result = curl_exec($ch);
    $result = json_decode($result,true);
    $resultdata = $result['subjects'];
    // var_dump($resultdata[0]['casts']);
      for ($k=0; $k<=49; $k++) {
        $result = $resultdata[$k];

        // $genres = array_column($result, 'genres');//电影标签
        $genres = $result['genres'];//电影标签
        // $gen = $genres[0];
        $movieTag = "";
        foreach ($genres as $key => $value) {
             if ($movieTag == null) {
                        $movieTag .= $value;
             } else {
                        $movieTag .= "、".$value;
                    }
         }
         // var_dump($movieTag);
      //
         $movieTitle = $result['title'];//电影标题
         // $movieTitle = $title[0];
      //    $original = array_column($result, 'original_title');//原始标题
      //    $originTitle = $original[0];
        $average = $result['rating']['average'];
      //    $aver = array_column($result, 'rating');//电影评分
      //    $aver1 = array_column($aver, 'average');
      //    $average = $aver1[0];
         $casts = $result['casts'];//电影主角姓名，字符串
         $actorName = "";
         foreach ($casts as $key => $value) {

               $actorName .= $value['name'];
               $actorName .= ' ';
         }
      //
      // var_dump($average);
         $direc_name = $result['directors'][0]['name'];//导演姓名
      //    $direc = $director[0][0];
      // var_dump($director);
      //    $direc_name = $result['directors']['name'];
      // //
         // $year = array_column($result, 'year');//上映年份

         $movie_date = $result['year'];
      // //

         $images = $result['images']['medium'];//电影海报
      //    $images = $image[0]['medium'];
      // var_dump($images);
      //
      //    $id = array_column($result, 'id');//电影豆瓣id
      //    $movie_id = $id[0];
         $data['rank'] = $q;
         $data['title'] = $movieTitle;
         $data['rating'] = $average;
         $data['img'] = $images;
         $data['year'] = $movie_date;
         // $data['quto'] = 'zanwu';
         $data['casts'] = $actorName;
         $data['quto'] = $direc_name;
      //
      $q++;
         $data = Doubandatas::updateOrCreate(['title'=>$data['title']],['rating'=>$data['rating'],'rank'=>$data['rank'],'img'=>$data['img'],'year'=>$data['year'],'quto'=>$data['quto'],'casts'=>$data['casts']]);
         // var_dump($data);

      }
      $i= $i+49;
      dump('douban----第'.$i.'个');
      sleep(1);

     }
    }

    public function douban($douban)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($douban);
      $datas = $crawler->filterXPath('//*[@class="article"]/ol/li')->each(function (Crawler $node, $i) {
      $data['rank'] = $node->filterXPath('//div/div[1]/em')->text();
      $data['title'] = $node->filterXPath('//div[@class="item"]/div[@class="info"]/div[@class="hd"]/a/span[1]')->text();
      $data['rating'] = $node->filterXPath('//div/div[2]/div[2]/div/span[2]')->text();
      $data['img'] = $node->filterXPath('//div/div[1]/a/img/@src')->text();
      $data['year'] = $node->filterXPath('//div/div[2]/div[2]/p[1]/text()[2]')->text();
      $data['year'] = str_replace(' ','',$data['year']);
      $data['year'] = substr($data['year'] , 0 , 5);
      // $data['year'] = intval($data['year']);
      $data['quto'] = $node->filterXPath('//div/div[2]/div[2]/p[2]/span')->text();
      $data['casts'] = $node->filterXPath('//div/div[2]/div[2]/p[1]')->text();
      // $data['website'] = "15首发在线影院";

      $data = Doubandatas::updateOrCreate(['title'=>$data['title']],['rating'=>$data['rating'],'rank'=>$data['rank'],'img'=>$data['img'],'year'=>$data['year'],'quto'=>$data['quto'],'casts'=>$data['casts']]);
      return $data;
      // sleep(1);
      });
      return $datas;
      // return json_encode($ttdytts, 320);
    }

    public function maoyandatas()
    {
      $handlerStack = HandlerStack::create(new CurlHandler());
      $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));

      for($i=0;$i<=100;$i++){
        $client = new Client([
             // Base URI is used with relative requests
             'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
             // You can set any number of default request options.
             'timeout'  => 2 ,
             'handler' => $handlerStack
             ]);
             $response = $client->request('get', 'https://maoyan.com/board/4?offset='.$i);
             $contents = (string)$response->getBody();
             $this->maoyan($contents);
             dump('maoyan----第'.$i.'页');
             $i=$i+9;
             sleep(1);
      }
    }

    public function maoyan($maoyan)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($maoyan);
      $datas = $crawler->filterXPath('//*[@class="main"]/dl/dd')->each(function (Crawler $node, $i) {
      $data['rank'] = $node->filterXPath('//i')->text();
      $data['title'] = $node->filterXPath('//a/@title')->text();
      $data['rating'] = $node->filterXPath('//div/div/div[2]/p')->text();
      $data['img'] = 'http:';
      $data['img'] .= $node->filterXPath('//a/img[1]/@src')->text();
      $data['year'] = $node->filterXPath('//div/div/div[1]/p[3]')->text();
      $data['year'] = str_replace('上映时间：','',$data['year']);
      // $data['year'] = substr($data['year'] , 0 , 5);
      // $data['year'] = intval($data['year']);
      $data['quto'] = '暂无';
      $data['casts'] = $node->filterXPath('//div/div/div[1]/p[2]')->text();
      // $data['website'] = "15首发在线影院";

      $data = Maoyandatas::updateOrCreate(['title'=>$data['title']],['rating'=>$data['rating'],'rank'=>$data['rank'],'img'=>$data['img'],'year'=>$data['year'],'quto'=>$data['quto'],'casts'=>$data['casts']]);
      return $data;
      // sleep(1);
      });
      return $datas;
      // return json_encode($ttdytts, 320);
    }

    public function retryDecider()
   {
       return function (
           $retries,
           Request $request,
           Response $response = null,
           RequestException $exception = null
       ) {
           // Limit the number of retries to 5
           if ($retries >= 5) {
               return false;
           }

           // Retry connection exceptions
           if ($exception instanceof ConnectException) {
               return true;
           }

           if ($response) {
               // Retry on server errors
               if ($response->getStatusCode() >= 500 ) {
                   return true;
               }
           }

           return false;
       };
   }

   /**
    * delay 1s 2s 3s 4s 5s
    *
    * @return Closure
    */
   public function retryDelay()
   {
       return function ($numberOfRetries) {
           return 1000 * $numberOfRetries;
       };
   }

}
