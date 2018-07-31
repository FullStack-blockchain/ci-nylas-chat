///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Generate PO Script goes here      //
///////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var xwb = window.xwb;
    var _args = window.xwb_var;
    var bootbox = window.bootbox;

    $.extend(func, {

        updatePO: function (){
            var frm_data, supp;
            frm_data = $("#form_po").serializeArray();
            frm_data.push({name:'request_id', value: _args.request_id});
            frm_data.push({name:'vendor_name', value: $("#supplier option:selected").text()});

            $.ajax({
                url: _args.varUpdatePO,
                type: "post",
                data: frm_data,
                success: function(data){
                    data = $.parseJSON(data);
                    if(data.status == true){
                        xwb.Noty(data.message,'success');
                        supp = $('#supplier option:selected').text();
                        func.getPOBySupplier(supp);
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
                type: "get",
                data: {
                    supplier:supplier,
                    request_id:_args.request_id,
                },
                success: function(data){
                    var po;
                    data = $.parseJSON(data);
                    po = data.PO;
                    if(po == 0){
                        $("#pr_number").prop("value",data.pr_num);
                        $("#po_num").prop("value",data.po_num);
                        $("#payment_terms").prop("value",data.payment_terms);
                        $(".preview_po").prop('href','#');
                        $(".preview_po").addClass('disabled');
                        $(".po_update").removeClass('disabled');
                        $("input, textarea, select").not("#supplier").prop('disabled',false);
                    }else{

                        if(po.status == 1){
                            $(".po_update").addClass('disabled');
                            $("input, textarea, select").not("#supplier").prop('disabled',true);
                        }else{
                            $(".po_update").removeClass('disabled');
                            $("input, textarea, select").not("#supplier").prop('disabled',false);

                        }
                       
                        
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
                        $("#auditor").prop("value",po.approve_by);

                        
                        
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }

    });

    window.func = func;
    library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var func = window.func;


    $(function() {
        /**
        *generate purchase order item list datatable
        *
        *
        */
        _args.table_po_items = $('.table_po_items').DataTable({
            "ajax": {
                "url": _args.vaGetPOItems,
                "data": function ( d ) {
                    d.supplier = $('#supplier').val();
                    d.sup_text = $('#supplier option:selected').text();
                    d.request_id = _args.request_id;
                },
                dataFilter: function(data){
                            var json = $.parseJSON( data );

                            $("#total_amount").prop("value",json.total_amount);
                            $("strong.total_amount").text(json.total_amount);
                            return JSON.stringify( json );
                }
            },
            "paging":   false,
            "ordering": false,
            "searching": false,
            "order": [[ 0, "desc" ]]
        });



        $("#supplier").select2({
            placeholder: "Select Supplier",
            allowClear: true,
            selectOnBlur: true,
        }).on('change',function(){
            var supp, isnum;
            _args.table_po_items.ajax.reload();
            $('form').find("input, textarea").val("");
            $("#auditor").val('');
            $("#payment_terms").val('');
            isnum = /^\d+$/.test(this.value);
            if(isnum){
                supp = this.value;
            }else{
                supp = $('option:selected',this).text();
            }
            
            func.getPOBySupplier(supp);
            
            
        });
        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        });
        $("#date_issue").datepicker({
            minDate:0,
        });
        $("#delivery_date").datepicker({
            minDate:0,
        });



        $(document).ajaxStart(function() {
            $("div.loader").show();
        }).ajaxStop(function() {
            $("div.loader").hide();
        });


    });
}));
