<?php

use App\Theme;
use App\ThemeColour;
use Illuminate\Database\Seeder;

class ConvertThemeLightness extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lightness = ThemeColour::select('theme_id', 'lightness')->where('element', 'SurfaceBackground')->get();

        foreach($lightness as $v){

        	$theme = Theme::find($v->theme_id);

        	if( !is_null($theme) ){

	        	$theme->update([
	        		'lightness' => $v->lightness
	        	]);

	        }
        }
    }
}
