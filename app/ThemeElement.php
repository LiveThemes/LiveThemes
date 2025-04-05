<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThemeElement extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'default' => 'object',
        'related' => 'array'
    ];

    /**
     * Name
     */
    function getNameAttribute($value)
    {
    	if( $value ){
    		return $value;
    	} else {
    		return $this->element;
    	}
    }

    /**
     * Description
     */
    function getDescriptionAttribute($value)
    {
    	if( $value ){
    		return $value;
    	} else {
            return '';
    		return "We haven't figured out what this element controls yet, let us know if you do. You can still set the colour, we just can't show you a preview.";
    	}
    }
}
