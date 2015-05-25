<?php
/**
 * Truncates string with specified length.
 * 
 * @param string $string
 * @param int $length
 * @param string $etc
 * @param bool $break_words
 * @param bool $middle
 * @return string
 */
function ale_truncate($string, $length = 80, $etc = '&#133;', $break_words = false, $middle = false) {
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}

/**
 * Check if the url has http:// at the beginning
 * @param string $url
 * @return string 
 */
function ale_get_url($url) {
    if (preg_match('~^https?\:\/\/~si', $url)) {
        return $url;
    } else {
        return 'http://' . $url;
    }
}

function ale_url($url) {
    echo ale_get_url($url);
}