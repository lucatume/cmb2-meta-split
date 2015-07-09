<?php
/**
 * Plugin Name: CMB2 Meta Split
 * Plugin URI: http://theAverageDev.com
 * Description: Saves grouped meta values separately.
 * Version: 1.0
 * Author: theAverageDev
 * Author URI: http://theAverageDev.com
 * License: GPL 2.0
 */

include 'src/Meta_Split.php';

add_filter( 'cmb2_override_meta_save', array( CMB2_Meta_Split::instance(), 'meta_save' ), 100, 3 );
add_filter( 'cmb2_override_meta_remove', array( CMB2_Meta_Split::instance(), 'meta_remove' ), 100, 3 );

//include 'sample-functions.php';
