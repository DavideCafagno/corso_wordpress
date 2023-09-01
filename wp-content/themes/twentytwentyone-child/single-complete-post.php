
<style>
    td{
        padding:10px;
    }
</style>
<div style='border:solid 1px black; padding : 20px; margin:20px;'>
    <table>
        <tr>
            <td>Titolo</td>
            <td><?php the_title() ?></td>
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
                <?php if(has_post_thumbnail()):
                ?>
                <img src='<?php echo get_the_post_thumbnail_url() ?>' style='width : 100px; height:100px;' />
                <?php else : echo 'No image'; endif;?>
            </td>
        </tr>
        <tr>
            <td>Trackbacks</td>
            <td><?php echo get_trackback_url() ?></td>
        </tr>
        <tr>
            <td>Custom fields</td>
            <td><?php

            foreach(get_post_custom_keys() as $e){
                if(strncmp($e,"_",1) != 0){
                    print_r($e.": ".get_post_meta(get_the_ID(),$e, true)."<br>");
                }
            }
            
           // echo get_post_meta(get_the_ID(),'Campo-Custom-1', true);
            
            ?></td>
        </tr>
        <tr>
            <td>Comments</td>
            <td><?php if(null !== get_comments()){
                foreach(get_comments() as $ele){
                    echo  $ele ->comment_author.":    " .$ele -> comment_content . "<br>";
                }
            }else{
                echo 'No comment';
            } ?></td>
        </tr>
        <tr>
            <td>Revision</td>
            <td><?php  if( null !== wp_get_post_revisions()){
                foreach(wp_get_post_revisions() as $ele){
                    echo  $ele -> post_date . "<br>";
                }

            } ?></td>
        </tr>
        <tr>
            <td>Post-formats</td>
            <td><?php if(has_post_format()) 
                            echo  Ucwords(get_post_format(),1);
                       else echo "Nessun Formato";
                ?>
            </td>
        </tr>
        <tr>
            <td>Page-attributes</td>
            <td><?php  print_r(((array)get_post())['menu_order']) ?></td>
        </tr>
        <tr>
            <td><button id="<?php get_the_ID()?>" data-ID="<?php get_the_ID()?>">Titolo</button></td>
        </tr> 
    </table>
</div>