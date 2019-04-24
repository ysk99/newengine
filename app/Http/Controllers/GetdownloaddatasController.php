<?php

namespace App\Http\Controllers;

use App\Moviedatas;
use App\Downloaddatas;
// use Illuminate\Http\Request;
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

class GetdownloaddatasController extends Controller
{
    //音范思下载网站

        public function clewer_yinfansi_bt_totalpage()
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
          $client = new Client(['base_uri' => 'http://www.yinfans.me/page/1','timeout'  => 2.0,'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],'handler' => $handlerStack]);
          $vod_search = '1';
          $response = $client->request('get', 'http://www.yinfans.me/page/'.$vod_search);
          $contents = (string) $response->getBody();
          $crawler = new Crawler($contents);
          $total_page = $crawler->filterXPath('//*[@class="pagination"]/a[contains(text(),"尾页")]/@href')->text();
          $total_page=str_replace('https://www.yinfans.me/page/','',$total_page);
          return intval($total_page);
        }

        public function yinfansi($yinfansi)
        {
          $crawler = new Crawler();
          $crawler->addHtmlContent($yinfansi);
          $datas = $crawler->filterXPath('//*[@id="post_container"]/li')->each(function (Crawler $node, $i) {
          $data['title'] = $node->filterXPath('//div[@class="thumbnail"]/a/@title')->text();
          $data['website'] = "音范丝高清资源下载";
          $data['leixing'] = "download";
          $data['recommend'] = 5;
          $data['others'] = $node->filterXPath('//div[@class="info"]')->text();
          $data['others']=str_replace(' ','',$data['others']);
          // $data['date'] = $node->filterXPath('//strong')->text();
          // $data['href'] = 'http://www.ttyyy.vip';
          $data['href'] = $node->filterXPath('//div[@class="thumbnail"]/a/@href')->text();

          $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
          // sleep(1);
          return $data;
          });
          return $datas;
          // return json_encode($ttdytts, 320);
        }

        public function clewer_yinfansi()
        {
          // total_page =
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
            $total_page = $this->clewer_yinfansi_bt_totalpage();
          for($i=1;$i<=$total_page;$i++){
            $client = new Client([
                 // Base URI is used with relative requests
                 'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                 // You can set any number of default request options.
                 'timeout'  => 2,
                 'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                 'handler' => $handlerStack
                 ]);
                 // $vod_search = 'vod-search';
                 $response = $client->request('get', 'http://www.yinfans.me/page/'.$i);
                 $contents = (string) $response->getBody();
                 $this->yinfansi($contents);
                 // dump($contents);
                 dump('音范丝第'.$i.'页');
                 // printf(stripslashes(json_encode($this->mtotoo_save($contents), 320)));
                 sleep(1);
          }
        }

        // 电影天堂 失败

        public function dytt($dytt)
        {
          $crawler = new Crawler();
          $crawler->addHtmlContent($dytt,'utf-8');
          $datas = $crawler->filterXPath('//div[@class="co_content8"]/ul/td/table')->each(function (Crawler $node, $i) {
          $data['title'] = $node->filterXPath('//tr[2]/td[2]/b/a')->text();
          $data['website'] = "电影天堂下载";
          $data['leixing'] = "download";
          $data['recommend'] = 5;
          $data['others'] = $node->filterXPath('//tr[2]/td[2]/b/a')->text();
          $data['others']=str_replace(' ','',$data['others']);
          $data['href'] = 'http://www.dy2018.com';
          $data['href'] .= $node->filterXPath('//tr[2]/td[2]/b/a/@href')->text();
          $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
          // sleep(1);
          return $data;
          });
          return $datas;
          // return json_encode($ttdytts, 320);
        }

        public function clewer_dytt_totalpage()
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
          $client = new Client(['base_uri' => 'https://www.dy2018.com/html/gndy/dyzz/index_2.html','timeout'  => 2.0,'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],'handler' => $handlerStack]);
          $response = $client->request('get', 'https://www.dy2018.com/html/gndy/dyzz/index_2.html');
          $contents = (string) $response->getBody();
          $contents = iconv("gb2312","utf-8//IGNORE",$contents);
          $crawler = new Crawler();
          $crawler->addHtmlContent($contents,'utf-8');
          $total_page = $crawler->filterXPath('//div[@class="co_content8"]/div[@class="x"]/a[4]/@href')->text();
          $total_page = str_replace('/html/gndy/dyzz/index_','',$total_page);
          $total_page = str_replace('.html','',$total_page);
          return intval($total_page);
          // return $total_page;
        }

        public function clewer_dytt()
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));

          // total_page =
            $total_page = $this->clewer_dytt_totalpage();
            // return $total_page;
          for($i=1;$i<=$total_page;$i++){
            if($i==1){

              $client = new Client([
                   // Base URI is used with relative requests
                   'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                   // You can set any number of default request options.
                   'timeout'  => 2,
                   'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                   'handler' => $handlerStack
                   ]);
                   // $vod_search = 'vod-search';
                   $response = $client->request('get', 'https://www.dy2018.com/html/gndy/dyzz/index.html');
                   $contents = (string)$response->getBody();
                   $contents = iconv("gb2312","utf-8//IGNORE",$contents);
                   // $this->dytt($contents);
                   // dump();
                   $this->dytt($contents);
                   dump('电影天堂第'.$i.'页---');

                   // dump($datas);
                   sleep(1);
            }else {
              $client = new Client([
                   // Base URI is used with relative requests
                   'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                   // You can set any number of default request options.
                   'timeout'  => 2,
                   'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                   'handler' => $handlerStack
                   ]);
                   // $vod_search = 'vod-search';
                   $response = $client->request('get', 'https://www.dy2018.com/html/gndy/dyzz/index_'.$i.'.html');
                   $contents = (string)$response->getBody();
                   $contents = iconv("gb2312","utf-8//IGNORE",$contents);
                   // $this->dytt($contents);
                   // dump();
                   $this->dytt($contents);
                   dump('电影天堂第'.$i.'页---');

                   // dump($datas);
                   sleep(1);
            }
          }
        }



        public function clewer_s66_get_total_page($leixing)
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
          $client = new Client(['base_uri' => 'https://www.yinfans.com/page/1','timeout'  => 2.0,'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],'handler' => $handlerStack]);
          $vod_search = 'vod-search';
          $response = $client->request('get', 'https://www.66s.cc/'.$leixing.'/');
          $contents = (string) $response->getBody();
          $crawler = new Crawler($contents);
          // $article = [];
          $total_page = $crawler->filterXPath('//div[@class="pagination"]/a[contains(text(),"尾页")]/@href')->text();
          $total_page = strstr($total_page, "_");
          // return $total_page;
          // $total_page=str_replace('https://www.66s.cc/dongzuopian/index_','',$total_page);
          $total_page=str_replace('.html','',$total_page);
          $total_page=str_replace('_','',$total_page);
          return intval($total_page);
          return $total_page;
        }

        public function clewer_s66()
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
          for($i=2;$i<=2;$i++){
            switch ($i)
              {
              case 1:
                $leixing = "xijupian";
                break;
              case 2:
                $leixing = "dongzuopian";
                break;
              case 3:
                $leixing = "aiqingpian";
                break;
              case 4:
                $leixing = "kehuanpian";
                break;
              case 5:
                $leixing = "kongbupian";
                break;
              case 6:
                $leixing = "juqingpian";
                break;
              case 7:
                $leixing = "zhanzhengpian";
                break;
              case 8:
                $leixing = "jilupian";
                break;
              case 9:
                $leixing = "donghuapian";
                break;
              case 10:
                $leixing = "fuli";
                break;
              case 11:
                $leixing = "dianshiju";
                break;
              case 12:
                $leixing = "ZongYi";
                break;
              default:
                $leixing = 1;
              }
              // 获取每个类型下的页面总数
            $total_page = $this->clewer_s66_get_total_page($leixing);
            // return $total_page;
            for($k=1;$k<=$total_page;$k++){
                if($k==1){
                  $client = new Client([
                       // Base URI is used with relative requests
                       'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                       // You can set any number of default request options.
                       'timeout'  => 2,
                       'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                       'handler' => $handlerStack
                       ]);
                       $vod_search = 'vod-search';
                       $response = $client->request('get', 'https://www.66s.cc/'.$leixing.'/index.html');
                       $contents = (string) $response->getBody();
                       $this->s66($contents);
                       // stripslashes(json_encode($this->doubiekan($contents), 320));
                       dump('66V'.$leixing.'已经爬取完第'.$k.'页---');
                       sleep(1);
                }else {
                  // code...
                  $client = new Client([
                       // Base URI is used with relative requests
                       'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                       // You can set any number of default request options.
                       'timeout'  => 2,
                       'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                       'handler' => $handlerStack
                       ]);
                       $vod_search = 'vod-search';
                       $response = $client->request('get', 'https://www.66s.cc/'.$leixing.'/index_'.$k.'.html');
                       $contents = (string) $response->getBody();
                       $this->s66($contents);
                       // stripslashes(json_encode($this->doubiekan($contents), 320));
                       dump('66V'.$leixing.'已经爬取完第'.$k.'页---');
                       sleep(1);
                }
            }
          }
        }
        public function s66($s66)
        {
          $crawler = new Crawler();
          $crawler->addHtmlContent($s66);
          $datas = $crawler->filterXPath('//ul[@id="post_container"]/li')->each(function (Crawler $node, $i) {
          $data['title'] = $node->filterXPath('//div[@class="thumbnail"]/a/@title')->text();
          $data['website'] = "6V电影（在线+下载）";
          $data['leixing'] = "download";
          $data['recommend'] = 5;
          $data['others'] = $node->filterXPath('//div[@class="article"]/div[@class="entry_post"]')->text();
          $data['others']=str_replace(' ','',$data['others']);
          // $data['href'] = 'http://www.doubiekan.org';
          $data['href'] = $node->filterXPath('//div[@class="thumbnail"]/a/@href')->text();
          $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
          $data = Moviedatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
          // sleep(1);
          return $data;
          });
          return $datas;
          // return json_encode($datas, 320);
        }

        public function ceshi($ceshi)
        {
          $crawler = new Crawler();
          $crawler->addHtmlContent($ceshi);
          // $datas = $crawler->filterXPath('//*[@class="container"][2]/div[1]/div[2]/div')->
          // $data['title'] = $crawler->filter('#header > div > div.bd2 > div.bd3 > div.bd3r > div.co_area2 > div.co_content8 > ul > table:nth-child(1) > tbody > tr:nth-child(2)')->text();
          $data['website'] = "迅影网下载";
          $data['leixing'] = "download";
          $data['recommend'] = 5;
          // $data['others'] = $crawler->filterXPath('//*[@id="header"]/div/div[3]/div[3]/div[2]/div[2]/div[2]/ul/table[1]/tbody/tr[2]/td[2]')->text();
          // $data['date'] = $node->filterXPath('//strong')->text();
          // $data['href'] = 'http://www.ttyyy.vip';
          $data['href'] = $crawler->filterXPath('//*[@id="header"]/div/div[3]/div[3]/div[2]/div[2]/div[2]/ul/table[1]/tbody/tr[1]')->text();

          // $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
          // sleep(1);
          return $data;

          // return json_encode($ttdytts, 320);
        }

        public function xunying($xunying)
        {
          $crawler = new Crawler();
          $crawler->addHtmlContent($xunying);
          $datas = $crawler->filterXPath('//html/body/div[2]/div[1]/div[2]/div[@class="col-xs-1-5 movie-item"]')->each(function (Crawler $node, $i) {
          $data['title'] = $node->filterXPath('//div/a/@title')->text();
          $data['website'] = "迅影网下载";
          $data['leixing'] = "download";
          $data['recommend'] = 5;
          $data['others'] = $node->filterXPath('//div/div')->text();
          $data['others']=str_replace(' ','',$data['others']);
          // $data['date'] = $node->filterXPath('//strong')->text();
          // $data['href'] = 'http://www.ttyyy.vip';
          $data['href'] = $node->filterXPath('//div/a/@href')->text();

          $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
          // sleep(1);
          return $data;
          });
          return $datas;
          // return json_encode($ttdytts, 320);
        }

        public function clewer_xunying_get_total_page($leixing)
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
          $client = new Client(['base_uri' => 'https://www.yinfans.com/page/1','timeout'  => 2.0,'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],'handler' => $handlerStack]);
          $vod_search = 'vod-search';
          $response = $client->request('get', 'https://www.xunyingwang.com/'.$leixing);
          $contents = (string) $response->getBody();
          $crawler = new Crawler($contents);
          // $article = [];
          $total_page = $crawler->filterXPath('//*[@class="pager-bg"]/ul/li/a[contains(text(),"末页")]/@data-ci-pagination-page')->text();
          // $total_page = strstr($total_page, "_");
          // return $total_page;
          // $total_page=str_replace('https://www.66s.cc/dongzuopian/index_','',$total_page);
          // $total_page=str_replace('.html','',$total_page);
          // $total_page=str_replace('_','',$total_page);
          return intval($total_page);
          return $total_page;
        }

        public function clewer_xunying()
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
          for($i=1;$i<=2;$i++){
            switch ($i)
              {
              case 1:
                $leixing = "movie";
                break;
              case 2:
                $leixing = "tv";
                break;
              default:
                $leixing = 1;
              }
              // 获取每个类型下的页面总数
            $total_page = $this->clewer_xunying_get_total_page($leixing);
            // return $total_page;
            for($k=1;$k<=$total_page;$k++){
                  // code...
                  $client = new Client([
                       // Base URI is used with relative requests
                       'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                       // You can set any number of default request options.
                       'timeout'  => 2,
                       'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                       'handler' => $handlerStack
                       ]);
                       // $vod_search = 'vod-search';
                       $response = $client->request('get', 'https://www.xunyingwang.com/'.$leixing.'/?page='.$k);
                       $contents = (string)$response->getBody();
                       $this->xunying($contents);
                       // stripslashes(json_encode($this->doubiekan($contents), 320));
                       // dump($this->xunying($contents));
                       dump('讯影网'.$leixing.'已经爬取完第'.$k.'页---');
                       sleep(1);
            }
          }
        }

        // bt之家 失败
        public function clewer_btzhijia()
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
            for($k=1;$k<=1;$k++){
                  // code...
                  $client = new Client([
                       // Base URI is used with relative requests
                       'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                       // You can set any number of default request options.
                       'timeout'  => 2,
                       'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                       'handler' => $handlerStack
                       ]);
                      $response = $client->request('get', 'http://www.415.net/forum-index-fid-1-page-'.$k.'.htm');
                       $contents = (string) $response->getBody();
                       $this->btzhijia($contents);
                       // return $contents;
                       // stripslashes(json_encode($this->doubiekan($contents), 320));
                       // dump('已经爬取完第'.$k.'页---');
                       sleep(1);
          }
        }
        public function btzhijia($contents)
        {
          $crawler = new Crawler();
          $crawler->addHtmlContent($contents);
          $datas = $crawler->filterXPath('//*[@id="threadlist"]/table')->each(function (Crawler $node, $i) {
          $data['title'] = $node->filterXPath('//tr/td[1]/a[5]')->text();
          $data['website'] = "BT之家";
          $data['leixing'] = "download";
          $data['recommend'] = 5;
          $data['others'] = $node->filterXPath('//tr/td[1]/a[5]')->text();
          $data['others']=str_replace(' ','',$data['others']);
          // $data['href'] = 'http://www.doubiekan.org';
          $data['href'] = $node->filterXPath('//tr/td[1]/a[5]/@href')->text();
          $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
          // sleep(1);
          return $data;
          });
          return $datas;
          // return json_encode($datas, 320);
        }

        //BTBT电影网  下载+在线
        public function clewer_btbtdy()
        {
          $handlerStack = HandlerStack::create(new CurlHandler());
          $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
            for($k=1;$k<=311;$k++){
                  // code...
                  $client = new Client([
                       // Base URI is used with relative requests
                       'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                       // You can set any number of default request options.
                       'timeout'  => 2,
                       'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                       'handler' => $handlerStack
                       ]);
                       $vod_search = 'vod-search';
                      $response = $client->request('get', 'http://www.btbtdy.tv/btfl/dy1-'.$k.'.html');
                       $contents = (string) $response->getBody();
                       $this->btbtdy($contents);
                       // stripslashes(json_encode($this->doubiekan($contents), 320));
                       dump('BTBT电影更新第'.$k.'页');
                       sleep(1);
          }
          for($k=1;$k<=80;$k++){
                // code...
                $client = new Client([
                     // Base URI is used with relative requests
                     'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                     // You can set any number of default request options.
                     'timeout'  => 2,
                     'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                     'handler' => $handlerStack
                     ]);
                     $vod_search = 'vod-search';
                    $response = $client->request('get', 'http://www.btbtdy.tv/btfl/dy30-'.$k.'.html');
                     $contents = (string) $response->getBody();
                     $this->btbtdy($contents);
                     // stripslashes(json_encode($this->doubiekan($contents), 320));
                     dump('BTBT电视距更新第'.$k.'页');
                     sleep(1);
        }
        for($k=1;$k<=14;$k++){
              // code...
              $client = new Client([
                   // Base URI is used with relative requests
                   'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                   // You can set any number of default request options.
                   'timeout'  => 2,
                   'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                   'handler' => $handlerStack
                   ]);
                   $vod_search = 'vod-search';
                  $response = $client->request('get', 'http://www.btbtdy.tv/btfl/dy34-'.$k.'.html');
                   $contents = (string) $response->getBody();
                   $this->btbtdy($contents);
                   // stripslashes(json_encode($this->doubiekan($contents), 320));
                   dump('BTBT动漫更新第'.$k.'页');
                   sleep(1);
      }

        }
        public function btbtdy($s66)
        {
          $crawler = new Crawler();
          $crawler->addHtmlContent($s66);
          $datas = $crawler->filterXPath('//*[@class="list_su"]/ul/li')->each(function (Crawler $node, $i) {
          $data['title'] = $node->filterXPath('//div[2]/p[1]/a')->text();
          $data['website'] = "BTBT电影（在线+下载）";
          $data['leixing'] = "download";
          $data['recommend'] = 5;
          $data['others'] = $node->filterXPath('//div[2]/p[2]')->text();
          $data['others']=str_replace(' ','',$data['others']);
          $data['href'] = 'http://www.btbtdy.tv';
          $data['href'] .= $node->filterXPath('//div[2]/p[1]/a/@href')->text();
          $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
          // sleep(1);
          return $data;
          });
          return $datas;
          // return json_encode($datas, 320);
        }

        // 迅雷720
        public function clewer_xl720_get_total_page($leixing)
       {
         $handlerStack = HandlerStack::create(new CurlHandler());
         $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
         $client = new Client(['base_uri' => 'https://www.yinfans.com/page/1','timeout'  => 2.0,'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],'handler' => $handlerStack]);
         $vod_search = 'vod-search';
         $response = $client->request('get', 'https://www.xl720.com/category/'.$leixing);
         $contents = (string) $response->getBody();
         $crawler = new Crawler($contents);
         // $article = [];
         $total_page = $crawler->filterXPath('//div[@class="wp-pagenavi"]/a[contains(text(),"末页")]/@href')->text();
         $total_page = strstr($total_page, "page/");
         // return $total_page;
         // $total_page=str_replace('https://www.66s.cc/dongzuopian/index_','',$total_page);
         $total_page=str_replace('page/','',$total_page);
         // $total_page=str_replace('_','',$total_page);
         return intval($total_page);
         return $total_page;
       }

       public function clewer_xl720()
       {
         $handlerStack = HandlerStack::create(new CurlHandler());
         $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
         for($i=1;$i<=19;$i++){
           switch ($i)
             {
             case 1:
               $leixing = "dongzuopian";
               break;
             case 2:
               $leixing = "fanzuipian";
               break;
             case 3:
               $leixing = "kehuanpian";
               break;
             case 4:
               $leixing = "xijupian";
               break;
             case 5:
               $leixing = "aiqingpian";
               break;
             case 6:
               $leixing = "xuanyipian";
               break;
             case 7:
               $leixing = "kongbupian";
               break;
             case 8:
               $leixing = "zainanpian";
               break;
             case 9:
               $leixing = "zhanzhengpian";
               break;
             case 10:
               $leixing = "donghuapian";
               break;
             case 11:
               $leixing = "maoxianpian";
               break;
             case 12:
               $leixing = "jingsongpian";
               break;
             case 13:
               $leixing = "qihuanpian";
               break;
             case 14:
               $leixing = "juqingpian";
               break;
             case 15:
               $leixing = "jilupian";
               break;
             case 16:
               $leixing = "daluju";
               break;
             case 17:
               $leixing = "gangtaiju";
               break;
             case 18:
               $leixing = "rihanju";
               break;
             case 19:
               $leixing = "oumeiju";
               break;
             default:
               $leixing = 1;
             }
             // 获取每个类型下的页面总数
           $total_page = $this->clewer_xl720_get_total_page($leixing);
           // return $total_page;
           for($k=1;$k<=$total_page;$k++){
               // if($k==1){
                 $client = new Client([
                      // Base URI is used with relative requests
                      'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                      // You can set any number of default request options.
                      'timeout'  => 2,
                      'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                      'handler' => $handlerStack
                      ]);
                      $vod_search = 'vod-search';
                      $response = $client->request('get', 'https://www.xl720.com/category/'.$leixing.'/page/'.$k);
                      $contents = (string) $response->getBody();
                      $this->xl720($contents);
                      // stripslashes(json_encode($this->doubiekan($contents), 320));
                      dump('迅雷720下载网'.$leixing.'已经爬取完第'.$k.'页---');
                      sleep(1);
           }
         }
       }
       public function xl720($xl720)
       {
         $crawler = new Crawler();
         $crawler->addHtmlContent($xl720);
         $datas = $crawler->filterXPath('//div[@id="content"]/div[2]/div[@class="post clearfix"]')->each(function (Crawler $node, $i) {
         $data['title'] = $node->filterXPath('//h3/a/@title')->text();
         $data['website'] = "迅雷720下载";
         $data['leixing'] = "download";
         $data['recommend'] = 5;
         $data['others'] = $node->filterXPath('//div[@class="entry-meta"]')->text();
         $data['others']=str_replace(' ','',$data['others']);
         // $data['href'] = 'http://www.doubiekan.org';
         $data['href'] = $node->filterXPath('//h3/a/@href')->text();
         $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
         // sleep(1);
         return $data;
         });
         return $datas;
         // return json_encode($datas, 320);
       }

       // 优质电影网
       public function clewer_youzhidy_get_total_page($leixing)
       {
         $handlerStack = HandlerStack::create(new CurlHandler());
         $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
         $client = new Client(['base_uri' => 'https://www.yinfans.com/page/1','timeout'  => 2.0,'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],'handler' => $handlerStack]);
         $vod_search = 'vod-search';
         $response = $client->request('get', 'http://www.youzhidy.com/'.$leixing.'/list_1.html');
         $contents = (string) $response->getBody();
         $contents = iconv("gb2312","utf-8//IGNORE",$contents);
         $crawler = new Crawler();
         $crawler->addHtmlContent($contents, 'utf-8');
         // $article = [];
         $total_page = $crawler->filterXPath('//div[@class="pagination"]/ul/li/a[contains(text(),"末页")]/@href')->text();
         // $total_page = strstr($total_page, "page/");
         // return $total_page;
         // $total_page=str_replace('https://www.66s.cc/dongzuopian/index_','',$total_page);
         $total_page=str_replace('list_','',$total_page);
         $total_page=str_replace('.html','',$total_page);
         return intval($total_page);
         // return $total_page;
       }

       public function clewer_youzhidy()
       {
         $handlerStack = HandlerStack::create(new CurlHandler());
         $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
         for($i=1;$i<=2;$i++){
           switch ($i)
             {
             case 1:
               $leixing = "gqdy";
               break;
             case 2:
               $leixing = "gqdsj";
               break;
             default:
               $leixing = 1;
             }
             // 获取每个类型下的页面总数
           $total_page = $this->clewer_youzhidy_get_total_page($leixing);
           // return $total_page;
           for($k=1;$k<=$total_page;$k++){
               // if($k==1){
                 $client = new Client([
                      // Base URI is used with relative requests
                      'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                      // You can set any number of default request options.
                      'timeout'  => 2,
                      'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                      'handler' => $handlerStack
                      ]);
                      $vod_search = 'vod-search';
                      $response = $client->request('get', 'http://www.youzhidy.com/'.$leixing.'/list_'.$k.'.html');
                      $contents = (string) $response->getBody();
                      $contents = iconv("gb2312","utf-8//IGNORE",$contents);
                      $this->youzhidy($contents);
                      dump('优质电影下载网'.$leixing.'已经爬取完第'.$k.'页---');
                      sleep(1);
           }
         }
       }
       public function youzhidy($xl720)
       {
         $crawler = new Crawler();
         $crawler->addHtmlContent($xl720, 'utf-8');
         $datas = $crawler->filterXPath('//div[@class="content"]/article')->each(function (Crawler $node, $i) {
         $data['title'] = $node->filterXPath('//header/h2/a')->text();
         $data['website'] = "优质电影网";
         $data['leixing'] = "download";
         $data['recommend'] = 5;
         $data['others'] = $node->filterXPath('//p')->text();
         $data['others']=str_replace(' ','',$data['others']);
         $data['href'] = 'http://www.youzhidy.com';
         $data['href'] .= $node->filterXPath('//header/h2/a/@href')->text();
         $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
         // sleep(1);
         return $data;
         });
         return $datas;
         // return json_encode($datas, 320);
       }

       // pvideos电影网
       public function clewer_pvideos_get_total_page($leixing)
       {
         $handlerStack = HandlerStack::create(new CurlHandler());
         $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
         $client = new Client(['base_uri' => 'https://www.yinfans.com/page/1','timeout'  => 2.0,'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],'handler' => $handlerStack]);
         $vod_search = 'vod-search';
         $response = $client->request('get', 'https://www.55xia.com/'.$leixing);
         $contents = (string) $response->getBody();
         // $contents = iconv("gb2312","utf-8//IGNORE",$contents);
         $crawler = new Crawler();
         $crawler->addHtmlContent($contents);
         $total_page = $crawler->filterXPath('//div[@class="pager-bg"]/ul/li/a[contains(text(),"末页")]/@href')->text();
         $total_page = strstr($total_page, "=");
         $total_page=str_replace('=','',$total_page);
         return intval($total_page);
         // return $total_page;
       }

       public function clewer_pvideos()
       {
         $handlerStack = HandlerStack::create(new CurlHandler());
         $handlerStack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
         for($i=1;$i<=2;$i++){
           switch ($i)
             {
             case 1:
               $leixing = "movie";
               break;
             case 2:
               $leixing = "tv";
               break;
             default:
               $leixing = 1;
             }
             // 获取每个类型下的页面总数
           $total_page = $this->clewer_pvideos_get_total_page($leixing);
           return $total_page;
           for($k=1;$k<=$total_page;$k++){
               // if($k==1){
                 $client = new Client([
                      // Base URI is used with relative requests
                      'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
                      // You can set any number of default request options.
                      'timeout'  => 2,
                      'headers' => ["User-Agent"=> "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36"],
                      'handler' => $handlerStack
                      ]);
                      $vod_search = 'vod-search';
                      $response = $client->request('get', 'https://www.55xia.com/'.$leixing.'/?page='.$k);
                      $contents = (string) $response->getBody();
                      // $contents = iconv("gb2312","utf-8//IGNORE",$contents);
                      $this->pvideos($contents);
                      dump('pvideos电影下载网'.$leixing.'已经爬取完第'.$k.'页---');
                      sleep(1);
           }
         }
       }
       public function pvideos($pvideos)
       {
         $crawler = new Crawler();
         $crawler->addHtmlContent($pvideos);
         $datas = $crawler->filterXPath('//div[@class="row"]/div[2]/div[@class="col-xs-1-5 col-sm-4 col-xs-6 movie-item"]')->each(function (Crawler $node, $i) {
         $data['title'] = $node->filterXPath('//div/a[1]/@title')->text();
         $data['website'] = "PVideos电影网";
         $data['leixing'] = "download";
         $data['recommend'] = 5;
         $data['others'] = $node->filterXPath('//div')->text();
         $data['others']=str_replace(' ','',$data['others']);
         // $data['href'] = 'http://www.youzhidy.com';
         $data['href'] = $node->filterXPath('//div/a[1]/@href')->text();
         $data = Downloaddatas::updateOrCreate(['title'=>$data['title'],'website'=>$data['website']],['href'=>$data['href'],'others'=>$data['others'],'leixing'=>$data['leixing'],'recommend'=>$data['recommend']]);
         // sleep(1);
         return $data;
         });
         return $datas;
         // return json_encode($datas, 320);
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
