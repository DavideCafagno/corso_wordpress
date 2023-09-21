<?php
$title = "";
$content = "";
$tag = "";
if (!empty($_SESSION)) {
    $title = $_SESSION['title_search_param'];
    $content = $_SESSION['content_search_param'];
    $tag = $_SESSION['tag_search_param'];
}
?>
<form action="/Progetti/Corso_wordpress/search-result/" method="POST">
    <div style="text-align: center; margin:30px 0 100px 0;" class="container_form_ricerca ">
        <input type="text" class="col col-3" id="title_search_param" name="title_search_param"
               placeholder="Cerca nel titolo.." value="<?php echo $title; ?>">
        <input type="text" class="col col-3" id="content_search_param" name="content_search_param"
               placeholder="Cerca nel contenuto.." value="<?php echo $content; ?>">
        <select type="text" class="col col-3" id="tag_search_param" name="tag_search_param" value="<?php echo $tag; ?>">
            <option></option>
            <?php foreach (get_tags(array('taxonomy' => array('post_tag', 'category', 'categoria_custom'))) as $t): ?>
                <option value="<?php echo $t->slug ?>" <?php if($tag == $t->slug){echo "selected";}?>><?php echo $t->name ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="col col-2 button-large">Ricerca</button>
    </div>
</form>
