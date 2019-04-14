<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clewers;


class ClewersController extends Controller
{
    //
    public function clewers_add(Request $request)
    {
      // $jiexi = New Jiexiapis;
      // $jiexi['url'] = $request->input('url');
      // $jiexi['others'] = $request->input('others');
      // $jiexi['website']  = $request->input('website');
      // $jiexi['recommend'] = $request->input('recommend');
      // $jiexi->save();
      // return $jiexi;
      $clewers = $request->input('formdata');
      $clewers= Clewers::updateOrCreate(['website'=>$clewers['website']],['url'=>$clewers['url'],'schedule1'=>$clewers['schedule1'],'schedule2'=>$clewers['schedule2'],'leixing'=>$clewers['leixing'],'recommend'=>$clewers['recommend']]);
      return $clewers;
    }

    public function index()
    {
      $datas = Clewers::all();
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
      $hot = $request->input('formdata');
        // $hot = new Hotsearchs;
        // $hot->uid = $formdata['uid'];
        // $hot->name = $formdata['name'];
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
