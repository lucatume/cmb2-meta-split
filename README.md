# CMB2 Meta Split
 *A [Custom Meta Boxes 2](https://github.com/webdevstudios/CMB2) plugin addition that will take care of saving non serialized meta for repeatable fields and groups*

## Caveat
Thes meta splitting will only apply to posts (of any type, not just `post` and `page`) and users. It will not apply to option pages.
 
## Installation
Download, copy and paste into WordPress plugin folder and activate; this plugin has no UI.
 
## Usage
The plugin exposes no template tags or functions and will simply save non serialized meta in the database.  
The code below will add a repeatable text field to the page, id `my_text`, and a group field with the id of `my_group`; any entry in the repeatable text field will be saved to the `my_text_split` meta while entries in the group field will be saved in the `my_group_title` and `my_group_description` meta.

	add_action( 'cmb2_init', 'yourprefix_register_repeatable_group_field_metabox' );
	/**
	 * Hook in and add a metabox to demonstrate repeatable grouped fields
	 */
	function yourprefix_register_repeatable_group_field_metabox() {
	
		/**
		 * Repeatable Field Groups
		 */
		$cmb_group = new_cmb2_box( array(
			'id'           => 'metabox',
			'title'        => __( 'Repeating Field Group', 'cmb2' ),
			'object_types' => array( 'page', ),
		) );
	
		$cmb_group->add_field( array(
			'name'       => __( 'Repeatable text', 'cmb2' ),
			'desc'       => __( 'field description (optional)', 'cmb2' ),
			'id'         => 'my_text',
			'type'       => 'text_small',
			'repeatable' => true,
			'on_front'   => false,
		) );
	
		$group_field_id = $cmb_group->add_field( array(
			'id'          => 'my_group',
			'type'        => 'group',
			'description' => __( 'Generates reusable form entries', 'cmb2' ),
			'options'     => array(
				'group_title'   => __( 'Entry {#}', 'cmb2' ),
				'add_button'    => __( 'Add Another Entry', 'cmb2' ),
				'remove_button' => __( 'Remove Entry', 'cmb2' ),
				'sortable'      => true
			),
		) );
	
		$cmb_group->add_group_field( $group_field_id, array(
			'name' => __( 'Entry Title', 'cmb2' ),
			'id'   => 'title',
			'type' => 'text',
		) );
	
		$cmb_group->add_group_field( $group_field_id, array(
			'name'        => __( 'Description', 'cmb2' ),
			'description' => __( 'Write a short description for this entry', 'cmb2' ),
			'id'          => 'description',
			'type'        => 'textarea_small',
		) );
	}
	
## To test or develop
Support for repeatable fields in group fields.
