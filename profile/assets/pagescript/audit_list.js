///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Audit List Script goes here       //
///////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var xwb = window.xwb;
    var _args = window.xwb_var;
    var bootbox = window.bootbox;


    $.extend(func, {
        approvePO: function (po_id){
            bootbox.confirm("Are you sure you want to approve this Purchase Order?", function(result){ 
                if(result){
                    $.ajax({
                        url: _args.varApprovePO,
                        type: "post",
                        data: {
                            'po_id':po_id
                        },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(data.status == true){
                                xwb.Noty(data.message,'success');
                                xwb_var.table_audit_list.ajax.reload();
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
                }
            });
        },

        /**
        * Return to purchasing
        *
        * @param int po_id
        *
        * @return mixed
        */
        returnToPurchasing: function (po_id){
            bootbox.dialog({
                title: 'Return to Purchasing Department',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="reason">Reason</label>'+
                                    '<div class="col-md-6">'+
                                        '<textarea name="reason" id="reason" class="form-control"></textarea>'+
                                    '</div> '+
                                '</div> '+
                            '</div>'+
                        '</div>',
                buttons:{
                    deny: {
                        label: "Deny",
                        className: "btn-success",
                        callback: function () {
                            $.ajax({
                                url: _args.varReturnToPO,
                                type: "post",
                                data: {
                                    po_id:po_id,
                                    reason:$("#reason").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_audit_list.ajax.reload();
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
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                }

            });

        }
    });

    var xwb_var = library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;


    $(function() {
        /**
        *generate department datatable
        *
        *
        */
        _args.table_audit_list = $('.table_audit_list').DataTable({
            "processing": false, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [[ 0, "desc" ]], //Initial no order.
            "ajax": {
                "url": _args.varGetAuditList,
                "data": function ( d ) {
                    //d.extra_search = $('#extra').val();
                }
                },
            "columnDefs": [
                { 
                    "targets": [ 6 ], 
                    "orderable": false, //set not orderable
                },
            ],
            "createdRow": function( row, data, dataIndex ) {
                if($(row).find('a.has-action').length != 0){
                    $(row).css('background-color','#dff0d8');
                }
                
            }
        });


        $(document).ajaxStart(function() {
            $("div.loader").show();
        }).ajaxStop(function() {
            $("div.loader").hide();
        });

    });

    return _args;
}));
