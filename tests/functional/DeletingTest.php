<?php


class DeletingTest extends \WP_UnitTestCase {

	protected $backupGlobals = false;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		activate_plugin( 'cmb2-meta-split/cmb2-meta-split.php' );
		tests_add_filter( 'cmb2_override_meta_remove', array( CMB2_Meta_Split::instance(), 'meta_remove' ), 100, 4 );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should allow meta deletion normally
	 */
	public function it_should_allow_meta_deletion_normally() {
		$id = $this->factory->post->create();

		$args = [
			'object_id'   => $id,
			'object_type' => 'post',
			'field_args'  => [
				'name' => __( 'A post field', 'cmb2' ),
				'id'   => 'test_meta_1',
				'type' => 'text'
			]
		];

		$field = new CMB2_Field( $args );

		add_post_meta( $id, 'test_meta_1', 'foo' );

		$field->remove_data();

		$this->assertEmpty( get_post_meta( $id, 'test_meta_1' ) );
	}

	/**
	 * @test
	 * it should delete accessory split meta
	 */
	public function it_should_delete_accessory_split_meta() {
		$id = $this->factory->post->create();

		$args = [
			'object_id'   => $id,
			'object_type' => 'post',
			'field_args'  => [
				'name'       => __( 'A post field', 'cmb2' ),
				'id'         => 'test_meta_2',
				'type'       => 'text',
				'repeatable' => true
			]
		];

		$field = new CMB2_Field( $args );

		add_post_meta( $id, 'test_meta_2_split', 'foo' );
		add_post_meta( $id, 'test_meta_2_split', 'baz' );
		add_post_meta( $id, 'test_meta_2_split', 'bar' );

		$field->remove_data();

		$this->assertEquals( [ ], get_post_meta( $id, 'test_meta_2_split' ) );
	}

	/**
	 * @test
	 * it should delete group accessory meta
	 */
	public function it_should_delete_group_accessory_meta() {
		$id = $this->factory->post->create();

		$args = [
			'object_id'   => $id,
			'object_type' => 'post',
			'field_args'  => [
				'name' => __( 'A post field', 'cmb2' ),
				'id'   => 'test_meta_3',
				'type' => 'group',
			]
		];

		$field = new CMB2_Field( $args );

		add_post_meta( $id, 'test_meta_3_name_split', 'John' );
		add_post_meta( $id, 'test_meta_3_address_split', 'Doe' );
		add_post_meta( $id, 'test_meta_3_name_split', 'Jane' );
		add_post_meta( $id, 'test_meta_3_address_split', 'Dean' );

		$field->remove_data();

		$this->assertEquals( [ ], get_post_meta( $id, 'test_meta_3_name_split' ) );
		$this->assertEquals( [ ], get_post_meta( $id, 'test_meta_3_address_split' ) );
	}
}