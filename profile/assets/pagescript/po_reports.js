///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// PO Reports Script goes here       //
///////////////////////////////////////
(function(library) {
    "use strict";
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
        _args.table_po_reports = $('.table_po_reports').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "ajax": {
                "url": _args.varGetPOReports,
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
                    "targets": [ 9 ], 
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
            _args.table_po_reports.ajax.reload();
        });

    });
    
}));