<?php
spl_autoload_register( 'CMB2_Meta_autoload' );
function CMB2_Meta_autoload( $class ) {
	$map = array(
		'CMB2_Abstract_Meta_Splitter' => '/src/Abstract_Meta_Splitter.php',
		'CMB2_Meta_Split'            => '/src/Meta_Split.php',
		'CMB2_Meta_Splitter_Factory' => '/src/Meta_Splitter_Factory.php',
		'CMB2_Post_Meta_Splitter'    => '/src/Post_Meta_Splitter.php',
		'CMB2_User_Meta_Splitter'    => '/src/User_Meta_Splitter.php',
	);
	if ( isset( $map[ $class ] ) && file_exists( $file = dirname( __FILE__ ) . $map[ $class ] ) ) {
		include $file;
	}
}