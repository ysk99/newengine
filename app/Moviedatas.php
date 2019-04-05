<?php

namespace App;

use ScoutElastic\Searchable;
use App\MySearchRule;
use Illuminate\Database\Eloquent\Model;

class moviedatas extends Model
{
  use Searchable;

  /**
   * @var string
   */
  protected $indexConfigurator = MoviedatasConfigurator::class;
  protected $table = 'moviedatas';
 protected $searchRules = [
     //
     MySearchRule::class
 ];
 public function searchableAs()
 {
     return 'moviedatas';
 }
 protected $fillable = [
     'title', 'website', 'href', 'others', 'leixing', 'recommend',
 ];
 public function toSearchableArray()
     {
       return [
       'id'=>$this->id,
       'title'=>$this->title
   ];
     }
 /**
  * @var array
  */
  protected $mapping = [
    '_source' => [
      'enabled' => true
  ],
  'properties' => [ //⽂文档类型设置（相当于mysql的数据类型）
      'id' => [
          'type' => 'integer', // //类型 string、integer、float、double、boolean、date,text,keyword
          //'index'=> 'not_analyzed',//索引是否精确值 analyzed not_analyzed
      ],
      'title' => [
          'type' => 'text', // 字段类型为全⽂文检索,如果需要关键字,则修改为keyword,注意keyword字段为整体查询,不不能作为模糊搜索
          "analyzer" => "ik_max_word",
          "search_analyzer" => "ik_smart",
       ]
  ]
  ];
}
