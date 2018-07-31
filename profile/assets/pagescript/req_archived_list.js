///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Archive items goes here           //
///////////////////////////////////////

var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var xwb = window.xwb;
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var table_attachment;
    var table_req_items;
    $.extend(func, {
        /**
        * View Items per request
        * @param int request_id
        *
        * @return mixed
        */

        viewItems: function (request_id){
            var expenditure = "";
            var qty_price = "";
            if(_args.group_name == 'budget')
                expenditure = '<th>Expenditure</th>';
            
            if(_args.group_name == 'admin')
                qty_price = '<th>Unit Price</th><th>Total Price</th>';

            bootbox.dialog({
                title: 'Request Items',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table_req_items">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Item</th>'+
                                                '<th>Category</th>'+
                                                '<th>Description</th>'+
                                                '<th>Supplier</th>'+
                                                '<th>Qty</th>'+
                                                expenditure+
                                                qty_price+
                                                '<th>Attachment</th>'+
                                                '<th>ETA</th>'+
                                                '<th>Date Delivered</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                className: 'width-90p',
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
                    "url": _args.varGetRequestItems,
                    "data": function ( d ) {
                        d.request_id = request_id;
                    }
                  },
                  "order": [[ 0, "desc" ]]
            });
        },

        /**
        * View Attachment
        * @param int po_id
        *
        * @return mixed
        */
        viewAttachmentPreview: function (po_id){

            bootbox.dialog({
                title: "Attachment",
                message: '<div class="row">'+
                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<div class="panel panel-success">'+
                            '<div class="panel-heading">'+
                              '<h3 class="panel-title">Attachment</h3>'+
                            '</div>'+
                            '<div class="panel-body">'+
                                '<form class="form-horizontal" name="form_add_attachment" id="form_add_attachment">'+
                                    '<input type="hidden" name="po_id" id="po_id" value="'+po_id+'">'+
                                    '<div class="form-group">'+
                                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                            '<div class="input-group">'+
                                                '<input id="attachment" name="attachment" type="file">'+
                                            '</div>'+
                                        '</div> '+
                                        '<hr />'+
                                        '<span class="input-group-btn">'+
                                            '<button type="button" onClick="xwb.submitAttachment()" class="btn btn-primary">Upload</button>'+
                                        '</span>'+
                                    '</div> '+
                                '</form>'+
                                '<hr />'+
                                '<div class="table-responsive">'+
                                    '<table class="table table_attachment">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>'+
                                                '<th>Filename</th>'+
                                                '<th>Action</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                        '</tbody>'+
                                    '</table>'+
                            '</div>'+

                        '</div>'+
                    '</div></div>',
                buttons: {
                    close: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }
            }).init(function(){
                /**
                *generate attachment datatable
                *
                *
                */
                table_attachment = $('.table_attachment').DataTable({
                    "ajax": {
                        "url": _args.varGetAttachment,
                        "data": function ( d ) {
                            d.po_id = po_id;
                        }
                      },
                      "order": [[ 0, "desc" ]]
                });
            }); 



        },

        /**
        * Move request to archive
        *
        * @param int request_id
        *
        * @return void
        */
        unarchive: function (request_id){
            bootbox.confirm("Are you sure you want to restore this request?", function(result){ 
                if(result){
                    $.ajax({
                        url: _args.varUnArchiveRequest,
                        type: "post",
                        data: {
                            request_id:request_id,
                        },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(data.status == true){
                                xwb.Noty(data.message,'success');
                                xwb_var.table_archivereq_list.ajax.reload();
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


    $(function() {

        /**
        *generate request list datatable
        *
        *
        */
        _args.table_archivereq_list = $('.table_archivereq_list').DataTable({
            "processing": false, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [[ 0, "desc" ]], //Initial no order.
            "ajax": {
                "url": _args.varGetArchRequest,
                "data": function ( d ) {
                    //d.extra_search = $('#extra').val();
                }
              },
            "columnDefs": [
                { 
                    "targets": [ 7,9 ], 
                    "orderable": false, //set not orderable
                },
            ],
        });


    });
    return _args;
}));
