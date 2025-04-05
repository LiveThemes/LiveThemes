<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThemeComment extends Model
{
    protected $guarded = [
		'id', 'created_at', 'updated_at'
	];

    protected $appends = [
        'created_at_ago'
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

    /**
     * Comment
     */
    public function getCommentAttribute($value)
    {
        $value = app('profanityFilter')->filter($value);
        return ucfirst($value);
    }

    /**
     * Created At Ago
     */
    public function getCreatedAtAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
