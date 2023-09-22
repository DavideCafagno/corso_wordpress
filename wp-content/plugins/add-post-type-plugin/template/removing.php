<h1>SCEGLI IL CUSTOM POST TYPE DA RIMUOVERE</h1>
<?php $verify = false;
if (count(custom_post_list()) == 0):
    $verify = true; ?>
    <p>"Nessun Custom-Post da eliminare"</p>
    <hr>
<?php endif; ?>
<table>
    <tr class="row">
        <td class="col col-6">SELEZIONA POST DA ELIMINARE</td>
        <td class="col col-6"><select <?php if ($verify) echo 'disabled' ?> id="post_selected">
                <option value="" selected disabled> - </option>
                <?php
                foreach (custom_post_list() as $pt):?>
                    <option value="<?php echo $pt->post_slug; ?>"><?php echo $pt->post_name; ?></option>
                <?php endforeach; ?>
            </select></td>
    </tr>
    <tr class="row">
        <td class="col col-6">
            <button <?php if ($verify) echo 'disabled' ?> class="button" onclick="cestina_post()">DISABILITA</button>
        </td>
        <td class="col col-6">
            <button <?php if ($verify) echo 'disabled' ?> class="button" onclick="elimina_post()">ELIMINA</button>
        </td>
    </tr>
</table>