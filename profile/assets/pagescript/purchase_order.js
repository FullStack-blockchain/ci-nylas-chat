///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Purchase Order Script goes here   //
///////////////////////////////////////
var xwb = (function(library) {
	"use strict";
    library(window.jQuery, window, document);
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;


    $(function() {

		/*declares datatable*/
		var table_po;

		/**
		*generate purchase order list datatable
		*
		*
		*/
		table_po = $('.table_po').DataTable({
		    "ajax": {
		        "url": _args.varGetPO,
		        "data": function ( d ) {

		        }
		      },
		      "order": [[ 0, "desc" ]]
		});

    });

}));
