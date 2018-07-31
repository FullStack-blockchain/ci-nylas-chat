///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Request Reports Script goes here  //
///////////////////////////////////////
(function(library) {

    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";

    var _args = window.xwb_var;

    $(function() {
        /**
        *generate report request list datatable
        *
        *
        */
        _args.table_request_reports = $('.table_request_reports').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "ajax": {
                "url": _args.varGetRequestReports,
                "data": function ( d ) {
                    d.branches = $('#branches').val();
                    d.department = $('#department').val();
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
            _args.table_request_reports.ajax.reload();
        });

    });
    
}));