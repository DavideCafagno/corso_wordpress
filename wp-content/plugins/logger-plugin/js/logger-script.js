function change_files_select(value) {

    if (value) {
        let url = "http://localhost/Progetti/Corso_wordpress/wp-json/logger/v1/files/?f=" + value;
        jQuery.ajax({
                method: 'GET',
                dataType: 'json',
                url: url,
                success: function (resp) {
                    if (resp.status === 200) {
                        let html = "<option value=''> - </option>";
                        for (let file of resp.files) {
                            html += "<option value='" + file + "'>" + file + "</option>";
                        }
                        jQuery('#loggerSelectFiles').html(html);

                    } else {
                        alert(resp.message);
                    }
                }
            }
        );
    } else {
        jQuery('#loggerSelectFiles').html("<option value=''> - </option>");
    }
}


function view_file_selected(value){
    if (value) {
        let url = "http://localhost/Progetti/Corso_wordpress/wp-json/logger/v1/content/?f=" + value;
        jQuery.ajax({
                method: 'GET',
                dataType: 'json',
                url: url,
                success: function (resp) {
                    if (resp.status === 200) {
                        jQuery('#loggerTextarea').text(resp.content);
                        jQuery('#fileName').text(" ~ "+value);

                    } else {
                        alert(resp.message);
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
}