<head>
<?php
//    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">';
wp_head();
?>
</head>
<h1><?php echo  get_the_title()?></h1>
<?php  get_template_part('templates/searchbar'); ?>