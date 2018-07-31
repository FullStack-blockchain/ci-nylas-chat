///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Department Script goes here       //
///////////////////////////////////////
(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    /*declares datatable*/
    var table_department;


    /**
     * Jquery Functions goes here
     * 
     */
    $(function() {
        /**
        *generate department datatable
        *
        *
        */
        table_department = $('.table_department').DataTable({
            "ajax": {
                "url": _args.varGetDept,
                "data": function ( d ) {
       
                }
              },
              "order": [[ 0, "desc" ]]
        });


        /**
         * Add Department
         * 
         * @return mixed
         */
        $.fn.addDepartment = function(){
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                bootbox.dialog({
                    title: "Add Department",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<form class="form-horizontal" name="form_add_dept" id="form_add_dept">'+
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
                                var frm_data  = $("#form_add_dept").serializeArray();

                                $.ajax({
                                    url: _args.varAddDept,
                                    type: "post",
                                    data: frm_data,
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            xwb.Noty(data.message, 'success'); 
                                            table_department.ajax.reload();
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
                }); 
            });
        }; //end addDepartment

        /**
        * Edit department
        *
        * @return mixed
        */
        $.fn.editDept = function(){
            $( document ).delegate( '.xwb-edit-dept', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var dept_id = $(this).data('id');
                $.ajax({
                    url: _args.varEditDept,
                    type: "post",
                    data: {
                        dept_id: dept_id
                    },
                    success: function(data){
                        var edit_data = $.parseJSON(data);
                        bootbox.dialog({
                            title: "Edit Department",
                            message: '<div class="row">  ' +
                                '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                                '<form class="form-horizontal" name="form_edit_dept" id="form_edit_dept"> ' +
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
                                        var frm_data  = $("#form_edit_dept").serializeArray();

                                        $.ajax({
                                            url: _args.varUpdateDept,
                                            type: "post",
                                            data: frm_data,
                                            success: function(data){
                                                data = $.parseJSON(data);
                                                if(data.status == true){
                                                    xwb.Noty(data.message,'success');
                                                    table_department.ajax.reload();
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
                        $("#form_edit_dept").populate(edit_data);   
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log(XMLHttpRequest);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            });

        }; // End editDept



        /**
        * Delete department
        *
        * @return mixed
        */
        $.fn.deleteDept = function(id){
            $( document ).delegate( '.xwb-del-dept', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var dept_id = $(this).data('id');

                bootbox.confirm("Are you sure you want to delete this record?", function(result){ 
                    if(result){
                        $.ajax({
                            url: _args.varDeleteDept,
                            type: "post",
                            data: {
                                'id':dept_id
                            },
                            success: function(data){
                                data = $.parseJSON(data);
                                if(data.status == true){
                                    xwb.Noty(data.message,'success');
                                    table_department.ajax.reload();
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


        ///////////////////////////////
        // jQuery function call here //
        ///////////////////////////////
       $('.xwb-add-department').addDepartment();
       $('.xwb-edit-dept').editDept();
       $('.xwb-del-dept').deleteDept();
    });

}));

