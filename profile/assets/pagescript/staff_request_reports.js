///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Staff Request Script goes here    //
///////////////////////////////////////
(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";

    var _args = window.xwb_var;

    $(function() {
        /*declares datatable*/
        var table_staff_request_reports;


        /**
        *generate report request list datatable
        *
        *
        */
        table_staff_request_reports = $('.table_staff_request_reports').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "ajax": {
                "url": _args.varGetStaffRequestReports,
                "data": function ( d ) {
                    d.year = $('#year').val();
                    d.month = $('#month').val();
                    d.sy = $('#sy').val();
                }
            },
             //Set column definition initialisation properties.
            "columnDefs": [
                { 
                    "targets": [ 7,8 ], 
                    "orderable": false, //set not orderable
                },
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'excel',
                },
                {
                    extend: 'print',
                }
            ]
        });

        $(".search_opt").on('change',function(){
            table_staff_request_reports.ajax.reload();
        });

    });
    
}));