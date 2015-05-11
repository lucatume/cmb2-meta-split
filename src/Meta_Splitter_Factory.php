<?php


class CMB2_Meta_Splitter_Factory {

	public static function make( $object_type ) {
		Arg::_( $object_type, 'Object type' )->is_string()->assert( in_array( $object_type, array( 'user', 'post' ) ), 'Object type must be eithter of the user or the post type.' );

		// `post` to `CMB2_Post_Meta_Splitter`
		$class = sprintf( 'CMB2_' . ucfirst( $object_type ) . '_Meta_Splitter' );

		return new $class;
	}
}