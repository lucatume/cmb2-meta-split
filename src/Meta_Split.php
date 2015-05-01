<?php


class CMB2_Meta_Split {

	/**
	 * @var static
	 */
	protected static $instance;

	/*
	 * @var string
	 */
	private $field_id;

	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function meta_save( $override, $args, $field_args, CMB2_Field $field ) {
		$args = (object) $args;
		if ( $args->type != 'post' ) {
			return;
		}
		$this->field_id = $args->field_id;
		if ( is_array( $args->value ) ) {
			$this->delete_post_meta( $args );
			$this->update_split_meta( $args->id, $args->value );
		}
	}

	public function meta_remove( $override, $args, $field_args, CMB2_Field $field ) {
		$args = (object) $args;
		if ( $args->type != 'post' ) {
			return;
		}
		$this->delete_post_meta( $args );
	}

	private function get_split_meta_key( $field_id ) {
		$meta_key = $field_id . '_split';

		return $meta_key;
	}

	private function update_split_meta( $id, $value ) {
		$meta_key = $this->get_split_meta_key( $this->field_id );
		foreach ( $value as $v ) {
			if ( is_array( $v ) ) {
				$this->update_sub_meta( $id, $v );
			} else {
				add_post_meta( $id, $meta_key, $v );
			}
		}
	}

	private function update_sub_meta( $id, array $v, $sub_key = '' ) {
		foreach ( $v as $key => $entry ) {
			if ( is_array( $entry ) ) {
				$this->update_sub_meta( $id, $entry, $key );
			} else {
				$sub_meta_key = $this->field_id . $sub_key . '_' . $key;
				add_post_meta( $id, $sub_meta_key, $entry );
			}
		}
	}

	private function delete_sub_meta( $id, $field_id ) {
		global $wpdb;
		$q = $wpdb->prepare( "DELETE pm.* FROM $wpdb->postmeta pm WHERE pm.post_id = $id AND pm.meta_key REGEXP %s ", '^' . $field_id );

		$wpdb->query( $q );
	}

	private function delete_post_meta( $args ) {
		delete_post_meta( $args->id, $this->get_split_meta_key( $args->field_id ) );
		$this->delete_sub_meta( $args->id, $args->field_id );
	}
}
