<?php

/**
 * Clean Input (used by Sanitize)
 * @param  string $input String to clean up
 * @return string        Cleaned String
 */
function cleanInput($input, $html = false)
{
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );
    if($html==false){
    	$search[] = '@<[\/\!]*?[^<>]*?>@si'; // Strip out HTML tags
    }
    $output = preg_replace($search, '', $input);
    return $output;
}

/**
 * Sanitize string
 * @param  string $input String to sanitize
 * @return string        Santized string
 */
function sanitize($input, $html = false)
{
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $output  = cleanInput($input,$html);
    }
    return $output;
}
