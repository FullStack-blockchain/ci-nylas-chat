///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Branches Scripts goes here        //
///////////////////////////////////////

(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var table_branch;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    

    /**
     * Jquery functions here
     * 
     */
    $(function() {
        /**
         * [Add Branch]
         */
        $.fn.addBranch = function(){
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                bootbox.dialog({
                    title: "Add Branch",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<form class="form-horizontal" name="form_add_branch" id="form_add_branch">'+
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
                                var frm_data  = $("#form_add_branch").serializeArray();

                                $.ajax({
                                    url: _args.varAddBranch,
                                    type: "post",
                                    data: frm_data,
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            xwb.Noty(data.message,'success');
                                            table_branch.ajax.reload();
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
         * Edit Branch
         * @return {Bollean}
         */
        $.fn.editBranch = function(){
            $( document ).delegate( '.xwb-edit-branch', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var branch_id = $(this).data('id');
                $.ajax({
                    url: _args.varEditBranch,
                    type: "post",
                    data: {
                        'branch_id': branch_id
                    },
                    success: function(data){
                        var edit_data = $.parseJSON(data);
                        bootbox.dialog({
                            title: "Edit Branch",
                            message: '<div class="row">  ' +
                                '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                                '<form class="form-horizontal" name="form_edit_branch" id="form_edit_branch"> ' +
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
                                        var frm_data  = $("#form_edit_branch").serializeArray();

                                        $.ajax({
                                            url: _args.varUpdateBranch,
                                            type: "post",
                                            data: frm_data,
                                            success: function(data){
                                                data = $.parseJSON(data);
                                                if(data.status == true){
                                                    xwb.Noty(data.message,'success');
                                                    table_branch.ajax.reload();
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
                        $("#form_edit_branch").populate(edit_data);   
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
         * Delete Branch
         * 
         * @return {Bollean}
         */
        $.fn.deleteBranch = function(){
            $( document ).delegate( '.xwb-delete-branch', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var branch_id = $(this).data('id');
                bootbox.confirm("Are you sure you want to delete this record?", function(result){ 
                    if(result){
                        $.ajax({
                            url: _args.varDeleteBranch,
                            type: "post",
                            data: {
                                'id':branch_id
                            },
                            success: function(data){
                                data = $.parseJSON(data);
                                if(data.status == true){
                                    xwb.Noty(data.message,'success');
                                    table_branch.ajax.reload();
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
        *generate branch datatable
        *
        */
        table_branch = $('.table_branch').DataTable({
            "ajax": {
                "url": _args.varGetBranch,
                "data": function ( d ) {

                }
              },
              "order": [[ 0, "desc" ]]
        });

       $('.xwb-add-branch').addBranch();
       $('.xwb-edit-branch').editBranch();
       $('.xwb-delete-branch').deleteBranch();

    });
    
}));

