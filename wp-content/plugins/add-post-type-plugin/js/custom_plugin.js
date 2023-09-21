function invia_dati() {
    //alert("Ciao " + jQuery("#post_name").val());
    let $post_name = jQuery("#post_name").val();
    let $post_slug = jQuery("#post_slug").val();
    let $post_singular_name = jQuery("#post_singular_name").val();
    let $post_content = jQuery("#post_content")['0']['checked'];
    let $post_excerpt = jQuery("#post_excerpt")['0']['checked'];
    let $post_thumb = jQuery("#post_thumb")['0']['checked'];
    let $post_comments = jQuery("#post_comments")['0']['checked'];
    let $post_custom_fields = jQuery("#post_custom_fields")['0']['checked'];
    if ($post_name != "" && $post_slug != "" && $post_singular_name != "") {
        var dato = {};
        dato['post_name'] = $post_name;
        dato['post_slug'] = $post_slug;
        dato['post_singular_name'] = $post_singular_name;
        dato['post_content'] = $post_content;
        dato['post_excerpt'] = $post_excerpt;
        dato['post_thumb'] = $post_thumb;
        dato['post_comments'] = $post_comments;
        dato['post_custom_fields'] = $post_custom_fields;

        jQuery.ajax({
            url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/add-custom-post-type/",
            method: "POST",
            dataType: "json",
            data: dato,
            success: function (response) {
                console.log(response);
                if (response.status === 200) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    } else {
        alert("Inserire tutti i campi di testo!");

    }
}

function elimina_post() {
    $post_name = {'post_type': jQuery('#post_selected').val()};
    if(confirm('Eliminare definitivamente?')) {
        jQuery.ajax({
            url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/remove-custom-post-type/",
            method: "POST",
            dataType: "json",
            data: $post_name,
            success: function (response) {
                if (response.status === 200) {
                    alert(response.message);
                    location.reload();

                } else {
                    alert(response.message);
                }
            }
        });
    }
}

function cestina_post() {
    $post_name = {'post_type': jQuery('#post_selected').val()};
    if(confirm('Disabilitare? Potrai riabilitarlo successivamente.')) {
        jQuery.ajax({
            url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/disable-custom-post-type/",
            method: "POST",
            dataType: "json",
            data: $post_name,
            success: function (response) {
                if (response.status === 200) {
                    alert(response.message);
                    location.reload();

                } else {
                    alert(response.message);
                }
            }
        });
    }
}

function attiva_post() {
    $post_name = {'post_type': jQuery('#post_selected').val()};
        jQuery.ajax({
            url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/enable-custom-post-type/",
            method: "POST",
            dataType: "json",
            data: $post_name,
            success: function (response) {
                if (response.status === 200) {
                    alert(response.message);
                    location.reload();

                } else {
                    alert(response.message);
                }
            }
        });
}
function replace_wrong(str){
    // str = str.toLowerCase();
    //str = str.trim();
    str = str.replaceAll("  "," ");
    str = str.replace(/[à-ù0-9"!£$%&/.,;()'{}\]\[=?^ÈÙ*|+Ú]/gi,"");
    return str;
}
function check_input(name){
    name = '#'+name;
    jQuery(name).val(replace_wrong(jQuery(name).val()));
}
function make_slug(){
    let pn = jQuery('#post_name').val();
    pn = pn.trim();
    pn = pn.replaceAll(" ","-");
    pn = pn.toLowerCase();
    jQuery('#post_slug').val(pn);
}