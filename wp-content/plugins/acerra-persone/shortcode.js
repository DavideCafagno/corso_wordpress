$ = jQuery;

$(document).ready(function () {
    var tableDataFrontend = $('#tablePersona').DataTable({
        lengthMenu: [3, 10, 25, 50, 75, 100],
        searching: false,
        processing: true,
        serverSide: true,
        serverMethod: 'POST',
        paging: true,
        scrollX: true,
        destroy: true,
        language: {
            infoFiltered: "",
            lengthMenu: "Visualizza _MENU_ righe",
            search: "Cerca:",
            paginate: {
                next: "Successivo",
                previous: "Precedente"
            },
            emptyTable: "Nessun dato trovato",
            info: "Da _START_ a _END_ di _TOTAL_ elementi",
            infoEmpty: "Da 0 a 0 di 0 elementi",
            zeroRecords: "Nessun dato trovato"
        },
        ajax: {
            url: ajax_Persone.serviceWrapFrontendUrl, // json
            // type: "POST",  // type of method
            error: function () {
                //alert(error);
            },
            data:
                function (data) {
                    data["id_organizzazione"] = $('input#id_organizzazione').val();

                    data["nominativo"] = $('input#nominativo').val();
                    data["ruolo"] = $('input#ruolo').val();
                },
        },
        columns: [
            {data: "nome_e_cognome", searchable: false, orderable: true},
            {data: "ruolo", searchable: false, orderable: true},
            {
                data: "organizzazione_di_riferimento", searchable: false, orderable: false,
                render: function (data, type, row) {
                    return `
                                <a 
                                   class="linkDettaglio"
                                   title="Apre il dettaglio di ${data.title} in un'altra finestra"
                                   href="${data.permalink}"
                                   target="_blank">${data.title}</a>
                                `;
                }

            },
            {
                data: "responsabile_di", searchable: false, orderable: false,
                render: function (data, type, row) {
                    return `
                                <a 
                                   class="linkDettaglio"
                                   title="Apre il dettaglio di ${data.title} in un'altra finestra"
                                   href="${data.permalink}"
                                   target="_blank">${data.title}</a>
                                `;
                }
            },
            {
                data: "link_dettaglio", searchable: false, orderable: false,
                render: function (data, type, row) {
                    return `
                                <a 
                                   class="linkDettaglio"
                                   title="Apre il dettaglio di ${data.title} in un'altra finestra"
                                   href="${data.permalink}"
                                   target="_blank">Dettaglio</a>
                                `;
                }
            },
        ],

    });

    $('#btnCerca').click(function () {
        console.log("aaaa")
        tableDataFrontend.ajax.reload();
    });

    $('#btnReset').click(function () {
        setTimeout(function () {
            tableDataFrontend.ajax.reload();
        }, 200);
    });
});

