<?php

namespace App\Http\Controllers;

use App\Moviedatas;
use Illuminate\Http\Request;
// use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;


class MsgController extends Controller
{
    //
    public function getmsg()
    {

      $client = new Client([
           // Base URI is used with relative requests
           'base_uri' => 'http://fkdy.me/index.php?m=vod-search',
           // You can set any number of default request options.
           'timeout'  => 10
           ]);
           $vod_search = 'vod-search';
           // $keywd2312 = iconv("UTF-8","gbk//TRANSLIT",'老中医');
       $response = $client->request('POST', 'http://www.mtotoo.com/index.php?m='.$vod_search, ['form_params' => [ 'wd' => '老中医']]);

           $contents = (string) $response->getBody();

           return stripslashes(json_encode($this->mtotoo_save($contents), 320));

    }

    public function getmsg_many(Request $request)
    {
      $keywd = $request->input('keywd');
      $keywd2312 = iconv("UTF-8","gbk//TRANSLIT",$keywd);
      $client = new Client([
           // Base URI is used with relative requests
           'base_uri' => 'http://httpbin.org/',
           'http_errors'     => false,
           // You can set any number of default request options.
           'timeout'  => 20,
           ]);

       $promises = [
         'yy20' => $client->postAsync('http://esyy007.com/index.php?m=vod-search', ['form_params' => [ 'wd' => $keywd]]),
         // 'fw6080'=> $client->postAsync('https://www.6080fw.com/so', ['form_params' => [ 'wd' => '相亲']]),
         'dbk'=> $client->postAsync('http://www.doubiekan.org/search/', ['form_params' => [ 'wd' => $keywd]]),
         'ttyyy'=> $client->postAsync('http://www.ttyyy.vip/search.html', ['form_params' => [ 'searchword' => $keywd]]),
         'fkdy'=> $client->postAsync('http://fkdy.me/index.php?m=vod-search', ['form_params' => [ 'wd' => $keywd]]),
         'ys88'=> $client->postAsync('https://www.88ys.cn/index.php?m=vod-search', ['form_params' => [ 'wd' => $keywd]]),
         'mtotoo'=> $client->postAsync('http://www.mtotoo.com/index.php?m=vod-search', ['form_params' => [ 'wd' => $keywd]]),
         'ttdytt'=> $client->postAsync('https://www.ttdytt.com/search/index.asp', ['form_params' => [ 'keyword' => $keywd2312]])
        ];
        // Wait on all of the requests to complete.
        $results = Promise\unwrap($promises);
        // wait for the requests to complete, even if some of them fail
         // $results = Promise\settle($promises)->wait();
        // You can access each result using the key provided to the unwrap
        // function.
        // echo $results['image']->getHeader('Content-Length');
        // echo $results['png']->getHeader('Content-Length');
        // return json_encode($results['image'], 320);
        // dd($results['image']);
        // return json_encode($results['png'], 320);
        // dd($results['png']);
        $yy20 = (string) $results['yy20']->getBody();
        $dbk = (string) $results['dbk']->getBody();
        $fkdy = (string) $results['fkdy']->getBody();
        $mtotoo = (string) $results['mtotoo']->getBody();
        $ys88 = (string) $results['ys88']->getBody();
        // $fw6080 = (string) $results['fw6080']->getBody();
        $ttdytt = (string) $results['ttdytt']->getBody();
        $ttyyy = (string) $results['ttyyy']->getBody();
        // $doubiekan = (string) $results['png']->getBody();

        // $ttdytt = $this->yang_gbk2utf8($ttdytt);
        $ttdytt = iconv("gb2312","utf-8//IGNORE",$ttdytt);
        // return json_encode($this->yy20($yy20), 320);

        // $dbk = stripslashes(json_encode($this->dbk($dbk), 320));
        // $yy20 = stripslashes(json_encode($this->yy20($yy20), 320));
        // $ttdytt = stripslashes(json_encode($this->ttdytt($ttdytt), 320));

        // dd($ttdytt);
        // return json_encode($this->ttdytt($ttdytt), 320);
        // return array_column($dbk, $yy20, $ttdytt );
        $data = array_merge($this->dbk($dbk), $this->yy20($yy20), $this->ttdytt($ttdytt), $this->ttyyy($ttyyy), $this->fkdy($fkdy), $this->ys88($ys88), $this->mtotoo($mtotoo));
        return stripslashes(json_encode($data, 320));

    }

    public function dbk($dbk)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($dbk);
      $dbks = $crawler->filterXPath('//div[@id="content"]/div[@class="details-info-min col-md-12 col-sm-12 col-xs-12 clearfix news-box-txt p-0"]')->each(function (Crawler $node, $i) {
      $dbk['title'] = $node->filterXPath('//li[1]/a')->text();
      $dbk['website'] = "逗别看影院";
      $dbk['others'] = "none";
      $dbk['href'] = 'http://www.doubiekan.org';
      $dbk['href'] .= $node->filterXPath('//li[1]/a/@href')->text();

      // sleep(3);
      $movie = new Moviedatas;
      $movie->title = $dbk['title'];
      $movie->website = $dbk['website'];
      $movie->href = $dbk['href'];
      $movie->others = $dbk['others'];
      $movie->save();
      return $dbk;
      });
      return $dbks;
      // return json_encode($dbks, 320);
    }

    public function doubiekan($doubiekan)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($doubiekan);
      $doubiekans = $crawler->filterXPath('//div[@class="wrapper"]/div[@class="main"]/div[@class="row mt15 clearfix"]/div[@class="itemList"]')->each(function (Crawler $node, $i) {
      $doubiekan['title'] = $node->filterXPath('//div[@class="tit"]/h2/a')->text();
      $doubiekan['website'] = "6080fw影院";
      $doubiekan['others'] = "none";
      $doubiekan['href'] = $node->filterXPath('//div[@class="tit"]/h2/a/@href')->text();
      // sleep(3);
      $movie = new Moviedatas;
      $movie->title = $doubiekan['title'];
      $movie->website = $doubiekan['website'];
      $movie->href = $doubiekan['href'];
      $movie->others = $doubiekan['others'];
      $movie->save();
      return $doubiekan;
      });
      return $doubiekans;
      // return json_encode($doubiekans, 320);
    }



    public function yy20($yy20)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($yy20);
      $datas = $crawler->filterXPath('//div[@class="movie-item"]/div[@class="movie-info"]')->each(function (Crawler $node, $i) {
      $data['title'] = $node->filterXPath('//p[@class="movie-info-format"]/strong')->text();
      $data['website'] = "二十影院";
      $data['date'] = $node->filterXPath('//p[@class="movie-info-updateTime"]')->text();
      $data['others'] = $node->filterXPath('//p')->text();
      $data['href'] = 'http://esyy007.com';
      $data['href'] .= $node->filterXPath('//a/@href')->text();
      // sleep(3);
      $movie = new Moviedatas;
      $movie->title = $data['title'];
      $movie->website = $data['website'];
      $movie->href = $data['href'];
      $movie->others = $data['others'];
      $movie->save();
      return $data;
      });
      return $datas;
      // return json_encode($datas, 320);
    }

    public function ttdytt($ttdytt)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($ttdytt);
      $ttdytts = $crawler->filterXPath('//div[@class="so"]/ol')->each(function (Crawler $node, $i) {
      $ttdytt['title'] = $node->filterXPath('//a')->text();
      $ttdytt['website'] = "天天电影天堂";
      $ttdytt['others'] = $node->filterXPath('//b')->text();
      $ttdytt['date'] = $node->filterXPath('//strong')->text();
      $ttdytt['href'] = $node->filterXPath('//a/@href')->text();
      // sleep(3);
      $movie = new Moviedatas;
      $movie->title = $ttdytt['title'];
      $movie->website = $ttdytt['website'];
      $movie->href = $ttdytt['href'];
      $movie->others = $ttdytt['others'];
      $movie->save();
      return $ttdytt;
      });
      return $ttdytts;
      // return json_encode($ttdytts, 320);
    }

    public function ttyyy($ttyyy)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($ttyyy);
      $ttyyys = $crawler->filterXPath('//div[@class="module-content"]/ul/li')->each(function (Crawler $node, $i) {
      $ttyyy['title'] = $node->filterXPath('//a/div[@class="text"]/p[1]')->text();
      $ttyyy['website'] = "天天云影院";
      $ttyyy['others'] = $node->filterXPath('//a/div[@class="img"]/span')->text();
      // $ttyyy['date'] = $node->filterXPath('//strong')->text();
      $ttyyy['href'] = 'http://www.ttyyy.vip';
      $ttyyy['href'] .= $node->filterXPath('//a/@href')->text();
      // sleep(3);
      $movie = new Moviedatas;
      $movie->title = $ttyyy['title'];
      $movie->website = $ttyyy['website'];
      $movie->href = $ttyyy['href'];
      $movie->others = $ttyyy['others'];
      $movie->save();
      return $ttyyy;
      });
      return $ttyyys;
      // return json_encode($ttdytts, 320);
    }

    public function fkdy($fkdy)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($fkdy);
      $fkdys = $crawler->filterXPath('//div[@class="wrap"]/ul[@class="sul"]/li')->each(function (Crawler $node, $i) {
      $fkdy['title'] = $node->filterXPath('//a/div[@class="stext"]/p[1]')->text();
      $fkdy['website'] = "疯狂影院";
      $fkdy['others'] = $node->filterXPath('//a/div[@class="stext"]')->text();
      // $ttyyy['date'] = $node->filterXPath('//strong')->text();
      $fkdy['href'] = 'http://fkdy.me';
      $fkdy['href'] .= $node->filterXPath('//a[1]/@href')->text();
      // sleep(3);
      $movie = new Moviedatas;
      $movie->title = $fkdy['title'];
      $movie->website = $fkdy['website'];
      $movie->href = $fkdy['href'];
      $movie->others = $fkdy['others'];
      $movie->save();
      return $fkdy;
      });
      return $fkdys;
      // return json_encode($ttdytts, 320);
    }

    public function ys88($ys88)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($ys88);
      $ys88s = $crawler->filterXPath('//div[@class="index-area clearfix"]/ul/li')->each(function (Crawler $node, $i) {
      $ys88['title'] = $node->filterXPath('//a/span[@class="lzbz"]/p[1]')->text();
      $ys88['website'] = "88影视";
      $ys88['others'] = $node->filterXPath('//a')->text();
      // $ttyyy['date'] = $node->filterXPath('//strong')->text();
      $ys88['href'] = 'https://www.88ys.cn';
      $ys88['href'] .= $node->filterXPath('//a/@href')->text();
      // sleep(3);
      $movie = new Moviedatas;
      $movie->title = $ys88['title'];
      $movie->website = $ys88['website'];
      $movie->href = $ys88['href'];
      $movie->others = $ys88['others'];
      $movie->save();

      return $ys88;
      });
      return $ys88s;
      // return json_encode($ttdytts, 320);
    }

    public function mtotoo($mtotoo)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($mtotoo);
      $mtotoos = $crawler->filterXPath('//*[@class="hy-layout clearfix"]/div[@class="hy-video-details active clearfix"]')->each(function (Crawler $node, $i) {
      $mtotoo['title'] = $node->filterXPath('//div/dl/dd/div[1]/a')->text();
      $mtotoo['website'] = "田鸡影院";
      // $mtotoo['others'] = $node->filterXPath('//div')->text();
      // $ttyyy['date'] = $node->filterXPath('//strong')->text();
      $mtotoo['href'] = 'http://www.mtotoo.con';
      $mtotoo['href'] .= $node->filterXPath('//div/dl/dd/div[1]/a/@href')->text();
      // sleep(3);
      return $mtotoo;
      });
      return $mtotoos;
      // return json_encode($ttdytts, 320);
    }

    public function mtotoo_save($mtotoo)
    {
      $crawler = new Crawler();
      $crawler->addHtmlContent($mtotoo);
      $mtotoos = $crawler->filterXPath('//*[@class="hy-layout clearfix"]/div[@class="hy-video-details active clearfix"]')->each(function (Crawler $node, $i) {
      $mtotoo['title'] = $node->filterXPath('//div/dl/dd/div[1]/a')->text();
      $mtotoo['website'] = "田鸡影院";
      $mtotoo['others'] = 'none';
      // $ttyyy['date'] = $node->filterXPath('//strong')->text();
      $mtotoo['href'] = 'http://www.mtotoo.con';
      $mtotoo['href'] .= $node->filterXPath('//div/dl/dd/div[1]/a/@href')->text();
      // sleep(3);
      $movie = new Moviedatas;
      $movie->title = $mtotoo['title'];
      $movie->website = $mtotoo['website'];
      $movie->href = $mtotoo['href'];
      $movie->others = $mtotoo['others'];
      $movie->save();
      return $mtotoo;
      });
      return $mtotoos;
      // return json_encode($ttdytts, 320);
    }

}
