<h1>SCEGLI UN CUSTOM POST TYPE DA MODIFICARE</h1>
<select onchange="compile_update_post(this.value)" id="select_update_post">
    <option></option>
    <?php foreach (all_custom_post_list() as $post):?>
    <option value="<?=$post->post_slug ?>"><?=$post->post_name ?></option>
    <?php endforeach;?>
</select>
<hr>
<table>
    <tr class="row">
        <td class="col col-12"><h2>INFORMAZIONI NEW POST TYPE</h2></td>
    </tr>
    <tr class="row">
        <td class="col col-6">NOME CUSTOM POST</td>
        <td class="col col-6"><input oninput="check_input('post_name')" onblur="make_slug()" id="post_name"
                                     placeholder="Inserire nome qui.."></td>
    </tr>
    <tr class="row">
        <td class="col col-6">SLUG POST</td>
        <td class="col col-6"><input oninput="check_input('post_slug')" id="post_slug"
                                     placeholder="Inserire slug qui.."></td>
    </tr>
    <tr class="row">
        <td class="col col-6">NOME SINGOLARE</td>
        <td class="col col-6"><input oninput="check_input('post_singular_name')" id="post_singular_name"
                                     placeholder="Nome singolare qui.."></td>
    </tr>
    <tr class="row">
        <td class="col col-6">CONTENTUTO</td>
        <td class="col col-6"><input id="post_content" type="checkbox"></td>
    </tr>
    <tr class="row">
        <td class="col col-6">RIASSUNTO</td>
        <td class="col col-6"><input id="post_excerpt" type="checkbox"></td>
    </tr>
    <tr class="row">
        <td class="col col-6">IMMAAGINE</td>
        <td class="col col-6"><input type="checkbox" id="post_thumb"></td>
    </tr>
    <tr class="row">
        <td class="col col-6">COMMENTI</td>
        <td class="col col-6"><input type="checkbox" id="post_comments"></td>
    </tr>
    <tr class="row">
        <td class="col col-6">CUSTOM FIELDS</td>
        <td class="col col-6"><input type="checkbox" id="post_custom_fields"></td>
    </tr>
    <tr class="row">
        <td class="col col-12"><h2>INFORMAZIONI TAXONOMY</h2></td>
    </tr>
    <?php foreach (array_diff(get_taxonomies(), array('nav_menu', 'link_category', 'post_format', 'wp_theme', 'wp_template_part_area')) as $t): ?>
        <tr class="row">
            <td class="col col-6"><?php echo $t; ?></td>
            <td class="col col-6"><input
                    type="checkbox" <?php if ($t == 'post_tag' || $t == 'category') echo 'checked'; ?>
                    value="<?php echo $t; ?>" id="post_taxonomies"></td>
        </tr>

    <?php endforeach; ?>
    <tr class="row">
        <td class="col col-12">
            <button class="button" onclick="update_post()">Modifica</button>
        </td>
    </tr>
</table>
