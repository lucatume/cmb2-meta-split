<?php


class CMB2_Meta_Split {

	/**
	 * @var CMB2_Meta_Split
	 */
	protected static $instance;

	/**
	 * @return CMB2_Meta_Split
	 */
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
	 */
	public function meta_save( $override, array $args, array  $field_args ) {
		if ( ! $this->applies( $args, $field_args ) ) {
			return $override;
		}

		if ( ! is_array( $args['value'] ) ) {
			return $override;
		}

		$args['group'] = $field_args['type'] == 'group' ? true : false;

		$this->delete_all_meta( $args );
		$this->update_sub_meta( $args, $args['id'], $args['value'] );

		return $override;
	}

	/**
	 * Whether the splitting applies or not.
	 *
	 * @param array $args
	 * @param array $field_args
	 *
	 * @return bool
	 */
	protected function applies( array $args, array  $field_args ) {
		$group_or_repeatable = $args['repeat'] || ( $field_args['type'] == 'group' );
		if ( ! $group_or_repeatable ) {
			return false;
		}

		if ( ! in_array( $args['type'], array( 'post', 'user' ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Adds the meta for the object.
	 *
	 * @param            $type
	 * @param            $id
	 * @param            $meta_key
	 * @param            $meta_value
	 * @param bool|false $group
	 */
	protected function add_object_meta( $type, $id, $meta_key, $meta_value, $group = false ) {
		$prefix = $group ? '' : $this->get_split_postfix();
		add_metadata( $type, $id, $meta_key . $prefix, $meta_value );
	}

	/**
	 * Updates the object meta taking care of recursing over group.
	 *
	 * @param            $args
	 * @param null       $id
	 * @param array|null $v
	 * @param string     $sub_key
	 */
	protected function update_sub_meta( $args, $id = null, array $v = null, $sub_key = '' ) {
		$id      = empty( $id ) ? $args['id'] : $id;
		$v       = empty( $v ) ? $args['value'] : $v;
		$sub_key = is_numeric( $sub_key ) ? '' : $sub_key;

		foreach ( $v as $key => $entry ) {
			if ( is_array( $entry ) ) {
				$this->update_sub_meta( $args, $id, $entry, $key );
			} else {
				$postfix      = is_numeric( $key ) ? '' : $sub_key . '_' . $key;
				$sub_meta_key = $args['field_id'] . $postfix;
				$this->add_object_meta( $args['type'], $id, $sub_meta_key, $entry, $args['group'] );
			}
		}
	}

	/**
	 * Deletes all the meta and the auxiliary split meta for an object.
	 *
	 * @param $args
	 */
	protected function delete_all_meta( $args ) {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$table  = $wpdb->{$args['type'] . 'meta'};
		$id_key = $args['type'] . '_id';
		$q      = $wpdb->prepare( "DELETE t.* FROM $table t WHERE t.$id_key = %d AND t.meta_key REGEXP %s ", $args['id'], '^' . $args['field_id'] );

		$wpdb->query( $q );
	}

	/**
	 * Removes all the meta and auxiliary meta for an object.
	 *
	 * @param       $override
	 * @param array $args
	 * @param array $field_args
	 *
	 * @return null
	 */
	public function meta_remove( $override, array $args, array $field_args ) {
		if ( ! $this->applies( $args, $field_args ) ) {
			return $override;
		}
		$this->delete_all_meta( $args );

		return null;
	}

	/**
	 * Retrieves the postfix that's appended to split meta.
	 *
	 * @return string
	 */
	private function get_split_postfix() {
		return apply_filters( 'cmb2_meta_split_postfix', '_split' );
	}
}
