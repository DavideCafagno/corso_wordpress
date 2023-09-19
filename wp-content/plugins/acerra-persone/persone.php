<?php
/*
Plugin Name: Acerra Persone
Plugin URI: https://wordpress.org/plugins/
Description: Acerra persone
Version: 1.0
Author: Step
License: GPL Attribution-ShareAlike
*/

function cptui_register_my_cpts_persona() {

	/**
	 * Post Type: Persone.
	 */

	$labels = [
		"name"                     => __( "Persone", "design-italia-child" ),
		"singular_name"            => __( "Persona", "design-italia-child" ),
		"menu_name"                => __( "My Persone", "design-italia-child" ),
		"all_items"                => __( "All Persone", "design-italia-child" ),
		"add_new"                  => __( "Add new", "design-italia-child" ),
		"add_new_item"             => __( "Add new persona", "design-italia-child" ),
		"edit_item"                => __( "Edit persona", "design-italia-child" ),
		"new_item"                 => __( "New persona", "design-italia-child" ),
		"view_item"                => __( "View persona", "design-italia-child" ),
		"view_items"               => __( "View Persone", "design-italia-child" ),
		"search_items"             => __( "Search Persone", "design-italia-child" ),
		"not_found"                => __( "No Persone found", "design-italia-child" ),
		"not_found_in_trash"       => __( "No Persone found in trash", "design-italia-child" ),
		"parent"                   => __( "Parent persona:", "design-italia-child" ),
		"featured_image"           => __( "Featured image for this persona", "design-italia-child" ),
		"set_featured_image"       => __( "Set featured image for this persona", "design-italia-child" ),
		"remove_featured_image"    => __( "Remove featured image for this persona", "design-italia-child" ),
		"use_featured_image"       => __( "Use as featured image for this persona", "design-italia-child" ),
		"archives"                 => __( "persona archives", "design-italia-child" ),
		"insert_into_item"         => __( "Insert into persona", "design-italia-child" ),
		"uploaded_to_this_item"    => __( "Upload to this persona", "design-italia-child" ),
		"filter_items_list"        => __( "Filter Persone list", "design-italia-child" ),
		"items_list_navigation"    => __( "Persone list navigation", "design-italia-child" ),
		"items_list"               => __( "Persone list", "design-italia-child" ),
		"attributes"               => __( "Persone attributes", "design-italia-child" ),
		"name_admin_bar"           => __( "persona", "design-italia-child" ),
		"item_published"           => __( "persona published", "design-italia-child" ),
		"item_published_privately" => __( "persona published privately.", "design-italia-child" ),
		"item_reverted_to_draft"   => __( "persona reverted to draft.", "design-italia-child" ),
		"item_scheduled"           => __( "persona scheduled", "design-italia-child" ),
		"item_updated"             => __( "persona updated.", "design-italia-child" ),
		"parent_item_colon"        => __( "Parent persona:", "design-italia-child" ),
	];

	$args = [
		"label"                 => __( "Persone", "design-italia-child" ),
		"labels"                => $labels,
		"description"           => "",
		"public"                => true,
		"publicly_queryable"    => true,
		"show_ui"               => true,
		"show_in_rest"          => false,
		"rest_base"             => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace"        => "wp/v2",
		"has_archive"           => false, // "archive_persone",
		"show_in_menu"          => true,
		"show_in_nav_menus"     => true,
		"delete_with_user"      => false,
		"exclude_from_search"   => false,
		"capability_type"       => [ "persona", "persone" ],
		"map_meta_cap"          => true,
		"hierarchical"          => false,
		"can_export"            => false,
		"rewrite"               => [ "slug" => "persona", "with_front" => true ],
//		'rewrite'               => array( 'pages' => true, 'with_front' => false ),
		"query_var"             => true,
		"menu_icon"             => "dashicons-admin-users",
//		"register_meta_box_cb"  => "callback_metabox_persona",
		"supports"              => [ "title", "excerpt", "custom-fields", "revisions" ],
		"show_in_graphql"       => false,
		'taxonomies'            => [ 'post_tag', 'tipologia_persona' ]
	];

	register_post_type( "persona", $args );
}

add_action( 'init', 'cptui_register_my_cpts_persona' );

function cptui_register_my_taxes_tipologia_persona() {

	/**
	 * Taxonomy: Tipologie persone.
	 */

	$labels = [
		"name"                       => __( "Tipologie persone", "design-italia-child" ),
		"singular_name"              => __( "Tipologia persona", "design-italia-child" ),
		"menu_name"                  => __( "Tipologie persone", "design-italia-child" ),
		"all_items"                  => __( "All Tipologie persone", "design-italia-child" ),
		"edit_item"                  => __( "Edit Tipologia persona", "design-italia-child" ),
		"view_item"                  => __( "View Tipologia persona", "design-italia-child" ),
		"update_item"                => __( "Update Tipologia persona name", "design-italia-child" ),
		"add_new_item"               => __( "Add new Tipologia persona", "design-italia-child" ),
		"new_item_name"              => __( "New Tipologia persona name", "design-italia-child" ),
		"parent_item"                => __( "Parent Tipologia persona", "design-italia-child" ),
		"parent_item_colon"          => __( "Parent Tipologia persona:", "design-italia-child" ),
		"search_items"               => __( "Search Tipologie persone", "design-italia-child" ),
		"popular_items"              => __( "Popular Tipologie persone", "design-italia-child" ),
		"separate_items_with_commas" => __( "Separate Tipologie persone with commas", "design-italia-child" ),
		"add_or_remove_items"        => __( "Add or remove Tipologie persone", "design-italia-child" ),
		"choose_from_most_used"      => __( "Choose from the most used Tipologie persone", "design-italia-child" ),
		"not_found"                  => __( "No Tipologie persone found", "design-italia-child" ),
		"no_terms"                   => __( "No Tipologie persone", "design-italia-child" ),
		"items_list_navigation"      => __( "Tipologie persone list navigation", "design-italia-child" ),
		"items_list"                 => __( "Tipologie persone list", "design-italia-child" ),
		"back_to_items"              => __( "Back to Tipologie persone", "design-italia-child" ),
		"name_field_description"     => __( "The name is how it appears on your site.", "design-italia-child" ),
		"parent_field_description"   => __( "Assign a parent term to create a hierarchy. The term Jazz, for example, would be the parent of Bebop and Big Band.", "design-italia-child" ),
		"slug_field_description"     => __( "The slug is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.", "design-italia-child" ),
		"desc_field_description"     => __( "The description is not prominent by default; however, some themes may show it.", "design-italia-child" ),
	];


	$args = [
		"label"                 => __( "Tipologie persone", "design-italia-child" ),
		"labels"                => $labels,
		"public"                => true,
		"publicly_queryable"    => true,
		"hierarchical"          => true,
		"show_ui"               => true,
		"show_in_menu"          => true,
		"show_in_nav_menus"     => true,
		"query_var"             => true,
		"rewrite"               => [
			'slug'         => 'tipologia_persona',
			'with_front'   => true,
			'hierarchical' => true,
		],
		"show_admin_column"     => true,
		"show_in_rest"          => false,
		"show_tagcloud"         => false,
		"rest_base"             => "tipologia_persona",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace"        => "wp/v2",
		"show_in_quick_edit"    => false,
		"sort"                  => false,
		"show_in_graphql"       => false,
	];
	register_taxonomy( "tipologia_persona", [ "persona" ], $args );

	$tipologieIniziali = [
		"Politica",
		"Amministrativa"
	];

	foreach ( $tipologieIniziali as $tipologia ) {
		if ( ! term_exists( $tipologia, 'tipologia_persona' ) ) {
			wp_insert_term( $tipologia, 'tipologia_persona' );
		}
	}

	$tagsIniziali = [
		"anziano",
		"fanciullo",
		"giovane",
		"famiglia",
		"studente",
		"associazione",
		"istruzione",
		"abitazione",
		"animale domestico",
		"integrazione sociale",
		"protezione sociale",
		"programma d'azione",
		"comunicazione",
		"edificio urbano",
		"urbanistica ed edilizia",
		"formazione professionale",
		"acquisizione di conoscenza",
		"condizioni e organizzazione del lavoro",
		"trasporto",
		"matrimonio",
		"procedura elettorale e voto",
		"tempo libero",
		"cultura",
		"immigrazione",
		"inquinamento",
		"area di parcheggio",
		"traffico urbano",
		"acqua",
		"gestione dei rifiuti",
		"salute",
		"sicurezza pubblica",
		"sicurezza internazionale",
		"spazio verde",
		"sport",
		"trasporto stradale",
		"turismo",
		"energia",
		"informatica e trattamento dei dati",
	];

	foreach ( $tagsIniziali as $tag ) {
		if ( ! term_exists( $tag, 'post_tag' ) ) {
			wp_insert_term( $tag, 'post_tag' );
		}
	}
}

add_action( 'init', 'cptui_register_my_taxes_tipologia_persona' );


//function callback_metabox_persona( $post ) {
//
//	// get the current post type
//	$post_type = get_post_type( $post );
//
//	if ($post_type === "persona") {
//
//	}
//
//	// your logic for the add_meta_box code
//	// ...
//
//}

add_action( 'admin_enqueue_scripts', function () {
	wp_register_script( 'searchTaxonomyGT_ap_js',
		plugins_url( '/includes/ap_searchTaxonomyGT.js', __FILE__ ) );
	wp_enqueue_script( 'searchTaxonomyGT_ap_js' );
} );

add_action( 'restrict_manage_posts', function () {
	global $typenow;
	$taxonomy = 'tipologia_persona';

	if ( $typenow === 'persona' ) {
		$filters = array( $taxonomy );
		foreach ( $filters as $tax_slug ) {
			$tax_obj  = get_taxonomy( $tax_slug );
			$tax_name = $tax_obj->labels->name;
			$terms    = get_terms( $tax_slug );
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Tutte le persone</option>";
			foreach ( $terms as $term ) {
				$label = ( isset( $_GET[ $tax_slug ] ) ) ? $_GET[ $tax_slug ] : '';
				echo '<option value=' . $term->slug, $label === $term->slug ? ' selected="selected"' : '', '>' . $term->name . ' (' . $term->count . ')</option>';
			}
			echo "</select>";
		}
	}
} );


function persone_shtc( $atts ) {
	ob_start();
	include( plugin_dir_path( __FILE__ ) . 'shortcode.php' );
	$atshortcode = ob_get_clean();

	return $atshortcode;
}

add_shortcode( 'persone', 'persone_shtc' );