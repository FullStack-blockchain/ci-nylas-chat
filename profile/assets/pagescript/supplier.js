///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Supplier JavaScripts goes here    //
///////////////////////////////////////

(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    var table_supplier;
    

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {

        /**
         * Add Supplier
         * 
         * @return mixed
         */
        $.fn.addSupplier = function(){
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();

                bootbox.dialog({
                    title: "Add Supplier",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<form class="form-horizontal" name="form_add_supplier" id="form_add_supplier">'+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="supplier_name">Supplier Name: </label>'+
                                '<div class="col-md-6">'+
                                    '<input id="supplier_name" name="supplier_name" type="text" placeholder="Supplier Name" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="tel_number">Tel. Number: </label>'+
                                '<div class="col-md-6">'+
                                    '<input id="tel_number" name="tel_number" type="text" placeholder="Tel. Number" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="phone_number">Mobile Number</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="phone_number" name="phone_number" type="text" placeholder="Mobile Number" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="fax">Fax</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="fax" name="fax" type="text" placeholder="Fax Number" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="email">Email</label>'+
                                '<div class="col-md-6">'+
                                    '<input id="email" name="email" type="text" placeholder="Email" class="form-control input-md"> '+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="email">Preferred Terms of Payment</label>'+
                                '<div class="col-md-6">'+
                                    '<select name="payment_terms" id="payment_terms" class="form-control">'+
                                        '<option value="">Select Option</option>'+
                                        '<option value="cash">Cash</option>'+
                                        '<option value="open_account">Open Account</option>'+
                                        '<option value="secured_account">Secured Account</option>'+
                                    '</select>'+
                                '</div> '+
                            '</div> '+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="address">Address</label>'+
                                '<div class="col-md-6">'+
                                    '<textarea name="address" id="address" class="form-control"></textarea>'+
                                '</div> '+
                            '</div> '+
                        '</form> </div>  </div>',
                    buttons: {
                        success: {
                            label: "Save",
                            className: "btn-success",
                            callback: function () {
                                var frm_data  = $("#form_add_supplier").serializeArray();

                                $.ajax({
                                    url: _args.varAddSupplier,
                                    type: "post",
                                    data: frm_data,
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            xwb.Noty(data.message,'success');
                                            table_supplier.ajax.reload();
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
        * Edit Suplier
        *
        * @param int supplier_id
        * @return mixed
        */
        $.fn.editSupplier = function (){
            $( document ).delegate( '.xwb-edit-supplier', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var supplier_id = $(this).data('supplier');

                $.ajax({
                    url: _args.varEditSupplier,
                    type: "post",
                    data: {
                        supplier_id: supplier_id
                    },
                    success: function(data){
                        var edit_data = $.parseJSON(data);
                        bootbox.dialog({
                            title: "Edit Department",
                            message: '<div class="row">  ' +
                                '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                                '<form class="form-horizontal" name="form_edit_supplier" id="form_edit_supplier"> ' +
                                    '<input type="hidden" id="id" name="id" />'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="supplier_name">Supplier Name: </label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="supplier_name" name="supplier_name" type="text" placeholder="Supplier Name" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="tel_number">Tel. Number: </label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="tel_number" name="tel_number" type="text" placeholder="Tel. Number" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="phone_number">Mobile Number</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="phone_number" name="phone_number" type="text" placeholder="Mobile Number" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="fax">Fax</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="fax" name="fax" type="text" placeholder="Fax Number" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="email">Email</label>'+
                                        '<div class="col-md-6">'+
                                            '<input id="email" name="email" type="text" placeholder="Email" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="email">Preferred Terms of Payment</label>'+
                                        '<div class="col-md-6">'+
                                            '<select name="payment_terms" id="payment_terms" class="form-control">'+
                                                '<option value="">Select Option</option>'+
                                                '<option value="cash">Cash</option>'+
                                                '<option value="open_account">Open Account</option>'+
                                                '<option value="secured_account">Secured Account</option>'+
                                            '</select>'+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="address">Address</label>'+
                                        '<div class="col-md-6">'+
                                            '<textarea name="address" id="address" class="form-control"></textarea>'+
                                        '</div> '+
                                    '</div> '+
                                '</form> </div>  </div>',
                            buttons: {
                                success: {
                                    label: "Update",
                                    className: "btn-success",
                                    callback: function () {
                                        var frm_data  = $("#form_edit_supplier").serializeArray();

                                        $.ajax({
                                            url: _args.varUpdateSupplier,
                                            type: "post",
                                            data: frm_data,
                                            success: function(data){
                                                data = $.parseJSON(data);
                                                if(data.status == true){
                                                    xwb.Noty(data.message,'success');
                                                    table_supplier.ajax.reload();
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
                        $("#form_edit_supplier").populate(edit_data);   
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
        * Delete Supplier
        * @param int id
        *
        * @return mixed
        */
        $.fn.deleteSupplier = function (){
            $( document ).delegate( '.xwb-del-supplier', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var supplier_id = $(this).data('supplier');

                bootbox.confirm("Are you sure you want to delete this record?", function(result){ 
                    if(result){
                        $.ajax({
                            url: _args.varDeleteSupplier,
                            type: "post",
                            data: {
                                'id':supplier_id
                            },
                            success: function(data){
                                data = $.parseJSON(data);
                                if(data.status == true){
                                    xwb.Noty(data.message,'success');
                                    table_supplier.ajax.reload();
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
        *generate supplier list datatable
        *
        *
        */
        table_supplier = $('.table_supplier').DataTable({
            "ajax": {
                "url": _args.varGetSupplier,
                "data": function ( d ) {
                    //d.branches = $('#branches').val();
                }
              },
              "order": [[ 0, "desc" ]]
        });


        
        $('.xwb-add-supplier').addSupplier();
        $('.xwb-edit-supplier').editSupplier();
        $('.xwb-del-supplier').deleteSupplier();
        
    });
    
}));