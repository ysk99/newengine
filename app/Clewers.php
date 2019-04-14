<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clewers extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'website', 'url','leixing', 'recommend', 'schedule1', 'schedule2'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
