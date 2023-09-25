function invia_dati() {
    let post_name = jQuery("#post_name").val();
    let post_slug = jQuery("#post_slug").val();
    let post_singular_name = jQuery("#post_singular_name").val();
    let post_content = jQuery("#post_content")['0']['checked'];
    let post_excerpt = jQuery("#post_excerpt")['0']['checked'];
    let post_thumb = jQuery("#post_thumb")['0']['checked'];
    let post_comments = jQuery("#post_comments")['0']['checked'];
    let post_custom_fields = jQuery("#post_custom_fields")['0']['checked'];
    let post_taxonomies = jQuery('#post_taxonomies:checked').map(function () {
        return jQuery(this).val();
    }).get();
    if (post_name != "" && post_slug != "" && post_singular_name != "") {
        var dato = {};
        dato['post_name'] = post_name;
        dato['post_slug'] = post_slug;
        dato['post_singular_name'] = post_singular_name;
        dato['post_content'] = post_content;
        dato['post_excerpt'] = post_excerpt;
        dato['post_thumb'] = post_thumb;
        dato['post_comments'] = post_comments;
        dato['post_custom_fields'] = post_custom_fields;
        dato['post_taxonomies'] = post_taxonomies;

        jQuery.ajax({
            url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/add-custom-post-type/",
            method: "POST",
            dataType: "json",
            data: dato,
            success: function (response) {
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
    let val = jQuery('#post_selected').val();
    if (val) {
        let post_name = {'post_type': val};
        if (confirm('Eliminare definitivamente?')) {
            jQuery.ajax({
                url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/remove-custom-post-type/",
                method: "POST",
                dataType: "json",
                data: post_name,
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
    } else {
        alert('Selezionare almeno un Post-Type');
    }
}

function cestina_post() {
    let val = jQuery('#post_selected').val();
    if (val) {
        let post_name = {'post_type': val};
        if (confirm('Disabilitare? Potrai riabilitarlo successivamente.')) {
            jQuery.ajax({
                url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/disable-custom-post-type/",
                method: "POST",
                dataType: "json",
                data: post_name,
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
    } else {
        alert('Selezionare almeno un Post-Type');
    }
}

function attiva_post() {
    let val = jQuery('#post_selected').val();
    if (val) {
        let post_name = {'post_type': val};
        jQuery.ajax({
            url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/enable-custom-post-type/",
            method: "POST",
            dataType: "json",
            data: post_name,
            success: function (response) {
                if (response.status === 200) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    } else {
        alert('Selezionare almeno un Post-Type');
    }
}

function compile_update_post(value) {
    if (value != "") {
        jQuery.ajax({
            url: "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/get-custom-post-type?post_slug=" + value,
            dataType: 'json',
            method: 'GET',
            success: function (response) {
                console.log(response);
                if (response.status === 200) {
                    let post = response.post[0];
                    jQuery("#post_name").val(post.post_name);
                    jQuery("#post_slug").val(post.post_slug);
                    jQuery("#post_singular_name").val(post.post_singular_name);
                    jQuery("#post_content")['0']['checked'] = (post.post_content == "1") ? true : false;
                    jQuery("#post_excerpt")['0']['checked'] = (post.post_excerpt == "1") ? true : false;
                    jQuery("#post_thumb")['0']['checked'] = (post.post_thumb == "1") ? true : false;
                    jQuery("#post_comments")['0']['checked'] = (post.post_comments == "1") ? true : false;
                    jQuery("#post_custom_fields")['0']['checked'] = (post.post_custom_fields == "1") ? true : false;
                    let taxarray = post.post_taxonomies.split(',');
                    console.log(taxarray);
                    jQuery.each(jQuery('input[name ="post_taxonomies"]'), function () {
                        console.log(jQuery(this));
                        let val = jQuery(this).val();

                        (jQuery(this)[0]['checked'] = taxarray.includes(val));
                    });
                } else if (response.status === 404) {
                    alert("Post non trovato");
                    clean();
                } else {
                    alert("Errore generale");
                    clean();
                }
            }
        });
    } else {
        clean();
    }
}

function clean() {
    jQuery("#post_name").val("");
    jQuery("#post_slug").val("");
    jQuery("#post_singular_name").val("");
    jQuery("#post_content")['0']['checked'] = false;
    jQuery("#post_excerpt")['0']['checked'] = false;
    jQuery("#post_thumb")['0']['checked'] = false;
    jQuery("#post_comments")['0']['checked'] = false;
    jQuery("#post_custom_fields")['0']['checked'] = false;
    let taxarray = ['category', 'post_tag'];
    jQuery.each(jQuery('input[name ="post_taxonomies"]'), function () {
        let val = jQuery(this).val();
        (jQuery(this)[0]['checked'] = taxarray.includes(val));
    });
}

function update_post() {
    let val = jQuery('#select_update_post').val();
    if (val) {
        let dato = {};
        dato['post_name'] = jQuery("#post_name").val();
        dato['post_slug'] = jQuery("#post_slug").val();
        dato['post_singular_name'] = jQuery("#post_singular_name").val();
        dato['post_content'] = jQuery("#post_content")['0']['checked'];
        dato['post_excerpt'] = jQuery("#post_excerpt")['0']['checked'];
        dato['post_thumb'] = jQuery("#post_thumb")['0']['checked'];
        dato['post_comments'] = jQuery("#post_comments")['0']['checked'];
        dato['post_custom_fields'] = jQuery("#post_custom_fields")['0']['checked'];
        dato['post_taxonomies'] = jQuery('#post_taxonomies:checked').map(function () {
            return jQuery(this).val();
        }).get();
        if (dato['post_name'] && dato['post_slug'] && dato['post_singular_name']) {
            let url = "http://localhost/Progetti/Corso_wordpress/wp-json/plug/v1/update-custom-post-type/?old_slug=" + val;
            if (confirm('Sicro di voler modificare?')) {
                if (dato['post_slug'] != val) {
                    if (confirm("I post associati a slug: '" + val + "' perderanno l'associazione al post-type, associarli al nuovo slug: '" + dato['post_slug'] + "' ?")) {
                        url += '&association=true';
                    } else {
                        url += '&association=false';
                    }
                } else {
                    url += '&association=false';
                }
                jQuery.ajax({
                        url: url,
                        method: 'POST',
                        dataType: 'json',
                        data: dato,
                        success: function (response) {
                            //console.log(response);
                            if (response.status === 200) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        }

                    }
                );
            }
        } else {
            alert('Inserire i campi testuali');
        }
    } else {
        alert('Selezionare almeno un Post-Type');
    }
}

function replace_wrong(str) {
    str = str.replaceAll("  ", " ");
    str = str.replace(/[à-ù0-9"!£$%&/.,;()'{}\]\[=?^ÈÙ*|+Ú]/gi, "");
    return str;
}

function check_input(name) {
    name = '#' + name;
    jQuery(name).val(replace_wrong(jQuery(name).val()));
}

function make_slug() {
    let pn = jQuery('#post_name').val();
    pn = pn.trim();
    pn = pn.replaceAll(" ", "-");
    pn = pn.toLowerCase();
    jQuery('#post_slug').val(pn);
}