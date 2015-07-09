<?php
// This is global bootstrap for autoloading

// Functions
function find_in_ancestors( $path, $file = __FILE__ ) {
	$dir  = dirname( $file );
	$path = ltrim( $path, '/' );
	while ( ! file_exists( $found = $dir . DIRECTORY_SEPARATOR . $path ) ) {
		if ( $dir == DIRECTORY_SEPARATOR ) {
			return false;
		}
		$dir = dirname( $dir );
	}

	return $found;
}

$includes = find_in_ancestors( '/cmb2/includes' ) ?: find_in_ancestors( '/CMB2/includes' );
require_once $includes . DIRECTORY_SEPARATOR . '/helper-functions.php';
spl_autoload_register( function ( $class ) use ( $includes ) {
	if ( strpos( $class, 'CMB2_' ) === 0 &&  file_exists($path = $includes . DIRECTORY_SEPARATOR . $class . '.php') ) {
		require $path;
	}
} );
