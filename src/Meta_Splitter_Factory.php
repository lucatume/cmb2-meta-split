<?php


class CMB2_Meta_Splitter_Factory {

	public static function make( $object_type ) {
		$supported_types = array( 'user', 'post' );
		Arg::_( $object_type, 'Object type' )->is_string()->assert( in_array( $object_type, $supported_types ), 'Object type must be eithter of the user or the post type.' );

		// `post` to `CMB2_Post_Meta_Splitter`
		$class = sprintf( 'CMB2_' . ucfirst( $object_type ) . '_Meta_Splitter' );

		return new $class;
	}
}