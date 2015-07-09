<?php


class SavingTest extends \WP_UnitTestCase {

	protected $backupGlobals = false;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		activate_plugin( 'cmb2-meta-split/cmb2-meta-split.php' );
		tests_add_filter( 'cmb2_override_meta_save', array( CMB2_Meta_Split::instance(), 'meta_save' ), 100, 4 );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should allow normal saving
	 */
	public function it_should_allow_normal_saving() {
		$id = $this->factory->post->create();

		$args  = [
			'object_id'   => $id,
			'object_type' => 'post',
			'field_args'  => [
				'name' => __( 'A post field', 'cmb2' ),
				'id'   => 'test_meta_1',
				'type' => 'text'
			]
		];
		$field = new CMB2_Field( $args );

		$field->save_field( 'foo' );

		$this->assertEquals( 'foo', get_post_meta( $id, 'test_meta_1', true ) );
		$this->assertEmpty( get_post_meta( $id, 'test_meta_1_split' ) );
	}

	/**
	 * @test
	 * it should save repeatable fields in different db rows
	 */
	public function it_should_save_repeatable_fields_in_different_db_rows() {
		$id = $this->factory->post->create();

		$args  = [
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

		$meta_value = [ 'foo', 'baz', 'bar' ];
		$field->save_field( $meta_value );

		/** @var \wpdb $wpdb */
		global $wpdb;

		$saved = $wpdb->get_col( "select meta_value from $wpdb->postmeta where post_id = $id and meta_key = 'test_meta_2_split'" );

		$this->assertCount( 3, $saved );
		$this->assertEquals( $meta_value, $saved );

		$this->assertEquals( $meta_value, get_post_meta( $id, 'test_meta_2', true ) );
	}

	/**
	 * @test
	 * it should not split values that are not repeatable
	 */
	public function it_should_not_split_values_that_are_not_repeatable() {
		$id = $this->factory->post->create();

		$args  = [
			'object_id'   => $id,
			'object_type' => 'post',
			'field_args'  => [
				'name' => __( 'A post field', 'cmb2' ),
				'id'   => 'test_meta_3',
				'type' => 'text'
			]
		];
		$field = new CMB2_Field( $args );

		$meta_value = [ 'foo', 'baz', 'bar' ];
		$field->save_field( $meta_value );

		/** @var \wpdb $wpdb */
		global $wpdb;

		$saved = $wpdb->get_var( "select meta_value from $wpdb->postmeta where post_id = $id and meta_key = 'test_meta_3'" );

		$this->assertEquals( maybe_serialize( $meta_value ), $saved );
		$this->assertEmpty( get_post_meta( $id, 'test_meta_3_split' ) );
	}

}