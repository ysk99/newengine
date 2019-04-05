<?php

use Illuminate\Http\Request;
use App\Mtotoo;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//在线数据
Route::get('clewer_doubiekan', 'GetController@clewer_doubiekan');
Route::get('clewer_ttyyy', 'GetController@clewer_ttyyy');
Route::get('clewer_mtotoo', 'GetController@clewer_mtotoo');
Route::get('clewer_zxzj', 'GetController@clewer_zxzj');
Route::get('clewer_yc15', 'GetController@clewer_yc15');
Route::get('clewer_ys88', 'GetController@clewer_ys88');
//下载数据
Route::get('clewer_yinfansi', 'GetdownloaddatasController@clewer_yinfansi');
Route::get('clewer_dytt', 'GetdownloaddatasController@clewer_dytt');
Route::get('clewer_s66', 'GetdownloaddatasController@clewer_s66');

//实时查询
Route::get('getmsg', 'MsgController@getmsg');
Route::post('getmsg_post', 'MsgController@getmsg_post');
Route::post('getmsg_many', 'MsgController@getmsg_many');
//豆瓣爬虫
Route::get('doubandatas', 'DoubandatasController@doubandatas');
Route::get('maoyandatas', 'DoubandatasController@maoyandatas');

//百度关键词建议接口
Route::post('baidu_suggest', 'BaiduController@baidu_suggest');

//视频解析前端url获取
Route::any('jiexiapi_query', 'JiexiapisController@jiexiapi_query');
Route::any('jiexiapi_add', 'JiexiapisController@jiexiapi_add');
Route::any('jiexiapi_delete', 'JiexiapisController@jiexiapi_delete');
//热搜查询
Route::any('hotsearchs_query', 'JiexiapisController@hotsearchs_query');
Route::any('hotsearchs_add', 'JiexiapisController@hotsearchs_add');
Route::any('hotsearchs_delete', 'JiexiapisController@hotsearchs_delete');


// 前端用户访问
Route::any('query', 'QueryController@query_online');
Route::any('query_download', 'QueryController@query_download');
Route::any('doubanapi', 'QueryController@doubanapi');
Route::any('maoyanapi', 'QueryController@maoyanapi');


// 引擎测试路由
// Route::get('search_test', function () {
//     dump('下面搜索的是：企业搜索');
//     dump(Mtotoo::search('爱')->get()->toArray());
// });
// Route::get('/', function () {
//     return redirect('/search');
// });
// Route::any('/search', [
//     'uses' => 'QueryController@query'
// ]);
// Route::get('/', function () {
//     return view('search');
// });
