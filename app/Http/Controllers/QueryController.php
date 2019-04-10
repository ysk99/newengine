<?php

namespace App\Http\Controllers;

use App\Moviedatas;
use App\Downloaddatas;
use App\Doubandatas;
use App\Maoyandatas;
use App\MySearchRule;
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
            $paginator = Moviedatas::search($online)->paginate(8);
            // $paginator = Moviedatas::search($online)
            // // ->orderBy('recommend','asc')
            // ->from(0)
            // // set limit
            // ->take(10)
            // // get results
            // ->get();
            // // ->rule(MySearchRule::class)
            // // ->first();
            // $paginator->highlight->title;
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

}
