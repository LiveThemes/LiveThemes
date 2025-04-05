<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
	protected $fillable = [
		'from', 'to', 'image', 'url', 'alt'
	];

    protected $dates = [
    	'from', 'to'
    ];

    /**
     * Clicks
     */
    public function clicks(){
        return $this->hasMany('App\AdvertClick');
    }
}
