<?php //setup_postdata($post);

?>
<table style='width: fit-content' >

    <tr>

        <?php if (!is_singular()) { ?>
            <td><a href="<?php the_permalink() ?>"> <?php the_title() ?></a></td>
        <?php } else { ?>
            <td><?php the_title() ?></td>
        <?php } ?>
    </tr>
    <tr>
        <td><?php the_modified_date() ?></td>
    </tr>

    <tr>
        <td><?php echo get_the_author() ?></td>
    </tr>

    <tr>

        <td><?php
            $string = "";
            foreach (get_post_custom_keys() as $e) {
                if (strncmp($e, "_", 1) != 0) {
                    $string .= (Ucwords($e) . ": " . get_post_meta(get_the_ID(), $e, true) . "<br>");
                }
            }
            if ($string !== "") {
                echo $string;
            } else echo "No Custom Fields";

            // echo get_post_meta(get_the_ID(),'Campo-Custom-1', true);

            ?></td>
    </tr>
    <?php if(current_user_can('administrator')) :?>
    <tr>
        <td><?php edit_post_link(); ?></td>
    </tr>
    <?php else: endif; ?>

</table>

<?php //comments_template('comments.php', true); ?>
