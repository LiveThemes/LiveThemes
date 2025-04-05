<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertClick extends Model
{
    use HasFactory;

    protected $fillable = [
    	'advert_id', 'user_id'
    ];

    /**
     * Advert
     */
    public function advert(){
        return $this->belongsTo('App\Advert');
    }

    /**
     * User
     */
    public function user(){
        return $this->belongsTo('App\User');
    }
}
