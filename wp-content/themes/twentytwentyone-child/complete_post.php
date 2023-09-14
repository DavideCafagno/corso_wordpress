<?php //setup_postdata($post);

?>
<table  >

    <tr>
        <td>Titolo</td>
        <?php if (!is_singular()) { ?>
            <td><a href="<?php the_permalink() ?>"> <?php the_title() ?></a></td>
        <?php } else { ?>
            <td><?php the_title() ?></td>
        <?php } ?>
    </tr>
    <tr>
        <td>Data di pubblicaione</td>
        <td><?php the_modified_date() ?></td>
    </tr>
    <tr>
        <td>Descrizione</td>
        <td><?php the_content() ?></td>
    </tr>
    <tr>
        <td>Riassunto</td>
        <td><?php the_excerpt() ?></td>
    </tr>
    <tr>
        <td>Autore</td>
        <td><?php the_author() ?></td>
    </tr>
    <tr>
        <td>Immagine</td>
        <td>
            <?php if (has_post_thumbnail()):
                ?>
                <img src='<?php echo get_the_post_thumbnail_url() ?>' style='width : 100px; height:100px;'/>
            <?php else : echo 'No image'; endif; ?>
        </td>
    </tr>
    <tr>
        <td>Trackbacks</td>
        <td><?php echo get_trackback_url() ?></td>
    </tr>
    <tr>
        <td>Custom fields</td>
        <td><?php
            $string = "";
            foreach (get_post_custom_keys() as $e) {
                if (strncmp($e, "_", 1) != 0) {
                    $string .= ($e . ": " . get_post_meta(get_the_ID(), $e, true) . "<br>");
                }
            }
            if ($string !== "") {
                echo $string;
            } else echo "No Custom Fields";

            // echo get_post_meta(get_the_ID(),'Campo-Custom-1', true);

            ?></td>
    </tr>
    <tr>
        <td>Comments</td>
        <td><?php
            $commentts = get_comments(array('post_id' => get_the_ID()));
            if (!empty($commentts)) {
                foreach ($commentts as $ele) {
                    echo $ele->comment_author . ":    " . $ele->comment_content . "<br>";
                }
            } else {
                echo 'No comment';
            } ?></td>
    </tr>
    <tr>
        <td>Revision</td>
        <td><?php if (null !== wp_get_post_revisions()) {
                foreach (wp_get_post_revisions() as $ele) {
                    echo $ele->post_date . "<br>";
                }

            } else {
                echo "No revisions";
            } ?></td>
    </tr>
    <tr>
        <td>Post-formats</td>
        <td><?php if (has_post_format())
                echo Ucwords(get_post_format(), 1);
            else echo "Nessun Formato";
            ?>
        </td>
    </tr>
    <tr>
        <td>Page-attributes</td>
        <td><?php print_r(((array)get_post())['menu_order']) ?></td>
    </tr>
    <tr>
        <td>Tipo di post da contare</td>
        <td><input id="inputTipoPost" type="text" placeholder="Complete-post / pippo"></td>
    </tr>
    <tr>
        <?php
        $nonce = wp_create_nonce('retrive_permalink');
        $myID = get_the_ID();
        $url = admin_url('admin-ajax.php?action=retrive_permalink&post_id_ajax=' . $myID . '&nonce_ajax=' . $nonce);
        //$link = admin_url('admin-ajax.php?action=my_user_like&post_id='.$post->ID.'&nonce='.$nonce);
        ?>
        <td colspan="2">
            <button data_id="<?php the_ID() ?>" data_nonce="<?php echo $nonce; ?>"
                    onclick="getAlert()">Conta post
            </button><br>
            <a href="<?php echo $url; ?>">Clicca qui</a>
        </td>
    </tr>

</table>

<?php //comments_template('comments.php', true); ?>
