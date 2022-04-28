//pdf,print,export datatable
 jqry = $.noConflict();
jqry(document).ready(function(){
    "use strict";

    //use for every report view
    let today = new Date();
    let dd = today.getDate();
    let mm = today.getMonth()+1; //January is 0!
    let yyyy = today.getFullYear();

    if(dd<10) {
        dd = '0'+dd;
    }

    if(mm<10) {
        mm = '0'+mm;
    }
    today = yyyy + '-' + mm + '-' + dd;

    //get title and datatable id name from hidden input filed that is before in the table in view page for every datatable
    let datatable_name1 = $(".datatable_name1").attr('data-id_name');
    let title = $(".datatable_name1").attr('data-title');
    let TITLE = title+ ", "+today;
    jqry("#"+datatable_name1).DataTable( {
        'autoWidth'   : false,
        'ordering'    : false,
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'print',
                title: TITLE,
                exportOptions: {
                    columns: ':visible:not(.not-export-col)'
                }
            },
            {
                extend: 'excelHtml5',
                title: TITLE
            },
            {
                extend: 'pdfHtml5',
                title: TITLE
            }
        ]
    });
});