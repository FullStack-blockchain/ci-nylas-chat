//////////////////////////////////////////
// XWB Purchasing                       //
// Author - Jay-r Simpron               //
// Copyright (c) 2017, Jay-r Simpron    //
//                                      //
// Staff Items Reports Script goes here //
//////////////////////////////////////////
(function(library) {

    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";

    var _args = window.xwb_var;

    $(function() {
        /*declares datatable*/
        var table_items_report;


        /**
        *generate report request list datatable
        *
        *
        */
        table_items_report = $('.table_items_report').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "ajax": {
                "url": _args.varGetStaffItemReports,
                "data": function ( d ) {
                    d.year = $('#year').val();
                    d.month = $('#month').val();
                    d.sy = $('#sy').val();
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                { 
                    "targets": [ 10,11 ], 
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
                    extend: 'excelHtml5',
                },
                {
                    extend: 'print',
                }
            ]
        });

        $(".search_opt").on('change',function(){
            table_items_report.ajax.reload();
        });

    });
    
}));