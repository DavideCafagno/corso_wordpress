console.log(oggettoAjax);

function getAlert() {

    console.log(event.currentTarget.attributes['data_id'].value);
    console.log(event.currentTarget.attributes['data_nonce'].value);
    let id = event.currentTarget.attributes['data_id'].value;
    let nonce = event.currentTarget.attributes['data_nonce'].value;
    let input = jQuery('#inputTipoPost').val();
    //console.log(input);
    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: oggettoAjax.proprietaUrl,
        data: {action: "retrive_permalink", post_id_ajax: id, nonce_ajax: nonce, postType_ajax: input},
        success: function (response) {
            console.log('RESPONSE', response);
            if (response.type == "success") {
                alert(response.messaggio);
            } else {
                alert(response.messaggio);
            }
        }
    });


}
