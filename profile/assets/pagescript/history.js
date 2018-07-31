///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// History Script goes here          //
///////////////////////////////////////
(function(library) {
	"use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {
		/*declares datatable*/
		var table_history;

		/**
		*generate transaction history in datatable
		*
		*
		*/
		table_history = $('.table_history').DataTable({
		    "processing": true, //Feature control the processing indicator.
		    "serverSide": true, //Feature control DataTables' server-side processing mode.
		    "order": [[ 0, "desc" ]], //Initial no order.
		    "ajax": {
		        "url": _args.varGetHistory,
		        "data": function ( d ) {
		            //d.branches = $('#branches').val();
		        }
		      },
		});
    });

}));
