///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Update PO Script goes here        //
///////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var _args = window.xwb_var;
    var xwb = window.xwb;
    var bootbox = window.bootbox;

    $.extend(func, {
        reupdatePO: function (){
            var frm_data;
            frm_data = $("#form_po").serializeArray();
            frm_data.push({name:'request_id', value: _args.request_id});
            frm_data.push({name:'vendor_name', value: $("#supplier option:selected").text()});
            
            $.ajax({
                url: _args.varReupdatePO,
                type: "post",
                data: frm_data,
                success: function(data){
                    data = $.parseJSON(data);
                    if(data.status == true){
                        xwb.Noty(data.message,'success');
                        bootbox.hideAll();
                    }else{
                        xwb.Noty(data.message,'error');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        },


        getPOBySupplier: function (supplier){
            $.ajax({
                url: _args.varGetPOBySupplier,
                type: "post",
                data: {
                    supplier:supplier,
                    request_id:_args.request_id,
                },
                success: function(data){
                    var po;
                    data = $.parseJSON(data);
                    po = data.PO;
                    if(po == 0){
                        $(".preview_po").prop('href','#');
                        $(".preview_po").addClass('disabled');
                    }else{
                        $(".preview_po").removeClass('disabled');
                        $(".preview_po").prop('href',po.url);
                        $("#id").prop("value",po.id);
                        $("#date_issue").prop("value",po.date_issue);
                        $("#po_num").prop("value",po.po_num);
                        $("#pr_number").prop("value",po.pr_number);
                        $("#supplier_invoice").prop("value",po.supplier_invoice);
                        $("#rr_num").prop("value",po.rr_num);
                        $("#delivery_date").prop("value",po.delivery_date);
                        $("#payment_terms").prop("value",po.payment_terms);
                        $("#warranty_condition").prop("value",po.warranty_condition);
                        $("#requisitioner").prop("value",po.requisitioner);
                        $("#auditor").prop("value",po.approve_by);
                        
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        },

    });

    window.func = func;
    library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;

    /**
     * Jquery Functions goes here
     * 
     */
    $(function() {
        /**
        *generate purchase order item list datatable
        *
        */
        _args.table_po_items = $('.table_po_items').DataTable({
            "paging":   false,
            "ordering": false,
            "searching": false,
            "order": [[ 0, "desc" ]]
        });


        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        });
        $("#date_issue").datepicker();
        $("#delivery_date").datepicker();


        $(document).ajaxStart(function() {
            $("div.loader").show();
        }).ajaxStop(function() {
            $("div.loader").hide();
        });

    });

    return _args;
}));