///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Status Script goes here           //
///////////////////////////////////////
(function(library) {
    "use strict";
    var _args;

    _args = library(window.jQuery, window, document);
    
}(function($, window, document) {
    "use strict";
    var xwb = window.xwb;
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var table_status;
    
    /**
     * Jquery functions goes here
     * 
     */
    $(function() {

        /**
        *generate status datatable
        *
        *
        */
        table_status = $('.table-status').DataTable({
            "ajax": {
                "url": _args.varGetStatus,
                "data": function ( d ) {
       
                }
              },
              "order": [[ 0, "desc" ]]
        });


        /**
         * Add Status
         * 
         * @return mixed
         */
        $.fn.addStatus = function(){
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                bootbox.dialog({
                    title: "Add Status",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<form class="form-horizontal" name="form_add_status" id="form_add_status">'+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="name">Name</label>'+
                                '<div class="col-md-6">'+
                                    _args.status_names+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="status_number">Status Number:</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="status-number" name="status_number" type="number" placeholder="Status Number" class="form-control"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="status_text">Status Text:</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="status-text" name="status_text" type="text" placeholder="Status Text" class="form-control"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="status_type">Status Type:</label>'+
                                '<div class="col-md-6">'+
                                    _args.status_types+
                                '</div> '+
                            '</div> '+
                        '</form> </div>  </div>',
                    buttons: {
                        success: {
                            label: "Save",
                            className: "btn-success",
                            callback: function () {
                                var frm_data  = $("#form_add_status").serializeArray();

                                $.ajax({
                                    url: _args.varAddStatus,
                                    type: "post",
                                    data: frm_data,
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            xwb.Noty(data.message, 'success'); 
                                            table_status.ajax.reload();
                                            bootbox.hideAll();
                                        }else{
                                            xwb.Noty(data.message, 'error'); 
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
                            label: "Cancel",
                            className: "btn-warning",
                            callback: function () {

                            }
                        }
                    }
                }).init(function(){
                    $("#status-names").select2({});

                    /*remove tab-index for the select2 compatiblity of bootbox*/
                    setTimeout(function(){
                        $('.bootbox.modal').removeAttr('tabindex');
                    },100);
                }); 
            });
        };

        /**
        * Edit Status
        *
        * @return mixed
        */
        $.fn.editStatus = function(){
            $( document ).delegate( '.xwb-edit-status', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var status_id = $(this).data('id');
                $.ajax({
                    url: _args.varEditStatus,
                    type: "post",
                    data: {
                        status_id: status_id
                    },
                    success: function(data){
                        var edit_data = $.parseJSON(data);
                        bootbox.dialog({
                            title: "Edit Status",
                            message: '<div class="row">  ' +
                                '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                                '<form class="form-horizontal" name="form_edit_status" id="form_edit_status"> ' +
                                    '<input type="hidden" id="id" name="id" />'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="name">Name</label>'+
                                        '<div class="col-md-6">'+
                                            _args.status_names+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="status_number">Status Number:</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="status-number" name="status_number" type="number" placeholder="Status Number" class="form-control"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="status_text">Status Text:</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="status-text" name="status_text" type="text" placeholder="Status Text" class="form-control"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="status_type">Status Type:</label>'+
                                        '<div class="col-md-6">'+
                                            _args.status_types+
                                        '</div> '+
                                    '</div> '+
                                '</form> </div>  </div>',
                            buttons: {
                                success: {
                                    label: "Update",
                                    className: "btn-success",
                                    callback: function () {
                                        var frm_data  = $("#form_edit_status").serializeArray();

                                        $.ajax({
                                            url: _args.varUpdateStatus,
                                            type: "post",
                                            data: frm_data,
                                            success: function(data){
                                                data = $.parseJSON(data);
                                                if(data.status == true){
                                                    xwb.Noty(data.message,'success');
                                                    table_status.ajax.reload();
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
                                        return false;
                                    }
                                },
                                cancel: {
                                    label: "Cancel",
                                    className: "btn-warning",
                                    callback: function () {

                                    }
                                }
                            }
                        }).init(function(){
                            $("#status-names").select2({});
                            $("#form_edit_status").populate(edit_data);   
                        });
                        
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log(XMLHttpRequest);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            });

        }; // End editStatus




        /**
        * Delete Status
        *
        * @return mixed
        */
        $.fn.deleteStatus = function(id){
            $( document ).delegate( '.xwb-del-status', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var status_id = $(this).data('id');

                bootbox.confirm("Unless you know what you’re doing, it isn’t recommended to delete status, please confirm action before deleting.", function(result){ 
                    if(result){
                        $.ajax({
                            url: _args.varDeleteStatus,
                            type: "post",
                            data: {
                                'id':status_id
                            },
                            success: function(data){
                                data = $.parseJSON(data);
                                if(data.status == true){
                                    xwb.Noty(data.message,'success');
                                    table_status.ajax.reload();
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
            });
        };

        $('.xwb-add-status').addStatus();
        $('.xwb-edit-status').editStatus();
        $('.xwb-del-status').deleteStatus();
        

    });
    
}));