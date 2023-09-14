//console.log(oggettoAjax);

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
        url: oggettoAjax.proprietaUrl,// 'admin-ajax.php'
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

function addPost() {

    let id = event.currentTarget.attributes['author-id'].value;
    let titolo = jQuery('#titolo_post').val();
    let nome = jQuery('#nome_post').val();
    let cognome = jQuery('#cognome_post').val();
    let email = jQuery('#email_post').val();
    let telefono = jQuery('#telefono_post').val();

    if (titolo == "") {
        alert("Inserire correttamente campo titolo");
    } else if (nome == "") {
        alert("Inserire correttamente campo nome");
    } else if (cognome == "") {
        alert("Inserire correttamente campo cognome");
    } else if (email == "") {
        alert("Inserire correttamente campo email");
    } else if (telefono == "") {
        alert("Inserire correttamente campo telefono");
    } else {
        let responseObj = {};
        responseObj['post_author'] = id;
        responseObj['post_title'] = titolo;
        responseObj['post_type'] = 'contatto';
        responseObj['post_meta'] = {
            "nome": nome,
            "cognome": cognome,
            "email": email,
            "telefono": Number(telefono),
        };
        console.log(responseObj);
        jQuery.ajax({
                type: 'post',
                url: 'http://localhost/Progetti/Corso_wordpress/wp-json/contacts/v1/add/',
                dataType: "json",
                data: responseObj,
                success: function (response) {
                    console.log(response);
                    if (response.status === 200) {
                        alert(response.response);
                    } else {
                        alert(response.response);
                    }
                }
            }
        );

        return responseObj;
    }


}
