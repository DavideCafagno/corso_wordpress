<h1><?php echo _e('SELECT POST-TYPE TO BE UPDATED', 'add-post-type-plugin'); ?></h1>
<?php $verify = false;
if (count(all_custom_post_list()) == 0):
    $verify = true; ?>
    <p><?php echo _e("No custom posts to update.", 'add-post-type-plugin'); ?></p>
<?php endif; ?>
<select <?php if ($verify) echo 'disabled' ?> onchange="compile_update_post(this.value)" id="select_update_post">
    <option value="" selected> -</option>
    <?php foreach (all_custom_post_list() as $post): ?>
        <option value="<?= $post->post_slug ?>"><?= $post->post_name ?></option>
    <?php endforeach; ?>
</select>
<hr>
<table>
    <tr class="row">
        <td class="col col-12"><h2><?php _e('ABOUT NEW POST-TYPE','add-post-type-plugin');?></h2></td>
    </tr>
    <tr class="row">
        <td class="col col-6"><?php _e('CUSTOM POSTS NAME','add-post-type-plugin');?></td>
        <td class="col col-6"><input oninput="check_input('post_name')" onblur="make_slug()" id="post_name"
                                     placeholder="<?php _e("The name here..","add-post-type-plugin");?>"></td>
    </tr>
    <tr class="row">
        <td class="col col-6"><?php _e('CUSTOM POST SLUG','add-post-type-plugin');?></td>
        <td class="col col-6"><input oninput="check_input('post_slug')" id="post_slug"
                                     placeholder="<?php _e("The slug here..","add-post-type-plugin");?>"></td>
    </tr>
    <tr class="row">
        <td class="col col-6"><?php _e('CUSTOM POST SINGULAR NAME','add-post-type-plugin');?></td>
        <td class="col col-6"><input oninput="check_input('post_singular_name')" id="post_singular_name"
                                     placeholder="<?php _e("The singular name here..","add-post-type-plugin");?>"></td>
    </tr>
    <tr class="row">
        <td class="col col-6"><?php _e('CONTENT','add-post-type-plugin');?></td>
        <td class="col col-6"><input id="post_content" type="checkbox"></td>
    </tr>
    <tr class="row">
        <td class="col col-6"><?php _e('EXCERPT','add-post-type-plugin');?></td>
        <td class="col col-6"><input id="post_excerpt" type="checkbox"></td>
    </tr>
    <tr class="row">
        <td class="col col-6"><?php _e('THUMBNAIL','add-post-type-plugin');?></td>
        <td class="col col-6"><input type="checkbox" id="post_thumb"></td>
    </tr>
    <tr class="row">
        <td class="col col-6"><?php _e('COMMENTS','add-post-type-plugin');?></td>
        <td class="col col-6"><input type="checkbox" id="post_comments"></td>
    </tr>
    <tr class="row">
        <td class="col col-6"><?php _e('CUSTOM FIELDS','add-post-type-plugin');?></td>
        <td class="col col-6"><input type="checkbox" id="post_custom_fields"></td>
    </tr>
    <tr class="row">
        <td class="col col-12"><h2><?php _e('ABOUT TAXONOMIES','add-post-type-plugin');?></h2></td>
    </tr>
    <?php foreach (array_diff(get_taxonomies(), array('nav_menu', 'link_category', 'post_format', 'wp_theme', 'wp_template_part_area')) as $t): ?>
        <tr class="row">
            <td class="col col-6"><?php echo $t; ?></td>
            <td class="col col-6"><input
                        type="checkbox" <?php if ($t == 'post_tag' || $t == 'category') echo 'checked'; ?>
                        value="<?php echo $t; ?>" name="post_taxonomies" id="post_taxonomies"></td>
        </tr>
    <?php endforeach; ?>
    <tr class="row">
        <td class="col col-12">
            <button <?php if ($verify) echo 'disabled' ?> class="button" onclick="update_post()">Modifica</button>
        </td>
    </tr>
</table>
