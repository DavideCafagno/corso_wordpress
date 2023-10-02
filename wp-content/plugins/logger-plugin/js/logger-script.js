function change_files_select(value, nonce) {

    if (value) {
        //let url = "http://localhost/Progetti/Corso_wordpress/wp-json/logger/v1/files/?f=" + value;
        let url = wp_ajax.ajaxUrl;
        jQuery.ajax({
                method: 'GET',
                dataType: 'json',
                url: url,
            data:{action:'logger_list_files', loggernonce: nonce, folder: value},
                success: function (resp) {
                    console.log(resp);
                    if (resp.status === 200) {
                        let html = "<option value=''> - </option>";
                        for (let file of resp.data) {
                            html += "<option value='" + file + "'>" + file + "</option>";
                        }
                        jQuery('#loggerSelectFiles').html(html);

                    } else {
                        alert(resp.data);
                    }
                }
            }
        );
    } else {
        jQuery('#loggerSelectFiles').html("<option value=''> - </option>");
    }
}


function view_file_selected(value, nonce){
    if (value) {
        //let url = "http://localhost/Progetti/Corso_wordpress/wp-json/logger/v1/content/?f=" + value;
        let url = wp_ajax.ajaxUrl;
        jQuery.ajax({
                method: 'GET',
                dataType: 'json',
                url: url,
                data:{action:'logger_file_content', loggernonce: nonce, file: value},
                success: function (resp) {
                    console.log(resp);
                    if (resp.status === 200) {
                        jQuery('#loggerTextarea').text(resp.data);
                        jQuery('#fileName').text(" ~ "+value);

                    } else {
                        alert(resp.data);
                    }
                }
            }
        );
    }else{
        jQuery('#fileName').text("");
        jQuery('#loggerTextarea').text("");
    }
}

function loggerDark(){
    jQuery('#loggerTextarea').toggleClass('loggerdark');
    jQuery('#loggerdarkicon').toggleClass('invert');
    jQuery('#darkmodeicon').toggleClass('darker');
}