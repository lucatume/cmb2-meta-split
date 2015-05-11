<?php


class CMB2_User_Meta_Splitter extends CMB2_Abstract_Meta_Splitter {

	/**
	 * @param $id
	 * @param $meta_key
	 * @param $v
	 */
	protected function add_object_meta( $id, $meta_key, $v ) {
		add_user_meta( $id, $meta_key, $v );
	}

	/**
	 * @param $args
	 */
	protected function delete_object_meta( $args ) {
		delete_user_meta( $args->id, $this->get_split_meta_key( $args->field_id ) );
	}

	/**
	 * @param $id
	 * @param $field_id
	 */
	protected function delete_sub_meta( $id, $field_id ) {
		global $wpdb;
		$q = $wpdb->prepare( "DELETE um.* FROM $wpdb->usermeta um WHERE um.user_id = $id AND um.meta_key REGEXP %s ", '^' . $field_id );

		$wpdb->query( $q );
	}
}