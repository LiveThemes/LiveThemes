<?php

namespace App;

use App\ThemeElement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Imagick;
use ImagickPixel;
use Storage;

class Theme extends Model
{
    protected $guarded = [
    	'id', 'created_at', 'updated_at'
    ];

    protected $casts = [
	    'elements' => 'object',
	];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $appends = [
        'url'
    ];

    /**
     * Boot
     */
    public static function boot() {
        parent::boot();

        static::deleting(function($theme) {

            $theme->themeColours()->delete();
            $theme->comments()->delete();
            $theme->downloads()->delete();
            $theme->ratings()->delete();

            if( $theme->preview ){
                Storage::delete( $theme->preview );
            }

        });
    }

    /**
     * Author
     */
    public function author(){
        return $this->belongsTo('App\User', 'author_user_id', 'id');
    }

    /**
     * Remixed from Theme
     */
    public function remixedFrom(){
        return $this->belongsTo('App\Theme', 'remixed_theme_id', 'id');
    }

    /**
     * Comments
     */
    public function comments(){
        return $this->hasMany('App\ThemeComment');
    }

    /**
     * Ratings
     */
    public function ratings(){
        return $this->hasMany('App\ThemeRating');
    }

    /**
     * Downloads
     */
    public function downloads(){
        return $this->hasMany('App\ThemeDownload');
    }

    /**
     * Colours
     */
    public function themeColours(){
        return $this->hasMany('App\ThemeColour');
    }

    /**
     * Lightness
     */
    public function lightness(){
        return $this->hasOne('App\ThemeColour', 'theme_id', 'id')->where('element', 'SurfaceBackground');
    }

    /**
     * Theme Name
     */
    public function getNameAttribute($value)
    {
        $value = app('profanityFilter')->filter($value);
        return ucfirst($value);
    }

    /**
     * Theme Description
     */
    public function getDescriptionAttribute($value)
    {
        $value = app('profanityFilter')->filter($value);
        return ucfirst($value);
    }

    /**
     * Theme URL
     */
    public function getUrlAttribute()
    {
        $slug = Str::slug($this->name);

        return '/themes/' . ($slug ? : 'untitled') . '/' . $this->id;
    }

    /**
     * Title Style
     */
    public function getTitleStyleAttribute(){

        $R = intval($this->elements->RetroDisplayForeground->r);
        $G = intval($this->elements->RetroDisplayForeground->g);
        $B = intval($this->elements->RetroDisplayForeground->b);

        $style = 'background-color: rgb(' . $R . ', ' . $G . ', ' . $B . ');';

        $lightness = (max($R, $G, $B) + min($R, $G, $B)) / 510.0;

        $style .= $lightness > 0.5 ? 'color:#111' : 'color:#FFF';

        return $style;

    }

    /**
     * Primary Colour
     */
    public function getPrimaryColourAttribute(){

        $R = intval($this->elements->RetroDisplayForeground->r);
        $G = intval($this->elements->RetroDisplayForeground->g);
        $B = intval($this->elements->RetroDisplayForeground->b);

        return 'rgb(' . $R . ', ' . $G . ', ' . $B . ');';

    }

    /**
     * CSS
     */
    public function css($important = false, $toArray = false)
    {
        $css = '.Preferences, .Alert, .AlertText, .AlertGroup, .Session, .Automation, .AudioEditor, .MidiEditor{ opacity: 0 !important; } svg text{ font-family: Work Sans, sans-serif' . ( $important ? ' !important' : '') . '; }';

        $array = [
            'elements' => [],
            'opacity'  => []
        ];

        foreach($this->elements as $k => $e){

            if( isset($e->r) ){

                $css .= '.' . $k . '{ fill: ' . $this->elementToRGBAString($e) . ( $important ? ' !important' : '') . '; }';

                $array['elements'][$k] = [ 'fill: ' . $this->elementToRGBString($e) . ';' ];

                if( $e->a < 255 ){
                    $array['opacity'][$k] = 0.1;
                }

                if( isset($e->related) && $e->related ){

                    foreach($e->related as $r){

                        if( isset($r->property) ){

                            $css .= '.' . $k . '{ ' . $r->property . ': ' . $this->elementToRGBAString( $this->elements->{$r->element} ) . ( $important ? ' !important' : '') . '; }';

                            if( !$array['elements'][$k] ){
                                $array['elements'][$k] = [ 'fill: ' . $this->elementToRGBString($e) . ';' ];
                            } else {
                                $array['elements'][$k][] = $r->property . ': ' . $this->elementToRGBString( $this->elements->{$r->element} ) . ';';
                            }

                        }

                    }

                }

            }

        }

        return $toArray ? $array : $css;
    }

    /**
     * Format Elements
     */
    public function toEditor(){

        // Get Elements
        $elements = ThemeElement::all();

        // Theme Elements
        $themeElements = (array) json_decode(json_encode($this->elements), true);

        // Editor Elements
        $editorElements = [];

        foreach($elements as $e){

            // Already in theme
            if( isset($themeElements[ $e->element ]) ){

                $el = $themeElements[ $e->element ];

                if(!is_array($el)){
                    $el = [ 'value' => $el ];
                }

            // New element not in theme
            } else {

                // Check for inherits
                if( $e->inherits && isset($themeElements[$e->inherits]) ){
                    $value = (object) $themeElements[$e->inherits];
                } else {
                    $value = $e->default;
                }

                if( isset($value->r) ){

                    $el = [
                        'r' => $value->r,
                        'g' => $value->g,
                        'b' => $value->b,
                        'a' => $value->a
                    ];

                } else {

                    $el = [
                        'value' => $value->value
                    ];

                }
            }

            // Descriptive Elements
            $el['element'] = $e->element;
            $el['name'] = $e->name;
            $el['description'] = $e->description;
            $el['related'] = $e->related;
            $el['alpha'] = $e->alpha;

            // RGB
            if( isset($el['r']) ){
                $el['rgba'] = 'rgba(' . $el['r'] . ',' . $el['g'] . ',' . $el['b'] . ',' . ($el['a']/255) . ')';
            }

            $editorElements[ $e->element ] = $el;

        }

        $this->elements = json_decode(json_encode($editorElements));

        return $this;

    }

    /**
     * To SVG
     */
    public function toSVG()
    {
        $html = file_get_contents( resource_path('/views/partials/preview-simple.blade.php') );
        $css = $this->css(false, true);

        $inlined = $html;

        foreach($css['elements'] as $k => $v){
            $inlined = str_replace('class="' . $k . '"', 'class="' . $k . '" style="' . implode(' ', $v) . '"', $inlined);
        }

        foreach($css['opacity'] as $k => $v){
            $inlined = str_replace('class="' . $k . '"', 'class="' . $k . '" fill-opacity="' . $v . '"', $inlined);
        }

        $inlined = preg_replace('/fill=".+?" ?/m', '', $inlined);

        return $inlined;
    }

    /**
     * Element to RGBA string
     */
    public static function elementToRGBAString($element)
    {
        return 'rgba(' . $element->r . ',' . $element->g . ',' . $element->b . ',' . ($element->a / 255) . ')';
    }

    /**
     * Element to RGB string
     */
    public static function elementToRGBString($element)
    {
        return 'rgb(' . $element->r . ',' . $element->g . ',' . $element->b . ')';
    }

    /**
     * Upgrade Theme
     */
    public function upgrade()
    {

        $this->updated = false;

        $elements = $this->elements;

        // Get Standard Elements
        $themeElements = ThemeElement::select('element', 'default', 'inherits', 'alpha')->get();

        foreach($themeElements as $v){

            // If new element not in theme
            if( !isset( $elements->{ $v->element } ) ){

                // Check for inherits
                if( $v->inherits && isset($theme->elements->{ $v->inherits }) ){
                    $elements->{ $v->element } = $theme->elements->{ $v->inherits };
                } else {
                    $elements->{ $v->element } = $v->default;
                }

            // If it exists, check alpha
            } else if( $v->alpha == false && isset($elements->{ $v->element }->a) && intval($elements->{ $v->element }->a) != 255 ) {

                $elements->{ $v->element }->a = 255;
                $elements->{ $v->element }->rgba = 'rgba(' . $elements->{ $v->element }->r . ',' . $elements->{ $v->element }->g . ',' . $elements->{ $v->element }->b . ',1)';

                $this->updated = true;

            }

        }

        $this->elements = $elements;

        return $this;

    }
}
