<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThemeColour extends Model
{
	public $timestamps = true;

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
     * Colours
     */
    public static function colours(){
    	return [
			'red' => [
				'name' => 'Red',
				'hue' => 1,
				'range' => 10
			],
			'orange' => [
				'name' => 'Orange',
				'hue' => 25,
				'range' => 10
			],
			'yellow' => [
				'name' => 'Yellow',
				'hue' => 60,
				'range' => 30
			],
			'green' => [
				'name' => 'Green',
				'hue' => 120,
				'range' => 20
			],
			'cyan' => [
				'name' => 'Cyan',
				'hue' => 180,
				'range' => 20
			],
			'blue' => [
				'name' => 'Blue',
				'hue' => 220,
				'range' => 20
			],
			'purple' => [
				'name' => 'Purple',
				'hue' => 270,
				'range' => 20
			],
			'pink' => [
				'name' => 'Pink',
				'hue' => 300,
				'range' => 20
			],
			'magenta' => [
				'name' => 'Magenta',
				'hue' => 330,
				'range' => 20
			]
		];
    }

    /**
     * RGB to HEX
     */
    public static function rgbToHex( $r, $g, $b ) {
    	return sprintf("%02x%02x%02x", $r, $g, $b);
    }

    /**
     * RGB to HSL
     */
    public static function rgbToHsl( $r, $g, $b ) {
		$oldR = $r;
		$oldG = $g;
		$oldB = $b;
		$r /= 255;
		$g /= 255;
		$b /= 255;
	    $max = max( $r, $g, $b );
		$min = min( $r, $g, $b );
		$h;
		$s;
		$l = ( $max + $min ) / 2;
		$d = $max - $min;
	    	if( $d == 0 ){
	        	$h = $s = 0; // achromatic
	    	} else {
	        	$s = $d / ( 1 - abs( 2 * $l - 1 ) );
			switch( $max ){
		            case $r:
		            	$h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
	                        if ($b > $g) {
		                    $h += 360;
		                }
		                break;
		            case $g:
		            	$h = 60 * ( ( $b - $r ) / $d + 2 );
		            	break;
		            case $b:
		            	$h = 60 * ( ( $r - $g ) / $d + 4 );
		            	break;
		        }
		}
		return array( round( $h, 2 ), round( $s, 2 ), round( $l, 2 ) );
	}

	/**
	 * HSL to RGB
	 */
	public static function hslToRgb( $h, $s, $l ){
	    $r;
	    $g;
	    $b;
		$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
		$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
		$m = $l - ( $c / 2 );
		if ( $h < 60 ) {
			$r = $c;
			$g = $x;
			$b = 0;
		} else if ( $h < 120 ) {
			$r = $x;
			$g = $c;
			$b = 0;
		} else if ( $h < 180 ) {
			$r = 0;
			$g = $c;
			$b = $x;
		} else if ( $h < 240 ) {
			$r = 0;
			$g = $x;
			$b = $c;
		} else if ( $h < 300 ) {
			$r = $x;
			$g = 0;
			$b = $c;
		} else {
			$r = $c;
			$g = 0;
			$b = $x;
		}
		$r = ( $r + $m ) * 255;
		$g = ( $g + $m ) * 255;
		$b = ( $b + $m  ) * 255;
	    return array( floor( $r ), floor( $g ), floor( $b ) );
	}
}
