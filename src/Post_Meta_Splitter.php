<?php
class CMB2_Post_Meta_Splitter extends CMB2_Abstract_Meta_Splitter{

	/**
	 * @param $id
	 * @param $meta_key
	 * @param $v
	 */
	protected function add_object_meta( $id, $meta_key, $v ) {
		add_post_meta( $id, $meta_key, $v );
	}

	/**
	 * @param $args
	 */
	protected function delete_object_meta( $args ) {
		delete_meta( $args->id, $this->get_split_meta_key( $args->field_id ) );
	}

	/**
	 * @param $id
	 * @param $field_id
	 */
	protected function delete_sub_meta( $id, $field_id ) {
		global $wpdb;
		$q = $wpdb->prepare( "DELETE pm.* FROM $wpdb->postmeta pm WHERE pm.post_id = $id AND pm.meta_key REGEXP %s ", '^' . $field_id );

		$wpdb->query( $q );
	}
}