<?php

if (!function_exists('switchUrlLocaleTo')) {
    function switchUrlLocaleTo($locale)
    {
        return route('switch.lang', ['lang' => $locale]);
    }
}
