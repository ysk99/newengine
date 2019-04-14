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

//热搜查询
Route::any('hotsearchs_query', 'JiexiapisController@hotsearchs_query');



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

//后端路由区域
Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@register');

Route::group(['middleware' => 'auth:api'], function() {
    //视频解析前端url获取
    Route::any('jiexiapi_add', 'JiexiapisController@jiexiapi_add');
    Route::any('jiexiapi_delete', 'JiexiapisController@jiexiapi_delete');
    //热搜查询
    Route::any('hotsearchs_add', 'JiexiapisController@hotsearchs_add');
    Route::any('hotsearchs_delete', 'JiexiapisController@hotsearchs_delete');
    //爬虫相关
    Route::any('clewers_add', 'ClewersController@clewers_add');
    Route::any('clewers_query', 'ClewersController@index');
 

    // Route::get('articles', 'ArticleController@index');
    Route::post('articles/getListPagination', 'ArticleController@getListPagination');
    Route::get('articles/{id}', 'ArticleController@show');
    Route::post('articles/store', 'ArticleController@store');
    Route::post('articles/update', 'ArticleController@update');
    // Route::put('articles/{id}', 'ArticleController@update');
    Route::delete('articles/{id}', 'ArticleController@delete');
    Route::post('logout', 'Auth\LoginController@logout');
    // 电影网站新增 后端
    Route::post('newmsg/store', 'MovieController@store');
    Route::post('newmsg/update', 'MovieController@update');
    Route::delete('newmsg/delete/{id}', 'MovieController@delete');
    //前端照片上传 后端
    Route::post('files/upload', 'UploadController@upfile');
  
    // EO相关路由 后端
    Route::get('eo', 'EotimeController@index');
  });