<?php

/**
 * Override the default Carbon language to the specified in the config.
 */
App::before(function ($request) {

    try {
		Carbon\Carbon::setLocale(Config::get('app.locale'));
    	
    } catch (Exception $e) {
    }

});
