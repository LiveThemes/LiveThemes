<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [
    	'id', 'created_at', 'updated_at'
    ];

    /**
     * Themes
     */
    public function themes(){
        return $this->belongsToMany('App\Theme');
    }

    /**
     * Tag Name
     */
    public function getNameAttribute($value)
    {
        $value = app('profanityFilter')->filter($value);
        return ucfirst($value);
    }

    /**
     * Tag Label
     */
    public function getLabelAttribute($value)
    {
        $value = app('profanityFilter')->filter($value);
        return ucfirst($value);
    }

    /**
     * Tag Slug
     */
    public function getSlugAttribute($value)
    {
        $value = app('profanityFilter')->filter($value);
        return ucfirst($value);
    }
}
