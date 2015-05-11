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
		try {
			$args = (object) $args;

			// from the post type get the right handler class
			$object_type   = $args->type;
			$meta_splitter = $this->get_meta_splitter( $object_type );

			$meta_splitter->set_field_id( $args->field_id );
			if ( is_array( $args->value ) ) {
				$meta_splitter->delete_meta( $args );
				$meta_splitter->update_split_meta( $args->id, $args->value );
			}
		} catch ( Exception $e ) {
			$this->maybe_throw( $e );
		}
	}

	/**
	 * @param $object_type
	 *
	 * @return CMB2_Abstract_Meta_Splitter
	 */
	protected function get_meta_splitter( $object_type ) {
		$meta_splitter = CMB2_Meta_Splitter_Factory::make( $object_type );

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
		try {
			$args = (object) $args;

			$object_type   = $args->type;
			$meta_splitter = CMB2_Meta_Splitter_Factory::make( $object_type );

			$meta_splitter->delete_meta( $args );
		} catch ( Exception $e ) {
			$this->maybe_throw( $e );
		}
	}

	/**
	 * @param $exception
	 */
	private function maybe_throw( $exception ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			throw $exception;
		}
	}

}
