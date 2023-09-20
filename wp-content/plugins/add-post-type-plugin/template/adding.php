<h1>AGGIUNGI UN CUSTOM POST TYPE</h1>
<hr>
<table>
    <tr class="row">
        <td class="col col-6">NOME CUSTOM POST</td>
        <td class="col col-6"><input oninput="check_input('post_name')" onblur="make_slug()" id="post_name" placeholder="Inserire nome qui.."></td>
    </tr>
    <tr class="row">
        <td class="col col-6">SLUG POST</td>
        <td class="col col-6"><input oninput="check_input('post_slug')" id="post_slug" placeholder="Inserire slug qui.."></td>
    </tr>
    <tr class="row">
        <td class="col col-6">NOME SINGOLARE</td>
        <td class="col col-6"><input oninput="check_input('post_singular_name')" id="post_singular_name" placeholder="Nome singolare qui.."></td>
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
        <td class="col col-12">
            <button class="button" onclick="invia_dati()">AGGIUNGI</button>
        </td>
    </tr>

</table>