/////////////////////////////////////////////
// XWB Purchasing                          //
// Author - Jay-r Simpron                  //
// Copyright (c) 2017, Jay-r Simpron       //
//                                         //
// all products category scripts goes here //
/////////////////////////////////////////////
(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    var table_product_cat;


    /**
     * Jquery functions goes here
     * 
     */
    $(function() {


        /**
         * Add Product Category
         * 
         * @return mixed
         */
        $.fn.addProdCat = function () {
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();

                bootbox.dialog({
                    title: "Add Product Category",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<form class="form-horizontal" name="form_prod_cat" id="form_prod_cat">'+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="name">Category Name</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="name" name="name" type="text" placeholder="Category name" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="description">Category Description</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="description" name="description" type="text" placeholder="Category Description" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="branch">Parent</label>'+
                                '<div class="col-md-6">'+
                                '<select class="parentcat" id="parentcat" name="parentcat" style="width:100%;">'+
                                    _args.parentCat +
                                '</div> '+
                            '</div> '+
                        '</form> </div>  </div>',
                    buttons: {
                        success: {
                            label: "Save",
                            className: "btn-success",
                            callback: function () {
                                var frm_data  = $("#form_prod_cat").serializeArray();

                                $.ajax({
                                    url: _args.varAddProdCat,
                                    type: "post",
                                    data: frm_data,
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            xwb.Noty(data.message,'success');
                                            table_product_cat.ajax.reload();
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

                $("#parentcat").select2({});
            });
        };


        /**
        * Delete Category
        *
        * @return mixed
        */
        $.fn.deleteProdCat = function () {
            $( document ).delegate( '.xwb-del-prodcat', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var cat_id = $(this).data('prodcat');

                bootbox.confirm("Are you sure you want to delete this record?", function(result){ 
                    if(result){
                        $.ajax({
                            url: _args.varDeleteProdCat,
                            type: "post",
                            data: {
                                'cat_id':cat_id
                            },
                            success: function(data){
                                data = $.parseJSON(data);
                                if(data.status == true){
                                    xwb.Noty(data.message,'success');
                                    table_product_cat.ajax.reload();
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
        * Edit Category
        *
        * @return mixed
        */
        $.fn.editProdCat = function(){
            $( document ).delegate( '.xwb-edit-prodcat', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var cat_id = $(this).data('prodcat');

                $.ajax({
                    url: _args.varEditProdCat,
                    type: "post",
                    data: {
                        cat_id: cat_id
                    },
                    success: function(data){
                        var edit_data = $.parseJSON(data);
                        bootbox.dialog({
                            title: "Edit Department",
                            message: '<div class="row">  ' +
                                '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                                '<form class="form-horizontal" name="form_edit_cat" id="form_edit_cat"> ' +
                                    '<input type="hidden" id="id" name="id" />'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="name">Category Name</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="name" name="name" type="text" placeholder="Category name" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="description">Category Description</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="description" name="description" type="text" placeholder="Category Description" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="branch">Parent</label>'+
                                        '<div class="col-md-6">'+
                                        '<select class="parent" id="parent" name="parent" style="width:100%;">'+
                                            _args.parentCat +
                                        '</div> '+
                                    '</div> '+
                                '</form> </div>  </div>',
                            buttons: {
                                success: {
                                    label: "Update",
                                    className: "btn-success",
                                    callback: function () {
                                        var frm_data  = $("#form_edit_cat").serializeArray();

                                        $.ajax({
                                            url: _args.varUpdateProdCat,
                                            type: "post",
                                            data: frm_data,
                                            success: function(data){
                                                data = $.parseJSON(data);
                                                if(data.status == true){
                                                    xwb.Noty(data.message,'success');
                                                    table_product_cat.ajax.reload();
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

                        $("#form_edit_cat").populate(edit_data);   
                        $("#parent").select2({});
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
        *generate product category datatable
        *
        *
        */
        table_product_cat = $('.table_product_cat').DataTable({
            "ajax": {
                "url": _args.varGetProdCat,
                "data": function ( d ) {
                    //d.extra_search = $('#extra').val();
                }
              },
              "order": [[ 0, "desc" ]]
        });


        $('.xwb-add-prodcat').addProdCat();
        $('.xwb-edit-prodcat').editProdCat();
        $('.xwb-del-prodcat').deleteProdCat();

    });
    
}));