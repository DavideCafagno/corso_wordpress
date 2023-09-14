<html>

<?php
/*
Template Name: insert_post
*/
wp_head();
?>
<h5><?php bloginfo('name'); ?> </h5>
<h1><?php the_title(); ?> </h1>
    <table>
        <tr>
            <td>Titolo</td>
            <td><input type="text" id="titolo_post" placeholder="Inserire qui titolo"></td>
        </tr>
        <tr>
            <td>Nome</td>
            <td><input type="text" id="nome_post" placeholder="Inserire qui nome"></td>
        </tr>
        <tr>
            <td>Cognome</td>
            <td><input type="text" id="cognome_post" placeholder="Inserire qui cognome"></td>
        </tr>
        <tr>
            <td>E-mail</td>
            <td><input type="email" id="email_post" placeholder="example@mail.it"></td>
        </tr>
        <tr>
            <td>Telefono</td>
            <td><input type="number" min="0" id="telefono_post" placeholder="123456789"></td>
        </tr>
        <tr>
            <td colspan="2"><button author-id="<?php echo wp_get_current_user()->ID?>" onclick="addPost()">Aggiungi</button></td>
        </tr>


    </table>

<?php
get_footer();?>

</html>

