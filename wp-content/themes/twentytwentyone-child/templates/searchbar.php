<?php
?>
<form action="/Progetti/Corso_wordpress/search-result/" method="POST">
<div style="text-align: center;" class="container_form_ricerca ">
    <input type="text" class="col col-3" id="title_search_param" name="title_search_param" placeholder="Cerca nel titolo..">
    <input type="text" class="col col-3" id="content_search_param" name="content_search_param" placeholder="Cerca nel contenuto..">
    <select type="text" class="col col-3" id="tag_search_param" name="tag_search_param">
        <option></option>
        <?php foreach (get_tags(array('taxonomy' => array('post_tag', 'category', 'categoria_custom'))) as $tag): ?>
            <option value="<?php echo $tag->slug ?>"><?php echo $tag->name ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="col col-2 button-large">Ricerca</button>
</div>
</form>
