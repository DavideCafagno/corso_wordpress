<?php
/*
Template Name: listcat
*/
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_uri()); ?>" type="text/css" />
    <?php wp_head(); ?>
</head>

<body>
<h1><?php bloginfo('name'); ?></h1>
<h2><?php bloginfo('description'); ?></h2>

<?php

echo "Ciao da template-listacat";
?>
<h3>Category</h3>
<?php
wp_list_categories();
?>
<h3>Taxonomies</h3>
<?php
foreach (get_terms('categoria_custom')as $t){
    echo $t-> name;
    ?>
    : <a href="<?php echo get_term_link($t->term_id); ?>"><?php echo $t->slug; ?><br>
    </a>
    <?php
}

    echo the_posts_pagination();?>


<?php wp_footer();
get_footer();
?>
</body>

</html><?php
