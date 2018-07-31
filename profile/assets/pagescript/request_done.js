// XWB Purchasing
// Author - Jay-r Simpron
// Copyright (c) 2017, Jay-r Simpron
// 
// Request Done Script goes here
var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var _args = window.xwb_var;
    var xwb = window.xwb;
    var bootbox = window.bootbox;
    var table_property_items;

    $.extend(func, {
        /**
        * Received items
        * @param int property_id
        *
        * @return mixed
        */
        receivedItems: function  (property_id) {
            bootbox.dialog({
                title: 'Received',
                message: '<div class="row">'+
                            '<form class="form-horizontal">'+
                                '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="date_received">Date Received</label>'+
                                        '<div class="col-md-6">'+
                                            '<input type="text" name="date_received" id="date_received" class="form-control" />'+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="date_received">Remarks</label>'+
                                        '<div class="col-md-6">'+
                                            '<textarea name="property_remarks" id="property_remarks" class="form-control"></textarea>'+
                                        '</div> '+
                                    '</div> '+
                                '</div>'+
                            '</form>'+
                        '</div>',
                buttons:{
                    return: {
                        label: "Update",
                        className: "btn-info",
                        callback: function () {
                            $.ajax({
                                url: _args.varReceivedItems,
                                type: "post",
                                data: {
                                    property_id:property_id,
                                    date_received:$("#date_received").val(),
                                    property_remarks:$("#property_remarks").val(),
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_request_done.ajax.reload();
                                        bootbox.hideAll();
                                    }else{
                                        xwb.Noty(data.message,'error');
                                        return false;
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                            return false;
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

            $("#date_received").datepicker({
                minDate:0,
            });
        },

        /**
        * View Items per property
        *
        * @param int property_id
        *
        * @return mixed
        */

        viewItems: function (property_id){
            bootbox.dialog({
                title: 'Property Items',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table_property_items">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Item</th>'+
                                                '<th>Category</th>'+
                                                '<th>Description</th>'+
                                                '<th>Qty</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                buttons:{
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }

            }).init(function(){
                table_property_items = $('.table_property_items').DataTable({
                    "ajax": {
                        "url": _args.varGetPropertyItems,
                        "data": function ( d ) {
                            d.property_id = property_id;
                        }
                      },
                      "order": [[ 0, "desc" ]]
                });
            });

        }
    });

    window.func = func;
    var xwb_var = library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;

    /**
     * Jquery Functions goes here
     * 
     */
    $(function() {

        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        });

        /**
        *generate request list datatable
        *
        *
        */
        _args.table_request_done = $('.table_request_done').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [[ 0, "desc" ]], //Initial no order.
            "ajax": {
                "url": _args.varGetProperties,
                "data": function ( d ) {
                    //d.extra_search = $('#extra').val();
                }
            },
            "columnDefs": [
                { 
                    "targets": [ 11,13 ], 
                    "orderable": false, //set not orderable
                },
            ],
            "createdRow": function( row, data, dataIndex ) {
                if($(row).find('a.has-action').length != 0){
                    $(row).css('background-color','#dff0d8');
                }
                
              }
        });

    });
    return _args;
}));