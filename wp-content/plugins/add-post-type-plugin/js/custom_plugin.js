//console.log(wp.i18n.__('Add Post-Type', 'add-post-type-plugin'));
//console.log(lang.script);

function invia_dati(nonce) {

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
            url: wp_ajax.ajaxUrl,
            method: "POST",
            dataType: "json",
            data: {action: 'add_custom_post', post_type: dato, addPostNonce: nonce},
            success: function (response) {
                if (response.status === 200) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert(response.data);
                }
            }
        });
    } else {
        alert(lang.insert_all);
        //alert(wp.i18n.__('Insert all text fields!', 'add-post-type-plugin'));

    }
}

function elimina_post(nonce) {
    let val = jQuery('#post_selected').val();
    if (val) {

        let association = false;
        if (
            confirm(lang.sure_delete)
            //confirm(wp.i18n.__('Are you sure to permanently delete?', 'add-post-type-plugin'))
        ) {
            if (confirm(lang.confirm_delete_posts)) { //confirm(wp.i18n.__('Do you want to permanently delete all associated posts?', 'add-post-type-plugin'))
                association = true;
            }
            jQuery.ajax({
                url: wp_ajax.ajaxUrl,
                method: "POST",
                dataType: "json",
                data: {action: 'remove_custom_post', association: association, post_type: val, removePostNonce: nonce},
                success: function (response) {
                    if (response.status === 200) {
                        alert(response.data);
                        location.reload();
                    } else {
                        alert(response.data);
                    }
                }
            });
        }
    } else {
        alert(lang.select_one);
        //alert(wp.i18n.__('Select at least one Post-Type!', 'add-post-type-plugin'));
    }
}

function cestina_post(nonce) {
    let val = jQuery('#post_selected').val();
    if (val) {
        if (
            confirm(lang.sure_disable)
            //confirm(wp.i18n.__('Are you sure to disable? You can enable it later.', 'add-post-type-plugin'))
        ) {
            jQuery.ajax({
                url: wp_ajax.ajaxUrl,
                method: "POST",
                dataType: "json",
                data: {
                    action: 'disable_custom_post',
                    post_type: val,
                    disablePostNonce: nonce
                },
                success: function (response) {
                    if (response.status === 200) {
                        alert(response.data);
                        location.reload();
                    } else {
                        alert(response.data);
                    }
                }
            });
        }
    } else {
        alert(lang.select_one);
//        alert(wp.i18n.__('Select at least one Post-Type!', 'add-post-type-plugin'));
    }
}

function attiva_post(nonce) {
    let val = jQuery('#post_selected').val();
    if (val) {
        jQuery.ajax({
            url: wp_ajax.ajaxUrl,
            method: "POST",
            dataType: "json",
            data: {action: 'enable_custom_post', post_type: val, enablePostNonce: nonce},
            success: function (response) {
                if (response.status === 200) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert(response.data);
                }
            }
        });
    } else {
        alert(lang.select_one);
        // alert(wp.i18n.__('Select at least one Post-Type!', 'add-post-type-plugin'));
    }
}

function compile_update_post(value, nonce) {
    if (value != "") {
        jQuery.ajax({
            url: wp_ajax.ajaxUrl,
            dataType: 'json',
            method: 'GET',
            data: {action: 'get_custom_post', post_slug: value, getPostNonce: nonce},
            success: function (response) {
                if (response.status === 200) {
                    let post = response.data[0];
                    jQuery("#post_name").val(post.post_name);
                    jQuery("#post_slug").val(post.post_slug);
                    jQuery("#post_singular_name").val(post.post_singular_name);
                    jQuery("#post_content")['0']['checked'] = (post.post_content == "1") ? true : false;
                    jQuery("#post_excerpt")['0']['checked'] = (post.post_excerpt == "1") ? true : false;
                    jQuery("#post_thumb")['0']['checked'] = (post.post_thumb == "1") ? true : false;
                    jQuery("#post_comments")['0']['checked'] = (post.post_comments == "1") ? true : false;
                    jQuery("#post_custom_fields")['0']['checked'] = (post.post_custom_fields == "1") ? true : false;
                    let taxarray = post.post_taxonomies.split(',');
                    jQuery.each(jQuery('input[name ="post_taxonomies"]'), function () {
                        let val = jQuery(this).val();

                        (jQuery(this)[0]['checked'] = taxarray.includes(val));
                    });
                } else if (response.status === 404) {
                    alert(response.data);
                    clean();
                } else {
                    alert("Error!");
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

function update_post(nonce) {
    let val = jQuery('#select_update_post').val();
    let association = false;
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

            if (
                confirm(lang.sure_changes)
                // confirm(wp.i18n.__("Are you sure to make the changes?", "add-post-type-plugin")/*'Sicro di voler modificare?'*/)
            ) {
                if (dato['post_slug'] != val) {
                    if (
                        confirm(sprintf(lang.new_association, val, dato['post_slug']))
                        //confirm(wp.i18n.__("The posts associated with their old slug will lose the association with their Post-Type. Do you want to associate them with the new slug ", "add-post-type-plugin") + "'" + dato['post_slug'] + "' ?")
                    ) {
                        association = true;
                    }
                }
                jQuery.ajax({
                        url: wp_ajax.ajaxUrl,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'update_custom_post',
                            post_type: dato,
                            association: association,
                            updatePostNonce: nonce,
                            old_slug: val
                        },
                        success: function (response) {
                            if (response.status === 200) {
                                alert(response.data);
                                location.reload();
                            } else {
                                alert(response.data);
                            }
                        }

                    }
                );
            }
        } else {
            alert(lang.insert_all);
            //alert(wp.i18n.__('Insert all text fields!', 'add-post-type-plugin'));
        }
    } else {
        alert(lang.select_one);
        //alert(wp.i18n.__('Select at least one Post-Type!', 'add-post-type-plugin'));
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