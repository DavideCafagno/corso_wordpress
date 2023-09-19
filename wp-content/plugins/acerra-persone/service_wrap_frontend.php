<?php
include( "../../../wp-load.php" );

global $wpdb;

$draw            = $_POST['draw'];
$startRow        = $_POST['start'];
$rowsPerPage     = $_POST['length']; // Rows display per page
$columns         = $_POST['columns'];
$columnIndex     = $_POST['order'][0]['column']; // Column index
$columnName      = $_POST['columns'][ $columnIndex ]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchText      = $_POST['search']['value']; // Search value

$idOrganizzazione = $_POST['id_organizzazione'];

$nominativoRicerca = $_POST['nominativo'];
$ruoloRicerca      = $_POST['ruolo'];

$meta_query_args        = [];
$filter_query_args      = [];
$filter_meta_query_args = [];

$organizzazione_meta_query_args = [
	'type '   => 'NUMERIC',
	'key'     => 'organizzazione_di_riferimento',
	'value'   => $idOrganizzazione,
	'compare' => 'LIKE'
];

$meta_query_args['relation'] = 'AND';
$meta_query_args[]           = $organizzazione_meta_query_args;
$filter_meta_query_args      = $meta_query_args;

$allPostsFilteredCount = 0;

if ( ! empty( $nominativoRicerca ) || ! empty( $ruoloRicerca ) ) {
	$filter_query_args = [ 'relation' => 'OR' ];

	if ( ! empty( $nominativoRicerca ) ) {
		$filter_query_args[] = [
			'key'     => 'nome_e_cognome',
			'value'   => $nominativoRicerca,
			'compare' => 'LIKE'
		];
	}

	if ( ! empty( $ruoloRicerca ) ) {
		$filter_query_args[] = [
			'key'     => 'ruolo',
			'value'   => $ruoloRicerca,
			'compare' => 'LIKE'
		];
	}

	$filter_meta_query_args[] = $filter_query_args;

	$allPostsFilteredArgs = [
		'post_type'           => 'persona',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'meta_query'          => $filter_meta_query_args
	];

	$allPostsFilteredQuery = new WP_Query( $allPostsFilteredArgs );
	$allPostsFilteredCount = $allPostsFilteredQuery->found_posts;
}

$allPostsArgs = [
	'post_type'           => 'persona',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'meta_query'          => $meta_query_args
];

$allPostsQuery = new WP_Query( $allPostsArgs );
$allPostsCount = $allPostsQuery->found_posts;

$totalPages  = (int) ceil( $allPostsCount / $rowsPerPage );
$currentPage = ceil( ( $startRow - 1 ) / $rowsPerPage ) + 1;

$pagedArgs = [
	'post_type'              => 'persona',
	'post_status'            => 'publish',
	'posts_per_archive_page' => $rowsPerPage,
	'paged'                  => $currentPage,
	'ignore_sticky_posts'    => true,
	'meta_query'             => $filter_meta_query_args,

	//ORDER BY META-VALUE
	'meta_key'               => $columnName,
	'order'                  => $columnSortOrder
];

$pagedQuery = new WP_Query( $pagedArgs );

$responseData = [];
$pagedPosts   = $pagedQuery->get_posts();

foreach ( $pagedPosts as $pagedPost ) {
	$currentCol = [];

	foreach ( $columns as $column ) {
		$fieldName = $column['data'];

		if ( $fieldName === 'link_dettaglio' ) {
			$currentCol[ $fieldName ] = [
				'title'     => get_field( "nome_e_cognome", $pagedPost->ID ),
				'permalink' => get_post_permalink( $pagedPost )
			];
		} else if ( $fieldName === 'organizzazione_di_riferimento' ) {
			$org     = get_field( "organizzazione_di_riferimento", $pagedPost->ID )[0];
			$orgId   = $org->ID;
			$orgName = get_field( "nome_titolo", $orgId );
			$orgULR  = get_post_permalink( $org );

			$currentCol[ $fieldName ] = [
				'title'     => $orgName,
				'permalink' => $orgULR
			];
		} else if ( $fieldName === 'responsabile_di' ) {
			$respDi     = get_field( "responsabile_di", $pagedPost->ID )[0];
			$respDiId   = $respDi->ID;
			$respDiName = get_field( "nome_titolo", $respDiId );
			$respDiULR  = get_post_permalink( $respDi );

			$currentCol[ $fieldName ] = [
				'title'     => $respDiName,
				'permalink' => $respDiULR
			];
		} else {
			$currentCol[ $fieldName ] = get_post_meta( $pagedPost->ID, $fieldName );
		}
	}

	$responseData[] = $currentCol;
}


$response = [];

$response['data']         = $responseData;
$response['draw']         = (int) $draw;
$response['recordsTotal'] = $allPostsCount;

if ( ! empty( $filter_query_args ) ) {
	$response['recordsFiltered'] = $allPostsFilteredCount;
} else {
	$response['recordsFiltered'] = $allPostsCount;
}

echo json_encode( $response );