<?php error_log("Start function PHP"); 
add_theme_support( 'post-thumbnails' );
register_post_type('pippo',
array(
	'labels'      => array(
		'name'          => __('pippi', 'textdomain'),
		'singular_name' => __('pippo', 'textdomain'),
	),
	'public'      => true,
	'has_archive' => true,
	'supports' => array('title','excerpt'), // metabox
	// "rewrite"  => [ "slug" => "pippo", "with_front" => true ],
	// "capability_type"       => [ "pippo", "pippi" ],
	
	)
);
function registraPost(){
	register_post_type('complete-post',
	array(
		'labels'=> array(
			'name'=> __('Complete posts','textdomain'),
			'singular_name'=> __('complete-post-singolo','textdomain'),
		),
		'public'=> true,
		'has_archive' => true,
		'hierarchical'=> false,
		'supports' => array('title','editor','thumbnail', 'author',  'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats','page-attributes'),
		'taxonomies' => array('post_tag','categoria_custom'),
		'show_ui' => true,
		)
		
	);
}
add_action('init', 'registraPost');
//add_post_type_support( 'complete-post', 'comments' );

//register_taxonomy_for_object_type('post_tag', 'complete-post');

function registraTaxonomy(){
$labels = [
	'name' => _x( 'Categoria2', 'taxonomy general name' ),
	'singular_name' => _x( 'Categoria2', 'taxonomy singular name' ),
	'search_items' => __( 'Search Categoria2' ),
	'all_items' => __( 'All Categoria2' ),
	'parent_item' => __( 'Parent Categoria2' ),
	'parent_item_colon' => __( 'Parent Categoria2:' ),
	'edit_item' => __( 'Edit Categoria2' ),
	'update_item' => __( 'Update Categoria2' ),
	'add_new_item' => __( 'Add New Categoria2' ),
	'new_item_name' => __( 'New Categoria2 Name' ),
	'menu_name' => __( 'Categoria2' ),
];  
	register_taxonomy('categoria_custom',
			array(
				'complete-post',
	
			),array(
				'hierarchical' =>true,
				'labels' =>$labels,
				'show_ui' =>true,
				'show_admin_column' =>true,
				'query_var' =>true,
				'rewrite' =>array( 'slug' => 'categoria_custom' ),
	));
}
add_action('init', 'registraTaxonomy');

function preSalvataggio($postID){
	
	$postObj = get_post($postID);

	error_log(get_the_title($postID));

	
}
add_action('save_post','preSalvataggio');