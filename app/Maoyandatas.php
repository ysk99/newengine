<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maoyandatas extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'rank','img', 'year','casts','quto','rating',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
