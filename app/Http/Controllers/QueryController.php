<?php

namespace App\Http\Controllers;

use App\Moviedatas;
use App\Downloaddatas;
use App\Doubandatas;
use App\Maoyandatas;
// use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
// use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    //
    public function query_online(Request $request)
    {
      $online = $request->input('keywd');
      // return $keywd;

      // $keywd = iconv("UTF-8","gbk//TRANSLIT",$keywd);
      // return $keywd;
      // $results = Mtotoo::where('title', $keywd);
      // $results = Mtotoo::where('id', '=', $keywd);
      $paginator = [];
        if ($online) {
            // $results1 = Mtotoo::search($q)->get()->toArray();
            // $results2 = Moviedatas::search($q)->get()->toArray();
            // $results2 = Moviedatas::search($keywd)->get()->toArray()->paginate();
            // $paginator = array_merge($results1, $results2);
            // $this->setPage2($request, $results, 10);
            $paginator = Moviedatas::search($online)->paginate(8);

        }
          return $paginator;

    }

    public function query_download(Request $request)
    {
      $download = $request->input('keywd');
      $paginator = [];
        if ($download) {
            // $paginator = Downloaddatas::search($download)->take(20);
            $paginator = Downloaddatas::search($download)->paginate(8);
            // $paginator = $this->setPage2($request,$paginator,6,20);
            // $paginator =$paginator->toArray()['$paginator'];
        }
          return $paginator;
    }

    public function doubanapi()
    {
      // $download = $request->input('keywd');
      // $paginator = [];
        // if ($download) {
            // $paginator = Downloaddatas::search($download)->take(20);
            $paginator = Doubandatas::all();
            // $paginator = $this->setPage2($request,$paginator,6,20);
            // $paginator =$paginator->toArray()['$paginator'];
        // }
          return $paginator;
    }

    public function maoyanapi()
    {
      // $download = $request->input('keywd');
      // $paginator = [];
        // if ($download) {
            // $paginator = Downloaddatas::search($download)->take(20);
            $paginator = Maoyandatas::all();
            // $paginator = $this->setPage2($request,$paginator,6,20);
            // $paginator =$paginator->toArray()['$paginator'];
        // }
          return $paginator;
    }

    // public function setPage2(Request $request,$data,$prepage,$total)
    // {
    //   #每页显示记录
    //     $prePage = $prepage;
    //     //$total =count($data);
    //     $allitem = $prepage *100;
    //     $total > $allitem ? $total = $allitem : $total;
    //     if(isset($request->page)){
    //         $current_page =intval($request->page);
    //         $current_page =$current_page<=0?1:$current_page;
    //     }else{
    //         $current_page = 1;
    //     }
    //     #url操作
    //     $url = $url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
    //     if(strpos($url,'&page')) $url=str_replace('&page='.$request->page, '',$url);
    //
    //     # $data must be array
    //     $item =array_slice($data,($current_page-1)*$prePage,$prePage);
    //     $paginator = new LengthAwarePaginator($item,$total,$prePage,$current_page,[
    //         'path'=>$url,
    //         'pageName'=>'page'
    //     ]);
    //
    //     return $paginator;
    // }
}
