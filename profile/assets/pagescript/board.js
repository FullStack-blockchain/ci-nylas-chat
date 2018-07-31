///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Board Script goes here            //
///////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var _args = window.xwb_var;
    var xwb = window.xwb;
    var bootbox = window.bootbox;
    var table_req_items;

    $.extend(func, {
        /**
        * View Items
        * @param int request_id
        *
        * @return mixed
        */
        viewItems: function (request_id){
            bootbox.dialog({
                title: 'Request Items',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table_req_items">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Item</th>'+
                                                '<th>Description</th>'+
                                                '<th>Qty</th>'+
                                                '<th>Unit Price</th>'+
                                                '<th>Total</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                className: "width-90p",
                buttons:{
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }

            });

            table_req_items = $('.table_req_items').DataTable({
                "ajax": {
                    "url": _args.varGetBudgetApprovaltItems,
                    "data": function ( d ) {
                        d.request_id = request_id;
                    }
                  },
                  "order": [[ 0, "desc" ]]
            });
        },

        /**
        * Deny request
        * @param int boardapproval_id
        *
        * @return mixed
        */
        deny: function (boardapproval_id){
            bootbox.dialog({
                title: 'Deny request',
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
                                url: _args.varDenyRequest,
                                type: "post",
                                data: {
                                    boardapproval_id:boardapproval_id,
                                    reason:$("#reason").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_board_approval.ajax.reload();
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

        },


        /**
        * Approve request
        * @param int budget_id
        *
        * @return mixed
        */
        approve: function (board_id){
           bootbox.confirm("Are you sure you want to approve this request?", function(result){ 
                if(result){
                    $.ajax({
                        url: _args.varApproveBoard,
                        type: "post",
                        data: {
                            'board_id':board_id
                        },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(data.status == true){
                                xwb.Noty(data.message,'success');
                                xwb_var.table_board_approval.ajax.reload();
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
        }

    });

    
    var xwb_var = library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {
        _args.table_board_approval = $('.table_board_approval').DataTable({
            "ajax": {
                "url": _args.varGetBoardApproval,
                "data": function ( d ) {

                }
            },
            "order": [[ 0, "desc" ]],
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