<?php


class CMB2_Meta_Split {

	/**
	 * @var CMB2_Meta_Split
	 */
	protected static $instance;

	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Saves the additional meta for the object.
	 *
	 * @param            $override
	 * @param            $args
	 * @param            $field_args
	 * @param CMB2_Field $field
	 */
	public function meta_save( $override, $args, $field_args, CMB2_Field $field ) {
		if ( ! is_array( $args['value'] ) || ! $args['repeat'] || $field_args['type'] == 'group' ) {
			return $override;
		}

		delete_metadata( $args['type'], $args['id'], $args['field_id'] );

		foreach ( $args['value'] as $val ) {
			add_metadata( $args['type'], $args['id'], $args['field_id'] . '_split', $val );
		}

		return $override;
	}

	/**
	 * @param $object_type
	 *
	 * @return CMB2_Abstract_Meta_Splitter
	 */
	protected function get_meta_splitter( $object_type ) {
		try {
			$meta_splitter = CMB2_Meta_Splitter_Factory::make( $object_type );
		} catch ( Exception $e ) {
			return false;
		}

		return $meta_splitter;
	}

	/**
	 * Removes the additional meta values created for the object.
	 *
	 * @param            $override
	 * @param            $args
	 * @param            $field_args
	 * @param CMB2_Field $field
	 */
	public function meta_remove( $override, $args, $field_args, CMB2_Field $field ) {
		$args = (object) $args;

		$object_type   = $args->type;
		$meta_splitter = $this->get_meta_splitter( $object_type );

		if ( ! $meta_splitter ) {
			return;
		}

		$meta_splitter->delete_meta( $args );
	}
}
