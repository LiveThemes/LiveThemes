<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThemeRating extends Model
{
    protected $guarded = [
		'id', 'created_at', 'updated_at'
	];

	/**
     * Belongs to Theme
     */
    public function theme(){
        return $this->belongsTo('App\Theme');
    }

    /**
     * Belongs to User
     */
    public function user(){
        return $this->belongsTo('App\User');
    }
}
