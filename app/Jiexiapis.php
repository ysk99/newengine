<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jiexiapis extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url', 'website','others', 'recommend',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
