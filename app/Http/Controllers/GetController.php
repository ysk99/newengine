<?php

namespace App\Http\Controllers;

use App\Moviedatas;
use App\Downloaddatas;
// use Illuminate\Http\Request;
// use GuzzleHttp\Psr7\Request;
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


class GetController extends Controller
{
    //
    //15yc影院
    public function clewer_yc15()
    {
      $handlerStack = HandlerStack::create(new CurlHandler());
      $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      //共2062页
      for($i=1;$i<=2062;$i++){
        $client = new Client([
             // Base URI is used with relative requests
             'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
             // You can set any number of default request options.
             'timeout'  => 2 ,
             'handler' => $handlerStack
             ]);
             $vod_search = 'vod-search';
             $response = $client->request('get', 'http://www.15yc.cc/vodsearch/page/'.$i.'.html');
             $contents = (string) $response->getBody();
             $this->yc15($contents);
             dump('15yc影院 ----第'.$i.'页');
             sleep(1);
      }
    }

    public function yc15($yc15)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($yc15);
      $datas = $crawler->filterXPath('//*[@class="stui-vodlist clearfix"]/li')->each(function (Crawler $node, $i) {
      $data['title'] = $node->filterXPath('//a[1]/@title')->text();
      $data['website'] = "15首发在线影院";
      $data['others'] = $node->filterXPath('//a/span[2]')->text();
      $data['leixing'] = 'online';
      $data['recommend'] = 5;
      $data['href'] = 'http://www.15yc.cc/index.php';
      $data['href'] .= $node->filterXPath('//a[1]/@href')->text();
      $data = Moviedatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);

      return $data;
      // sleep(1);
      });
      return $datas;
      // return json_encode($ttdytts, 320);
    }

    //在线之家影院
    public function clewer_zxzj()
    {
      $handlerStack = HandlerStack::create(new CurlHandler());
      $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      //共156页
      for($i=1;$i<=156;$i++){
        $client = new Client([
             // Base URI is used with relative requests
             'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
             // You can set any number of default request options.
             'timeout'  =>  2 ,
             'handler' => $handlerStack
             ]);
             $vod_search = 'vod-search';
             $response = $client->request('get', 'https://www.zxzjs.com/vodsearch/----------'.$i.'---.html');
             $contents = (string) $response->getBody();
             $this->zxzj($contents);

             // return $contents;
             dump('在线之家 ----第'.$i.'页');
             // printf(stripslashes(json_encode($this->mtotoo_save($contents), 320)));
             sleep(1);
      }
    }

    public function zxzj($zxzj)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($zxzj);
      $zxzjs = $crawler->filterXPath('//*[@class="stui-vodlist clearfix"]/li')->each(function (Crawler $node, $i) {
      $zxzj['title'] = $node->filterXPath('//div[1]/a/@title')->text();
      $zxzj['website'] = "在线之家影院";
      // $data['website'] = "田鸡影院";
      $zxzj['others'] = $node->filterXPath('//div[1]/a')->text();
      // $data['leixing'] = 'online';
      $zxzj['leixing'] = 'online';
      $zxzj['recommend'] = 5;
      // $ttyyy['date'] = $node->filterXPath('//strong')->text();
      $zxzj['href'] = 'https://www.zxzjs.com';
      $zxzj['href'] .= $node->filterXPath('//div[1]/a/@href')->text();
      $zxzj = Moviedatas::updateOrCreate(['title'=>$zxzj['title'],'website'=>$zxzj['website']],['href'=>$zxzj['href'],'others'=>$zxzj['others'],'leixing'=>$zxzj['leixing'],'recommend'=>$zxzj['recommend']]);

      return $zxzj;
      // sleep(1);
      });
      return $zxzjs;
      // return json_encode($ttdytts, 320);
    }

    //田鸡影院
    public function clewer_mtotoo()
    {
      $handlerStack = HandlerStack::create(new CurlHandler());
      $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      for($i=1;$i<=541;$i++){
        $client = new Client([
             // Base URI is used with relative requests
             'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
             // You can set any number of default request options.
             'timeout'  => 5 ,
             'handler' => $handlerStack
             ]);
             $vod_search = 'vod-search';
             $response = $client->request('get', 'http://www.mtotoo.com/list-1-'.$i.'---0-0---.html');
             $contents = (string) $response->getBody();
             $this->mtotoo_save($contents);
             dump('田鸡影院第'.$i.'页');
             // printf(stripslashes(json_encode($this->mtotoo_save($contents), 320)));
             sleep(1);
      }
    }
    public function mtotoo_save($mtotoo)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($mtotoo);
      $mtotoos = $crawler->filterXPath('//*[@class="hy-video-list"]/div[1]/ul/li')->each(function (Crawler $node, $i) {
      $mtotoo['title'] = $node->filterXPath('//div[1]/h5/a')->text();
      $mtotoo['website'] = "田鸡影院";
      // $mtotoo['website'] = "田鸡影院";/div[2]
      $mtotoo['others'] = $node->filterXPath('//div[2]')->text();
      // $mtotoo['leixing'] = 'online';
      $mtotoo['leixing'] = 'online';
      $mtotoo['recommend'] = 5;
      // $ttyyy['date'] = $node->filterXPath('//strong')->text();
      $mtotoo['href'] = 'http://www.mtotoo.com';
      $mtotoo['href'] .= $node->filterXPath('//div[1]/h5/a/@href')->text();
      $mtotoo = Moviedatas::updateOrCreate(['title'=>$mtotoo['title'],'website'=>$mtotoo['website']],['href'=>$mtotoo['href'],'others'=>$mtotoo['others'],'leixing'=>$mtotoo['leixing'],'recommend'=>$mtotoo['recommend']]);
      return $mtotoo;
      // sleep(1);
      });
      return $mtotoos;
      // return json_encode($ttdytts, 320);
    }

    //天天云影院
    public function clewer_ttyyy()
    {
      $handlerStack = HandlerStack::create(new CurlHandler());
      $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      for($i=1;$i<=4;$i++){
        switch ($i)
          {
          case 1:
            $total_page = 135;
            break;
          case 2:
            $total_page = 90;
            break;
          case 3:
          $total_page = 16;
            break;
          case 4:
            $total_page = 4;
            break;
          default:
            $total_page = 1;
          }
        for($k=1;$k<=$total_page;$k++){
          if($k==1){
            $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                 // You can set any number of default request options.
                 'timeout'  =>  5 ,
                 'handler' => $handlerStack
                 ]);
                 $vod_search = 'vod-search';
                 // $keywd2312 = iconv("UTF-8","gbk//TRANSLIT",'老中医');
                 $response = $client->request('get', 'http://www.ttyyy.vip/f/'.$i.'.html');
                 // $content = $response->getBody()->getContents();
                 $contents = (string) $response->getBody();
                 $this->ttyyy($contents);
                 dump('天天云影院类型'.$i.'---第'.$k.'页--');
                 // printf(stripslashes(json_encode($this->ttyyy($contents), 320)));
                 sleep(1);
          }else{
            $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                 // You can set any number of default request options.
                 'timeout'  =>  5 ,
                 'handler' => $handlerStack
                 ]);
                 $vod_search = 'vod-search';
                 // $keywd2312 = iconv("UTF-8","gbk//TRANSLIT",'老中医');
             $response = $client->request('get', 'http://www.ttyyy.vip/f/'.$i.'_'.$k.'.html');
                 // $content = $response->getBody()->getContents();
                 $contents = (string) $response->getBody();
                 // $content = json_decode($content, true);
                 // $data = array_merge($this->mtotoo($contents));
                 // return stripslashes(json_encode($this->mtotoo_save($contents), 320));
                 // return $contents;
                 $this->ttyyy($contents);
                 dump('天天云影院类型'.$i.'---第'.$k.'页--');
                 // printf(stripslashes(json_encode($this->ttyyy($contents), 320)));
                 sleep(1);
          }
        }
      }
    }
    public function ttyyy($ttyyy)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($ttyyy);
      $ttyyys = $crawler->filterXPath('//div[@class="module-content"]/ul/li')->each(function (Crawler $node, $i) {
      $ttyyy['title'] = $node->filterXPath('//a/div[@class="text"]/p[1]')->text();
      $ttyyy['website'] = "天天云影院";
      $ttyyy['leixing'] = "online";
      $ttyyy['recommend'] = 5;
      $ttyyy['others'] = $node->filterXPath('//a/div[@class="text"]')->text();
      // $ttyyy['date'] = $node->filterXPath('//strong')->text();
      $ttyyy['href'] = 'http://www.ttyyy.vip';
      $ttyyy['href'] .= $node->filterXPath('//a/@href')->text();
      $ttyyy = Moviedatas::updateOrCreate(['title'=>$ttyyy['title'],'website'=>$ttyyy['website']],['href'=>$ttyyy['href'],'others'=>$ttyyy['others'],'leixing'=>$ttyyy['leixing'],'recommend'=>$ttyyy['recommend']]);
      // sleep(1);
      return $ttyyy;
      });
      return $ttyyys;
      // return json_encode($ttdytts, 320);
    }

    //逗别看影院
    public function clewer_doubiekan_get_total_page($leixing)
    {
      $client = new Client(['base_uri' => 'http://fkdy.me/index.php?m=vod-search','timeout'  => 2.0]);
      $vod_search = 'vod-search';
      $response = $client->request('get', 'http://www.doubiekan.org/'.$leixing.'/');
      $contents = (string) $response->getBody();
      $crawler = new Crawler($contents);
      // $article = [];
      $total_page = $crawler->filterXPath('//*[@id="long-page"]/ul/li/a[contains(text(),"尾页")]/@data')->text();
      // return $total_page;
      $total_page=str_replace('p-','',$total_page);
      return intval($total_page);
    }

    public function clewer_doubiekan()
    {
      $handlerStack = HandlerStack::create(new CurlHandler());
      $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      for($i=1;$i<=19;$i++){
        switch ($i)
          {
          case 1:
            $leixing = "guocanju";
            break;
          case 2:
            $leixing = "xianggangju";
            break;
          case 3:
            $leixing = "taiwanju";
            break;
          case 4:
            $leixing = "hanguoju";
            break;
          case 5:
            $leixing = "ribenju";
            break;
          case 6:
            $leixing = "oumeiju";
            break;
          case 7:
            $leixing = "taiguoju";
            break;
          case 8:
            $leixing = "haiwaiju";
            break;
          case 9:
            $leixing = "xijupian";
            break;
          case 10:
            $leixing = "dongzuopian";
            break;
          case 11:
            $leixing = "kehuanpian";
            break;
          case 11:
            $leixing = "kongbupian";
            break;
          case 12:
            $leixing = "aiqingpian";
            break;
          case 13:
            $leixing = "zhanzhengpian";
            break;
          case 14:
            $leixing = "juqingpian";
            break;
          case 15:
            $leixing = "jilupian";
            break;
          case 16:
            $leixing = "donghuapian";
            break;
          case 17:
            $leixing = "dongman";
            break;
          case 18:
            $leixing = "zongyi";
            break;
          case 19:
            $leixing = "weidianying";
            break;
          default:
            $leixing = 1;
          }
          // 获取每个类型下的页面总数
        $total_page = $this->clewer_doubiekan_get_total_page($leixing);
        // return $total_page;
        for($k=1;$k<=$total_page;$k++){
            $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                 // You can set any number of default request options.
                 'timeout'  =>  2 ,
                 'handler' => $handlerStack
                 ]);
                 $vod_search = 'vod-search';
                 $response = $client->request('get', 'http://www.doubiekan.org/'.$leixing.'/index-'.$k.'.html');
                 $contents = (string) $response->getBody();
                 $this->doubiekan($contents);
                 // stripslashes(json_encode($this->doubiekan($contents), 320));
                 dump('逗别看影院'.$leixing.'已经爬取完第'.$k.'页---');
                 sleep(1);

        }
      }
    }
    public function doubiekan($doubiekan)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($doubiekan);
      $doubiekans = $crawler->filterXPath('//ul[@id="content"]/li')->each(function (Crawler $node, $i) {
      $doubiekan['title'] = $node->filterXPath('//div[@class="title"]/h5/a')->text();
      $doubiekan['website'] = "6080fw逗别看影院";
      $doubiekan['leixing'] = "online";
      $doubiekan['recommend'] = 5;
      $doubiekan['others'] = $node->filterXPath('//*')->text();
      $doubiekan['href'] = 'http://www.doubiekan.org';
      $doubiekan['href'] .= $node->filterXPath('//div[@class="title"]/h5/a/@href')->text();
      $doubiekan = Moviedatas::updateOrCreate(['title'=>$doubiekan['title'],'website'=>$doubiekan['website']],['href'=>$doubiekan['href'],'others'=>$doubiekan['others'],'leixing'=>$doubiekan['leixing'],'recommend'=>$doubiekan['recommend']]);
      // sleep(1);
      return $doubiekan;
      });
      return $doubiekans;
      // return json_encode($doubiekans, 320);
    }

    //88影视在线
    public function clewer_ys88()
    {
      $handlerStack = HandlerStack::create(new CurlHandler());
      $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      for($i=1;$i<=4;$i++){
        switch ($i)
          {
          case 1:
            $total_page = 682;//682
            $leixing = "dianying";
            break;
          case 2:
            $total_page = 386;
            $leixing = "lianxuju";
            break;
          case 3:
          $total_page = 241;
          $leixing = "zongyi";
            break;
          case 4:
            $total_page = 280;
            $leixing = "dongman";
            break;
          default:
            $total_page = 1;
            $leixing = "none";
          }
        for($k=1;$k<=$total_page;$k++){
          if($k==1){
            $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                 // You can set any number of default request options.
                 'timeout'  =>  2 ,
                 'handler' => $handlerStack
                 ]);
                 // $keywd2312 = iconv("UTF-8","gbk//TRANSLIT",'老中医');
                 $response = $client->request('get', 'https://www.88ys.cn/'.$leixing.'/'.$i.'.html');
                 $contents = (string) $response->getBody();
                 // printf(stripslashes(json_encode($this->ttyyy($contents), 320)));
                 $this->ys88($contents);
                 echo('88影视在线'.$leixing.'第'.$k.'页');
                 sleep(1);
          }else{
            $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                 // You can set any number of default request options.
                 'timeout'  =>  2 ,
                 'handler' => $handlerStack
                 ]);
                 $vod_search = 'vod-search';
                 // $keywd2312 = iconv("UTF-8","gbk//TRANSLIT",'老中医');
             $response = $client->request('get', 'https://www.88ys.cn/'.$leixing.'/'.$i.'-'.$k.'.html');
                 $contents = (string) $response->getBody();
                 // printf(stripslashes(json_encode($this->ys88($contents), 320)));
                 $this->ys88($contents);
                 dump('88影视在线'.$leixing.'第'.$k.'页');
                 sleep(1);
          }
        }
      }
    }
    public function ys88($ys88)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($ys88);
      $datas = $crawler->filterXPath('//div[@class="index-area clearfix"]/ul/li')->each(function (Crawler $node, $i) {
      $data['title'] = $node->filterXPath('//a[1]/@title')->text();
      $data['website'] = "88影视";
      $data['leixing'] = "online";
      $data['recommend'] = 5;
      $data['others'] = $node->filterXPath('//a/span/p[3]')->text();
      $data['others'] .= $node->filterXPath('//a/span/p[4]')->text();
      // $data['date'] = $node->filterXPath('//strong')->text();
      $data['href'] = 'https://www.88ys.cn';
      $data['href'] .= $node->filterXPath('//a[1]/@href')->text();
      $data = Moviedatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
      // sleep(1);
      return $data;
      });
      return $datas;
      // return json_encode($ttdytts, 320);
    }

    //天天云影院
    public function clewer_yunbtv()
    {
      $handlerStack = HandlerStack::create(new CurlHandler());
      $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
      for($i=1;$i<=4;$i++){
        // switch ($i)
        //   {
        //   case 1:
        //     $total_page = 135;
        //     break;
        //   case 2:
        //     $total_page = 90;
        //     break;
        //   case 3:
        //   $total_page = 16;
        //     break;
        //   case 4:
        //     $total_page = 4;
        //     break;
        //   default:
        //     $total_page = 1;
        //   }
        $total_page = $this->clewer_yunbtv_get_total_page($i);
        // return $total_page;
        for($k=1;$k<=$total_page;$k++){
            $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                 // You can set any number of default request options.
                 'timeout'  =>  5 ,
                 'handler' => $handlerStack
                 ]);
                 $vod_search = 'vod-search';
                 // $keywd2312 = iconv("UTF-8","gbk//TRANSLIT",'老中医');
                 $response = $client->request('get', 'https://www.yunbtv.com/category/'.$i.'-'.$k.'.html');
                 // $content = $response->getBody()->getContents();
                 $contents = (string) $response->getBody();
                 $this->yunbtv($contents);
                 dump('云播影院类型'.$i.'---第'.$k.'页--');
                 // printf(stripslashes(json_encode($this->ttyyy($contents), 320)));
                 sleep(1);

        }
      }
    }
    public function yunbtv($yunbtv)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($yunbtv);
      $datas = $crawler->filterXPath('//div[@class="cards video-list"]/div[@class="col-md-2 col-xs-4"]')->each(function (Crawler $node, $i) {
      $data['title'] = $node->filterXPath('//div/div[3]/strong/a')->text();
      $data['website'] = "云播影院";
      $data['leixing'] = "online";
      $data['recommend'] = 5;
      $data['others'] = $node->filterXPath('//div')->text();
      $data['others']=str_replace(' ','',$data['others']);
      // $data['date'] = $node->filterXPath('//strong')->text();
      $data['href'] = 'https://www.yunbtv.com';
      $data['href'] .= $node->filterXPath('//div/div[1]/a/@href')->text();
      $data = Moviedatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
      // sleep(1);
      return $data;
      });
      return $datas;
      // return json_encode($ttdytts, 320);
    }
    public function clewer_yunbtv_get_total_page($i)
   {
     $client = new Client(['base_uri' => 'http://fkdy.me/index.php?m=vod-search','timeout'  => 5.0]);
     $vod_search = 'vod-search';
     $response = $client->request('get', 'https://www.yunbtv.com/category/'.$i.'.html');
     $contents = (string) $response->getBody();
     $crawler = new Crawler($contents);
     // $article = [];
     $total_page = $crawler->filterXPath('//div[@class="panel-body pb-0"]/ul/li/a[contains(text(),"尾页")]/@href')->text();
     $total_page = strstr($total_page, "-");
     // return $total_page;
     // $total_page=str_replace('https://www.66s.cc/dongzuopian/index_','',$total_page);
     $total_page=str_replace('-','',$total_page);
     $total_page=str_replace('.html','',$total_page);
     // $total_page=str_replace('_','',$total_page);
     return intval($total_page);
     return $total_page;
   }

   //bttwo影院
   public function clewer_bttwo_get_total_page($leixing)
   {
     $client = new Client(['base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                          'timeout'  => 2.0,
                          'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"]
                        ]);
     $vod_search = 'vod-search';
     $response = $client->request('get', 'https://www.bttwo.com/'.$leixing.'/page/1');
     $contents = (string) $response->getBody();
     $crawler = new Crawler($contents);
     // $article = [];
     $total_page = $crawler->filterXPath('//*[@class="pagenavi_txt"]/a[last()]/@href')->text();
     // return $total_page;
     $total_page=str_replace('https://www.bttwo.com/'.$leixing.'/page/','',$total_page);
     return intval($total_page);
     // return $contents;
   }

   public function clewer_bttwo()
   {
     $handlerStack = HandlerStack::create(new CurlHandler());
     $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
     for($i=1;$i<=2;$i++){
       switch ($i)
         {
         case 1:
           $leixing = "movie_bt";
           break;
         case 2:
           $leixing = "dsj";
           break;
         default:
           $leixing = 1;
         }
         // 获取每个类型下的页面总数
       $total_page = $this->clewer_bttwo_get_total_page($leixing);
       // return $total_page;
       for($k=1;$k<=$total_page;$k++){
           $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                // You can set any number of default request options.
                'timeout'  =>  2 ,
                'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                'handler' => $handlerStack
                ]);
                $vod_search = 'vod-search';
                $response = $client->request('get', 'https://www.bttwo.com/'.$leixing.'/page/'.$k);
                $contents = (string) $response->getBody();
                $this->bttwo($contents);
                // stripslashes(json_encode($this->doubiekan($contents), 320));
                dump('BTtwo影院'.$leixing.'已经爬取完第'.$k.'页---');
                sleep(1);

       }
     }
   }
   public function bttwo($bttwo)
   {
     $crawler = new Crawler();
     $crawler->addHtmlContent($bttwo);
     $doubiekans = $crawler->filterXPath('//*[@class="bt_img mi_ne_kd mrb"]/ul/li')->each(function (Crawler $node, $i) {
     $doubiekan['title'] = $node->filterXPath('//h3')->text();
     $doubiekan['website'] = "BTtwo影院";
     $doubiekan['leixing'] = "online";
     $doubiekan['recommend'] = 5;
     $doubiekan['a'] = $node->filterXPath('//div[@class="hdinfo"]')->text();
     $doubiekan['others'] = $node->filterXPath('//p')->text();
     // $doubiekan['href'] = 'http://www.doubiekan.org';
     $doubiekan['href'] = $node->filterXPath('//h3/a/@href')->text();
     if(strpos($doubiekan['a'],'在线观看')){
       $doubiekantodown = Moviedatas::updateOrCreate(['title'=>$doubiekan['title'],'website'=>$doubiekan['website']],['href'=>$doubiekan['href'],'others'=>$doubiekan['others'],'leixing'=>$doubiekan['leixing'],'recommend'=>$doubiekan['recommend']]);
     }
     if(strpos($doubiekan['a'],'720p')||strpos($doubiekan['a'],'1080p')||strpos($doubiekan['a'],'HD')){
       $doubiekantodown= Downloaddatas::updateOrCreate(['title'=>$doubiekan['title'],'website'=>$doubiekan['website']],['href'=>$doubiekan['href'],'others'=>$doubiekan['others'],'leixing'=>$doubiekan['leixing'],'recommend'=>$doubiekan['recommend']]);
     }
     // sleep(1);
     return $doubiekan;
     });
     return $doubiekans;
     // return json_encode($doubiekans, 320);
   }

   public function clewer_gimy_get_total_page($leixing)
   {
     $handlerStack = HandlerStack::create(new CurlHandler());
     $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
     $client = new Client(['base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                          'timeout'  => 2.0,
                          'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                          'handler' => $handlerStack
                        ]);
     $response = $client->request('get', 'https://v.gimy.tv/list/'.$leixing.'-----hits_week-1.html');
     $contents = (string) $response->getBody();
     $crawler = new Crawler($contents);
     // $article = [];
     $total_page = $crawler->filterXPath('//*[@class="box-page clearfix ajax-page"]/ul/li[last()]/a/@data')->text();
     // return $total_page;
     $total_page=str_replace('p-','',$total_page);
     return intval($total_page);
   }
   public function clewer_gimy()
   {
     $handlerStack = HandlerStack::create(new CurlHandler());
     $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
     for($i=1;$i<=4;$i++){
       switch ($i)
         {
         case 1:
           $leixing = "movies";
           break;
         case 2:
           $leixing = "drama";
           break;
           case 3:
           $leixing = "anime";
           break;
           case 4:
           $leixing = "tvshow";
           break;
         default:
           $leixing = 1;
         }
         // 获取每个类型下的页面总数
       $total_page = $this->clewer_gimy_get_total_page($leixing);
      //  return $total_page;
       for($k=1;$k<=$total_page;$k++){
           $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                // You can set any number of default request options.
                'timeout'  =>  2 ,
                'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                'handler' => $handlerStack
                ]);
                $response = $client->request('get', 'https://v.gimy.tv/list/'.$leixing.'-----hits_week-'.$k.'.html');
                $contents = (string) $response->getBody();
                $this->gimy($contents);
                // return $contents;
                // $crawler = new Crawler();
                // $crawler->addHtmlContent($contents);
                // $datas = $crawler->filterXPath('//*[@class="box-video-list"]/div/ul/li[2]/div[1]/h5/a')->text();
                // return $datas;

                dump('gimy影院'.$leixing.'已经爬取完第'.$k.'页---');
                sleep(1);

       }
     }
   }
   public function gimy($gimy)
   {
     $crawler = new Crawler();
     $crawler->addHtmlContent($gimy);
     $datas = $crawler->filterXPath('//*[@class="box-video-list"]/div/ul/li')->each(function (Crawler $node, $i) {
     $data['title'] = $node->filterXPath('//div[1]/h5/a')->text();
     $data['website'] = "gimy影院";
     $data['leixing'] = "online";
     $data['recommend'] = 6;
     $data['others'] = $node->filterXPath('//a[1]')->text();
     $data['others'] .= $node->filterXPath('//div[2]')->text();
     // $data['href'] = 'http://www.doubiekan.org';
     $data['href'] = $node->filterXPath('//div[1]/h5/a/@href')->text();
     $data = Moviedatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
     return $data;
     });
     return $datas;
   }

   public function clewer_haitu()
   {
     $handlerStack = HandlerStack::create(new CurlHandler());
     $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
     for($i=1;$i<=7;$i++){
       switch ($i)
         {
          case 1:
            $leixing = "dianying";
            break;
          case 2:
            $leixing = "dianshiju";
            break;
          case 3:
            $leixing = "zongyi";
            break;
          case 4:
            $leixing = "dongman";
            break;
          case 5:
            $leixing = "jilupian";
            break;
          case 6:
            $leixing = "weidianying";
            break;
          case 7:
            $leixing = "lunlipian";
            break;
         default:
           $leixing = 1;
         }
         // 获取每个类型下的页面总数
       $total_page = $this->clewer_haitu_get_total_page($leixing);
       // return $total_page;
       for($k=1;$k<=$total_page;$k++){
           $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                // You can set any number of default request options.
                'timeout'  =>  3.14 ,
                'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36","Cookie"=>"Hm_lvt_d91bd9f22a0064c73796c0de6831474b=1554773228; PHPSESSID=bq22hd8ttkmf3jpfu5u0bhart1; Hm_lpvt_d91bd9f22a0064c73796c0de6831474b=1554778277","Accept"=>"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8"],
                'handler' => $handlerStack
                ]);
                $response = $client->request('get', 'http://www.haitum.com/'.$leixing.'/page/'.$k);
                $contents = (string) $response->getBody();
                $this->haitu($contents);
                // return $contents;
                // $crawler = new Crawler($contents);
                // $article = [];
                // $total_page = $crawler->filterXPath('//*[@class="content"]/ul/li[1]/a/@title')->text();
                // stripslashes(json_encode($this->doubiekan($contents), 320));
                dump('海兔影院'.$leixing.'已经爬取完第'.$k.'页---');
                sleep(1);

       }
     }
   }
   public function haitu($bttwo)
   {
     $crawler = new Crawler();
     $crawler->addHtmlContent($bttwo);
     $doubiekans = $crawler->filterXPath('//*[@class="new-hot-movie"]/div[2]/ul/li')->each(function (Crawler $node, $i) {
     $doubiekan['title'] = $node->filterXPath('//a/@title')->text();
     $doubiekan['website'] = "海兔影院";
     $doubiekan['leixing'] = "online";
     $doubiekan['recommend'] = 6;
     $doubiekan['others'] = $node->filterXPath('//a')->text();
     $doubiekan['href'] = 'http://www.haitum.com';
     $doubiekan['href'] .= $node->filterXPath('//a/@href')->text();
     $doubiekan = Moviedatas::updateOrCreate(['title'=>$doubiekan['title'],'website'=>$doubiekan['website']],['href'=>$doubiekan['href'],'others'=>$doubiekan['others'],'leixing'=>$doubiekan['leixing'],'recommend'=>$doubiekan['recommend']]);
     return $doubiekan;
     });
     return $doubiekans;
   }
   public function clewer_haitu_get_total_page($leixing)
   {
     $client = new Client(['base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                          'timeout'  => 0,
                          'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"]
                        ]);
     $vod_search = 'vod-search';
     $response = $client->request('get', 'http://www.haitum.com/'.$leixing.'/page/2');
     $contents = (string) $response->getBody();
     $crawler = new Crawler($contents);
     // $article = [];
     $total_page = $crawler->filterXPath('//*[@class="page-num count-right"]/div[2]/a[last()]/@href')->text();
     // return $total_page;
     $total_page=str_replace('/'.$leixing.'/page/','',$total_page);
     return intval($total_page);
     // return $contents;
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
