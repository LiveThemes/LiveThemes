<?php

use App\Theme;
use App\ThemeColour;
use Illuminate\Database\Seeder;

class ConvertThemeHue extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hue = ThemeColour::select('theme_id', 'element', 'hue')
							->where(function($q){
								$q->where('element', 'ControlForeground')
								  ->orWhere('element', 'RetroDisplayForeground');
							})
							->whereBetween('lightness', [40, 70])
            				->where('saturation', '>', 40)
							->get();

        foreach($hue as $v){

        	$theme = Theme::find($v->theme_id);

        	if( !is_null($theme) ){

        		if( $v->element == 'ControlForeground' ){

        			$theme->update([
		        		'colour_1' => $v->hue
		        	]);

        		} else {

        			$theme->update([
		        		'colour_2' => $v->hue
		        	]);

        		}

	        }
        }
    }
}
