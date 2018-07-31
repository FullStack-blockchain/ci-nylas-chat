//////////////////////////////////////////
// XWB Purchasing                       //
// Author - Jay-r Simpron               //
// Copyright (c) 2017, Jay-r Simpron    //
//                                      //
// Edit Request JavaScripts goes here   //
//////////////////////////////////////////
(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var xwb = window.xwb;
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var Noty = window.Noty;
    var lastResults;

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {
        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        });
        $("#date_needed").datepicker({
            minDate:0,
        });

        /**
         * Select2 for select supplier
         */
        $(".supplier").select2({
            placeholder: "Select Supplier",
            allowClear: true,
            selectOnBlur: true,
            tags: true,

            createSearchChoice: function (term) {
                if(lastResults.some(function(r) { return r.text == term; })) {
                    return { id: term, text: term };
                }else {
                    return { id: '', text: term + " (new)" };
                }
            }
        });


        /**
         * Select2 for select product
         */
        $(".product").select2({
            placeholder: "Select Product",
            allowClear: true,
            selectOnBlur: true,
            tags: true,

            createSearchChoice: function (term) {
                if(lastResults.some(function(r) { return r.text == term; })) {
                    return { id: term, text: term };
                }else {
                    return { id: '', text: term + " (new)" };
                }
            }
        });


        /**
         * Add item dialog box
         */
        $.fn.addItem = function (){
            var product_id, $tr_to_insert, product, isnum;
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                bootbox.dialog({
                    title: "Add Item",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<form class="form-horizontal" name="form_prod" id="form_prod">'+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label" for="product">Select Product Item</label>'+
                                '<div class="col-md-6">'+
                                '<select class="product" id="product" name="product" style="width:100%;">'+
                                    _args.prodOptions +
                                '</div> '+
                            '</div> '+
                        '</form> </div>  </div>',
                    buttons: {
                        success: {
                            label: "Add",
                            className: "btn-success",
                            callback: function () {

                                product = $("#product option:selected").text();
                                product_id = $("#product").val();

                                $tr_to_insert = $(trToInsert());
                                $tr_to_insert.find('td:eq( 1 ) input.new_product_name').val(product);
                                $tr_to_insert.find('td:eq( 4 ) select.new_supplier').select2({
                                    placeholder: "Select Supplier",
                                    allowClear: true,
                                    selectOnBlur: true,
                                    tags: true,

                                    createSearchChoice: function (term) {
                                        if(lastResults.some(function(r) { return r.text == term; })) {
                                            return { id: term, text: term };
                                        }else {
                                            return { id: '', text: term + " (new)" };
                                        }
                                    }
                                });
                                
                                
                                isnum = /^\d+$/.test(product_id);

                                if(!isnum){
                                    $tr_to_insert.find('td:eq( 1 ) input.new_product_name').prop('readonly',false);
                                    $tr_to_insert.find('td:eq( 1 ) input.product_id').val('');
                                }else{
                                    $tr_to_insert.find('td:eq( 1 ) input.product_id').val(product_id);
                                }

                                $('table.table_products tbody').append($tr_to_insert);
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
                    setTimeout(function(){
                        $('.bootbox.modal').removeAttr('tabindex');
                    },100);


                    $("#product").select2({
                        placeholder: "Select Product",
                        allowClear: true,
                        selectOnBlur: true,
                        tags: true,

                        createSearchChoice: function (term) {
                            if(lastResults.some(function(r) { return r.text == term; })) {
                                return { id: term, text: term };
                            }else {
                                return { id: '', text: term + " (new)" };
                            }
                        }
                    });
                }); 

                
            });
        };


        /**
         * Update the request
         * 
         */
        $.fn.updateRequest = function (){
            $(document).on('click','.xwb-update-request',function(e){
                e.preventDefault();
                e.stopPropagation();
                var request_id = $(this).data('request');

                var frm_data = $("#form_update_request").serializeArray();

                frm_data.push({name:'request_id', value:request_id});
                
                $.ajax({
                    url: _args.varUpdateItemRequest,
                    type: "post",
                    data: frm_data,
                    success: function(data){
                        data = $.parseJSON(data);
                        if(data.status == true){
                            new Noty({text: data.message, layout: 'topCenter', type: 'success',timeout: 3000}).on('onClose',function(){
                                $(".updatebtn").addClass('disabled');
                                location.replace(_args.view_req_link+'/'+request_id);
                            }).show();

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
            });
        };


        /**
         * Table row to Insert from add item
         * 
         */
        var trToInsert = function (){
            var count,supp, tr_to_insert;
            count = $(".table_products tbody tr").length + 1;
            supp = '<td>'+
                            '<select style="width:100%;" name="new_supplier[]" class="new_supplier new_supplier_'+count+'">'+
                                _args.supplierOpt+
                            '</select>'+
                        '</td>'; 

            console.log(_args.group_name);
            if(_args.group_name == 'members'){
                supp = "";
            }
            
            tr_to_insert = '<tr>'+
                            '<td>'+
                                '<a class="btn btn-danger btn-xs xwb-remove-item" href="javascript:;">'+
                                    '<i class="fa fa-minus"></i>'+
                                '</a>'+
                            '</td>'+
                            '<td>'+
                            '<input type="text" value="" readonly name="new_product_name[]" class="form-control new_product_name new_product_name_'+count+'" />'+
                            '<input type="hidden" name="product_id[]" class="form-control col-md-7 col-xs-12 product_id product_id_'+count+'">'+
                            '</td>'+
                            '<td><input type="text" value="" name="new_product_description[]" class="form-control new_product_description_'+count+'" /></td>'+
                            '<td><input type="text" data-count="'+count+'" value="" name="new_quantity[]" class="form-control new_quantity new_quantity_'+count+'" /></td>'+
                            supp+
                        '</tr>';
            return tr_to_insert;
        };



        /**
        * Delete from database
        *
        * @param int request_id
        *
        * @return mixed
        */
        $.fn.deleteItem = function (item_id){
            $(document).on('click','.xwb-delete-item',function(e){
                e.preventDefault();
                e.stopPropagation();
                var item_id = $(this).data('item');

                bootbox.dialog({
                    title: 'Delete Item',
                    message: '<div class="row">'+
                                '<form class="form-horizontal">'+
                                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                        '<div class="form-group">'+
                                            '<label class="col-md-4 control-label" for="message">Message</label>'+
                                            '<div class="col-md-6">'+
                                                '<textarea name="message" id="message" class="form-control"></textarea>'+
                                            '</div> '+
                                        '</div> '+
                                    '</div>'+
                                '</form>'+
                            '</div>',
                    buttons:{
                        assign: {
                            label: "Delete",
                            className: "btn-danger",
                            callback: function () {
                                $.ajax({
                                    url: _args.varRemoveItem,
                                    type: "post",
                                    data: {
                                        item_id:item_id,
                                        message:$("#message").val(),
                                    },
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            new Noty({text: data.message, layout: 'topCenter', type: 'success',timeout: 3000}).on('onClose',function(){
                                                if(_args.group_name == 'members'){
                                                    location.reload();
                                                }else{
                                                    location.reload();
                                                    //_args.table_request_list.ajax.reload();
                                                }
                                            }).show();
                                            
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
            });

        };


        /*Remove temporary added item*/

        $.fn.removeItem = function (){
            $(document).on('click','.xwb-remove-item',function(e){
                e.preventDefault();
                e.stopPropagation();
                var $tr;
                $tr = $(this).closest('tr');
                if($('.table_products tbody>tr').length <= 1){
                    xwb.Noty('Item must have a minimum of 1','error');
                    return;
                }
                $tr.remove(); 
            });   
        };



        $(".xwb-add-item").addItem();
        $('.xwb-remove-item').removeItem();
        $('.xwb-delete-item').deleteItem();
        $('.xwb-update-request').updateRequest();

    });
    
}));

