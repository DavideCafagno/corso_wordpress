<h1>SSCEGLI IL CUSTOM POST TYPE DA ABILITARE</h1>
<?php $verify = false;
if (count(disabled_custom_post_list()) == 0):
    $verify = true; ?>
    <p>"Nessun Custom-Post disabilitato da ri-abilitare"</p><hr>
<?php endif; ?>
<table>
    <tr class="row">
        <td class="col col-6">SELEZIONA POST DA ABILITARE</td>
        <td class="col col-6"><select <?php if ($verify) echo 'disabled' ?> id="post_selected">
                <?php
                foreach (disabled_custom_post_list() as $pt):?>
                    <option value="<?php echo $pt; ?>"><?php echo $pt; ?></option>
                <?php endforeach; ?>
            </select></td>
    </tr>
    <tr class="row">
        <td class="col col-6">
            <button <?php if ($verify) echo 'disabled' ?> class="button"
                                                                                          onclick="attiva_post()">ATTIVA
            </button>
        </td>
        <td class="col col-6">
            <button <?php if ($verify) echo 'disabled' ?> class="button"
                                                                                          onclick="elimina_post()">
                ELIMINA
            </button>
        </td>
    </tr>
</table>