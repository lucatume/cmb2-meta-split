<?php


abstract class CMB2_Abstract_Meta_Splitter {

	/**
	 * @var string
	 */
	protected $field_id;

	/**
	 * @param $field_id
	 */
	public function set_field_id( $field_id ) {
		Arg::_( $field_id, 'Field id' )->is_string();

		$this->field_id = $field_id;
	}

	protected function get_split_meta_key( $field_id ) {
		$meta_key = $field_id . '_split';

		return $meta_key;
	}

	public function update_split_meta( $id, $value ) {
		Arg::_( $id, 'Id' )->is_numeric();

		$meta_key = $this->get_split_meta_key( $this->field_id );
		foreach ( $value as $v ) {
			if ( is_array( $v ) ) {
				$this->update_sub_meta( $id, $v );
			} else {
				$this->add_object_meta( $id, $meta_key, $v );
			}
		}
	}

	protected function update_sub_meta( $id, array $v, $sub_key = '' ) {
		foreach ( $v as $key => $entry ) {
			if ( is_array( $entry ) ) {
				$this->update_sub_meta( $id, $entry, $key );
			} else {
				$sub_meta_key = $this->field_id . $sub_key . '_' . $key;
				$this->add_object_meta( $id, $sub_meta_key, $entry );
			}
		}
	}

	public function delete_meta( $args ) {
		Arg::_( $args, 'Field arguments' )->is_object()->is_set('id','field_id');

		$this->delete_object_meta( $args );
		$this->delete_sub_meta( $args->id, $args->field_id );
	}


	/**
	 * @param $id
	 * @param $meta_key
	 * @param $v
	 */
	abstract protected function add_object_meta( $id, $meta_key, $v );

	/**
	 * @param $args
	 */
	abstract protected function delete_object_meta( $args );

	/**
	 * @param $id
	 * @param $field_id
	 *
	 * @return mixed
	 */
	abstract protected function delete_sub_meta( $id, $field_id );
}