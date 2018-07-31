////////////////////////////////////////
// XWB Purchasing                     //
// Author - Jay-r Simpron             //
// Copyright (c) 2017, Jay-r Simpron  //
//                                    //
// Request Category scripts goes here //
////////////////////////////////////////
(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    var table_req_cat;


    /**
     * Jquery functions goes here
     * 
     */
    $(function() {

        /**
         * Add Request Category
         * 
         * @return mixed
         */
        $.fn.addReqCat = function (){
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();

                bootbox.dialog({
                    title: "Add Request Category",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<form class="form-horizontal" name="form_add_req_cat" id="form_add_req_cat">'+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="name">Name</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="name" name="name" type="text" placeholder="Name" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="description">Description</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="description" name="description" type="text" placeholder="Description" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                        '</form> </div>  </div>',
                    buttons: {
                        success: {
                            label: "Save",
                            className: "btn-success",
                            callback: function () {
                                var frm_data  = $("#form_add_req_cat").serializeArray();

                                $.ajax({
                                    url: _args.varAddReqCat,
                                    type: "post",
                                    data: frm_data,
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            xwb.Noty(data.message,'success');
                                            table_req_cat.ajax.reload();
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
                            label: "Cancel",
                            className: "btn-warning",
                            callback: function () {

                            }
                        }
                    }
                }); 
            });
        };


        /**
        * Edit Request Category
        *
        * @return void
        */

        $.fn.editReqCat = function (){
            $( document ).delegate( '.xwb-edit-request-cat', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var reqcat_id = $(this).data('request_cat');

                $.ajax({
                    url: _args.varEditReqCat,
                    type: "post",
                    data: {
                        reqcat_id: reqcat_id
                    },
                    success: function(data){
                        var edit_data = $.parseJSON(data);
                        bootbox.dialog({
                            title: "Edit Request Category",
                            message: '<div class="row">  ' +
                                '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                                '<form class="form-horizontal" name="form_edit_req_cat" id="form_edit_req_cat"> ' +
                                    '<input type="hidden" id="id" name="id" />'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="name">Name</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="name" name="name" type="text" placeholder="Name" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="description">Description</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="description" name="description" type="text" placeholder="Description" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                '</form> </div>  </div>',
                            buttons: {
                                success: {
                                    label: "Update",
                                    className: "btn-success",
                                    callback: function () {
                                        var frm_data  = $("#form_edit_req_cat").serializeArray();

                                        $.ajax({
                                            url: _args.varUpdateReqCat,
                                            type: "post",
                                            data: frm_data,
                                            success: function(data){
                                                data = $.parseJSON(data);
                                                if(data.status == true){
                                                    xwb.Noty(data.message,'success');
                                                    table_req_cat.ajax.reload();
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
                        });
                        $("#form_edit_req_cat").populate(edit_data);   
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log(XMLHttpRequest);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            });
        };

        /**
        * Delete request category
        *
        * @return mixed
        */
        $.fn.deleteReqCat = function (){
            $( document ).delegate( '.xwb-del-request-cat', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var reqcat_id = $(this).data('request_cat');

                bootbox.confirm("Are you sure you want to delete this record?", function(result){ 
                    if(result){
                        $.ajax({
                            url: _args.varDeleteReqCat,
                            type: "post",
                            data: {
                                'id':reqcat_id
                            },
                            success: function(data){
                                data = $.parseJSON(data);
                                if(data.status == true){
                                    xwb.Noty(data.message,'success');
                                    table_req_cat.ajax.reload();
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

        /**
        *generate request category datatable
        *
        *
        */
        table_req_cat = $('.table_req_cat').DataTable({
            "ajax": {
                "url": _args.varGetReqCat,
                "data": function ( d ) {

                }
              },
              "order": [[ 0, "desc" ]]
        });


        $('.xwb-add-request-cat').addReqCat();
        $('.xwb-edit-request-cat').editReqCat();
        $('.xwb-del-request-cat').deleteReqCat();
        
    });
    
}));