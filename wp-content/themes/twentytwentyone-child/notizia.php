<table class="table" style="width: 500px; margin: 20px auto;">
    <tr class="row">
        <td class="col col-6">Titolo</td>
        <td class="col col-6"><?php if (is_single()) {
                the_title();
            } else {
                echo "<a href=" . get_the_permalink() . ">" . get_the_title() . "</a>";
            } ?></td>
    </tr>
    <tr class="row">
        <td class="col col-12" style="text-align: center"><img style="max-width: 400px;" src="<?php echo the_post_thumbnail_url(); ?>" ></td>
    </tr>
    <tr class="row">
        <td class="col col-6">Riassunto</td>
        <td class="col col-6"><?php the_excerpt(); ?></td>
    </tr>
    <tr class="row">
        <td class="col col-6">Contenuto</td>
        <td class="col col-6"><?php the_content(); ?></td>
    </tr>

</table>
