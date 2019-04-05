<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jiexiapis;
use App\Hotsearchs;

class JiexiapisController extends Controller
{
    //
    public function jiexiapi_add(Request $request)
    {
      // $jiexi = New Jiexiapis;
      $jiexi['url'] = $request->input('url');
      $jiexi['others'] = $request->input('others');
      $jiexi['website']  = $request->input('website');
      $jiexi['recommend'] = $request->input('recommend');
      // $jiexi->save();
      // return $jiexi;
      $jiexi= Jiexiapis::updateOrCreate(['website'=>$jiexi['website']],['url'=>$jiexi['url'],'others'=>$jiexi['others'],'recommend'=>$jiexi['recommend']]);
      return $jiexi;
    }

    public function jiexiapi_query()
    {
      $datas = Jiexiapis::all();
      return $datas;
    }

    public function jiexiapi_delete(Request $request)
    {
      $jiexi = $request->input('website');
      $datas = Jiexiapis::where('website', '=', $jiexi)->delete();
      return $datas;
    }

    public function hotsearchs_query()
    {
      $datas = Hotsearchs::all();
      return $datas;
    }

    public function hotsearchs_add(Request $request)
    {
      // $hot = New Hotsearchs;
      $hot['uid'] = $request->input('uid');
      $hot['name'] = $request->input('name');
      // $hot->save();
      $hot= Hotsearchs::updateOrCreate(['uid'=>$hot['uid']],['name'=>$hot['name']]);
      return $hot;
    }

    public function hotsearchs_delete(Request $request)
    {
      $uid = $request->input('uid');
      $datas = Hotsearchs::where('uid', '=', $uid)->delete();
      return $datas;
    }

}
