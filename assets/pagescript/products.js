///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// all products scripts goes here    //
///////////////////////////////////////
(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    var table_products;
    

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {



        /**
         * Add Product
         * 
         * @return mixed
         */
        $.fn.addProduct = function () {
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();

                bootbox.dialog({
                    title: "Add Product",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<form class="form-horizontal" name="form_prod" id="form_prod">'+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="product_name">Product Name</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="product_name" name="product_name" type="text" placeholder="Product name" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="description">Product Description</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="description" name="description" type="text" placeholder="Product Description" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="category">Category</label>'+
                                '<div class="col-md-6">'+
                                '<select class="category" id="category" name="category" style="width:100%;">'+
                                    _args.category +
                                '</div> '+
                            '</div> '+
                        '</form> </div>  </div>',
                    buttons: {
                        success: {
                            label: "Save",
                            className: "btn-success",
                            callback: function () {
                                var frm_data  = $("#form_prod").serializeArray();

                                $.ajax({
                                    url: _args.varAddProduct,
                                    type: "post",
                                    data: frm_data,
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            xwb.Noty(data.message,'success');
                                            table_products.ajax.reload();
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

                $("#category").select2({});
            });
        };


        /**
        * Delete Product
        *
        * @return mixed
        */
        $.fn.delProduct = function () {
            $( document ).delegate( '.xwb-del-product', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var product_id = $(this).data('product');

                bootbox.confirm("Are you sure you want to delete this record?", function(result){ 
                    if(result){
                        $.ajax({
                            url: _args.varDeleteProduct,
                            type: "post",
                            data: {
                                'product_id':product_id
                            },
                            success: function(data){
                                data = $.parseJSON(data);
                                if(data.status == true){
                                    xwb.Noty(data.message,'success');
                                    table_products.ajax.reload();
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
        * Edit Product
        *
        * @return mixed
        */
        $.fn.editProduct = function (){
            $( document ).delegate( '.xwb-edit-product', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var product_id = $(this).data('product');

                $.ajax({
                    url: _args.varEditProduct,
                    type: "post",
                    data: {
                        product_id: product_id
                    },
                    success: function(data){
                        var edit_data = $.parseJSON(data);
                        bootbox.dialog({
                            title: "Edit Product",
                            message: '<div class="row">'+
                                '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<form class="form-horizontal" name="form_edit_product" id="form_edit_product">'+
                                    '<input type="hidden" id="id" name="id" />'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="product_name">Product Name</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="product_name" name="product_name" type="text" placeholder="Product name" class="form-control input-md">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="prod_info">Product Description</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="prod_info" name="prod_info" type="text" placeholder="Product Description" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="category">Category</label>'+
                                        '<div class="col-md-6">'+
                                        '<select class="category" id="category" name="category" style="width:100%;">'+
                                            _args.category +
                                        '</div> '+
                                    '</div> '+
                                '</form> </div>  </div>',
                            buttons: {
                                success: {
                                    label: "Update",
                                    className: "btn-success",
                                    callback: function () {
                                        var frm_data  = $("#form_edit_product").serializeArray();

                                        $.ajax({
                                            url: _args.varUpdateProduct,
                                            type: "post",
                                            data: frm_data,
                                            success: function(data){
                                                data = $.parseJSON(data);
                                                if(data.status == true){
                                                    xwb.Noty(data.message,'success');
                                                    table_products.ajax.reload();
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

                        $("#form_edit_product").populate(edit_data);   
                        $("#category").select2({});
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
        *generate product product datatable
        *
        *
        */
        table_products = $('.table_products').DataTable({
            "ajax": {
                "url": _args.varGetProduct,
                "data": function ( d ) {

                }
              },
              "order": [[ 0, "desc" ]]
        });


        $('.xwb-add-product').addProduct();
        $('.xwb-edit-product').editProduct();
        $('.xwb-del-product').delProduct();

    });
    
}));