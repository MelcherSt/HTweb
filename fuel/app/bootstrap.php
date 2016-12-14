<?php
// Bootstrap the framework DO NOT edit this
require COREPATH.'bootstrap.php';

\Autoloader::add_classes(array(
	// Add classes you want to override here
	// Example: 'View' => APPPATH.'classes/view.php',
));

// Register the autoloader
\Autoloader::register();

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGING
 * Fuel::PRODUCTION
 */
\Fuel::$env = \Arr::get($_SERVER, 'FUEL_ENV', \Arr::get($_ENV, 'FUEL_ENV', \Fuel::DEVELOPMENT));

// Initialize the framework with the config file.
\Fuel::init('config.php');

\Lang::load('template'); 
\Lang::load('session', 'session');
\Lang::load('user', 'user');
\Lang::load('actions', 'actions');	
\Lang::load('alert', 'alert');
\Lang::load('content', 'content');
\Lang::load('dashboard', 'dashboard');

// Execute bootstrap for each auto loaded module.
foreach(Module::loaded() as $module => $path) {
	if(File::exists($path. "bootstrap.php")){
		include_once($path. "bootstrap.php");
	}
}

