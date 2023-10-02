<h1><?php echo _e('SELECT POST-TYPE TO BE REMOVED OR DISABLED','add-post-type-plugin');?></h1>
<?php $verify = false;
if (count(all_custom_post_list()) == 0):
    $verify = true; ?>
    <p><?php echo _e("No custom posts to remove or disable.",'add-post-type-plugin');?></p>
    <hr>
<?php endif; ?>
<table>
    <tr class="row">
        <td class="col col-6"><?php echo _e('SELECT POST-TYPE','add-post-type-plugin');?></td>
        <td class="col col-6"><select <?php if ($verify) echo 'disabled' ?> id="post_selected">
                <option value="" selected disabled> - </option>
                <?php
                foreach (all_custom_post_list() as $pt):?>
                    <option value="<?php echo $pt->post_slug; ?>"><?php echo $pt->post_name; ?></option>
                <?php endforeach; ?>
            </select></td>
    </tr>
    <tr class="row">
        <td class="col col-6">
            <button <?php if ($verify) echo 'disabled' ?> class="button" onclick="cestina_post('<?php echo wp_create_nonce();?>')"><?php echo _e('DISABLE','add-post-type-plugin');?></button>
        </td>
        <td class="col col-6">
            <button <?php if ($verify) echo 'disabled' ?> class="button" onclick="elimina_post('<?php echo wp_create_nonce();?>')"><?php echo _e('DELETE','add-post-type-plugin');?></button>
        </td>
    </tr>
</table>