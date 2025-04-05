<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colour extends Model
{
    protected $fillable = [
    	'user_id', 'r', 'g', 'b'
    ];
}
