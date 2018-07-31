///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// New Request JavaScripts goes here //
///////////////////////////////////////
(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    var Noty = window.Noty;
    var table_attachment;
    var table_attachment_preview;
    var step_num, curstep, step_num_finish;
    var obj;
    var height;

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {
        

        $('#wizard').smartWizard({
            keyNavigation:false,
            noForwardJumping: true

        });

        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        });
        $("#date_needed").datepicker({
            minDate:0,
        });

        $('.new_request_wizard .actionBarClone .next').bind('click', function(event) {
            step_num = $('.wizard ul li a.selected').find('.step_no').text();
            
            obj = $("#step-"+step_num+" form").serializeArray();
            obj = $.merge(obj , [{name:'step', value: step_num}] );

            $.ajax({
                  type: "POST",
                  url: _args.varNewRequestSteps,
                  data: obj,
                  dataType: "json",
                  success: function(data){

                      if(data.status==true){
                          $(".new_request_wizard .buttonNext").click();

                          setTimeout(function(){
                            step_num_finish = $("#wizard").smartWizard('currentStep');
                            if(step_num_finish==3){
                              $(".new_request_wizard .actionBarClone .finish").removeClass('disabled');
                              $(".new_request_wizard .actionBarClone .next").addClass('disabled');
                              $(".review_request").html(data.content);
                              height = $("#step-3").outerHeight();
                              $(".stepContainer").css({height: (height + 10)+'px'});

                            }else{
                              $(".new_request_wizard .actionBarClone .finish").addClass('disabled');
                              $(".new_request_wizard .actionBarClone .next").removeClass('disabled');
                            }
                          },100);
                          Noty.closeAll();
                      }else{
                          
                        xwb.Noty(data.message,'error');
                        return false;
                      }
                  },
                  error: function(xhr, status, error) {
                          var err = xhr.responseText;
                          console.log(xhr);
                          console.log(err.Message);
                          console.log(status);
                          console.log(error);
                          console.log(xhr.responseText);               
                    }
                });
        });

        $('.new_request_wizard .actionBarClone .finish').bind('click', function(event) {
            step_num = curstep = $("#wizard").smartWizard('currentStep');
            
            /*obj = $("#step-"+step_num+" form").serializeArray();
            obj = $.merge(obj , [{name:'step', value: step_num}] );*/
            

            $.ajax({
                  type: "POST",
                  url: _args.varFileRequest,
                  data: {
                    step:step_num
                  },
                  dataType: "json",
                  success: function(data){

                    if(data.status == true){
                        new Noty({text: data.message, layout: 'topCenter', type: 'success',timeout: 3000}).on('onClose',function(){
                            window.location.replace(_args.varRequest);
                        }).show();

                        $('.new_request_wizard .actionBarClone .finish').addClass('disabled');
                    }else{
                        xwb.Noty(data.message,'error');
                        return false;
                    }
                  },
                  error: function(xhr, status, error) {
                          //var err = eval("(" + xhr.responseText + ")");
                          var err = xhr.responseText;
                          console.log(xhr);
                          console.log(err.Message);
                          console.log(status);
                          console.log(error);
                          console.log(xhr.responseText);               
                    }
                });
        });


        $('.new_request_wizard .actionBarClone .prev').bind('click', function(event) {
            $(".new_request_wizard .buttonPrevious").click();
            $(".new_request_wizard .actionBarClone .finish").addClass('disabled');
            curstep = $("#wizard").smartWizard('currentStep');

            if(curstep < 3){
                $(".new_request_wizard .actionBarClone .next").removeClass('disabled');
            }else{
                $(".new_request_wizard .actionBarClone .next").addClass('disabled');
            }

            for (var i = curstep; i <= 3; i++) {
                $("#wizard").smartWizard('disableStep', i);
            }
            
        });

        $(".wizard_steps a").click(function(){
            curstep = $(this).find('.step_no').text();
            for (var i = curstep; i <= 3; i++) {
                $("#wizard").smartWizard('disableStep', i);
            }
        });


        /**
        * Adjust hieght of the wizzard container
        *
        * @return mixed
        */
        function adjustHeight() {
            height = $("#step-2").outerHeight();
            $(".stepContainer").css({height: (height + 30)+'px'});
        }


        /**
        * Remove Item
        *
        *
        * @return mixed
        */
        $.fn.removeItem = function(){
            $( document ).delegate( '.xwb-remove-item', "click", function(e){
                e.preventDefault();
                e.stopPropagation();

                var $tr;
                if($(".table_items tbody tr").length>0){
                    $tr = $(this).closest('tr');
                    $tr.remove();    
                    adjustHeight();
                }
            });
        };





        /**
        * Add Item on rquest
        *
        *
        * @return mixed
        */
        $.fn.addItem = function(){
            var product, product_id, $tr_to_insert, trcount, isnum, lastResults;
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
                                        '</select> '+
                                        '<div class="help">If the requested item is not listed, just simply type the word and click or enter to create and add such name on the list</div>'+
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

                                $tr_to_insert = $(_args.tr_to_insert);
                                $tr_to_insert.find('td:first input.item').val(product);
                                
                                trcount = $('table.table_items tbody tr').length + 1;
                                $tr_to_insert.addClass('row_'+trcount);

                                isnum = /^\d+$/.test(product_id);

                                if(!isnum){
                                    $tr_to_insert.find('td:first input.item').prop('readonly',false);
                                    $tr_to_insert.find('td:first input.product_id').val('');
                                }else{
                                    $tr_to_insert.find('td:first input.product_id').val(product_id);
                                }

                                $('table.table_items tbody').append($tr_to_insert);

                                $($tr_to_insert).find('td .unit-measurement').select2();

                                adjustHeight();

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

                /*remove tab-index for the select2 compatiblity of bootbox*/
                setTimeout(function(){
                    $('.bootbox.modal').removeAttr('tabindex');
                },100);
            });
        };



        /**
        * View Attachment for the items
        *
        *
        * @return mixed
        */
        $.fn.viewAttachment = function () {
            $( document ).delegate( '.xwb-view-attachment', "click", function(e){
                e.preventDefault();
                e.stopPropagation();

                var row = $(this).closest('tr').attr('class');
                row = row.split("_");
                row = row[1];
                bootbox.dialog({
                    title: "Add Attachment",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                            '<div class="panel panel-success">'+
                                '<div class="panel-heading">'+
                                  '<h3 class="panel-title">Attachment</h3>'+
                                '</div>'+
                                '<div class="panel-body">'+
                                    '<form class="form-horizontal" name="form_add_attachment" id="form_add_attachment">'+
                                        '<input type="hidden" name="row" id="row" value="'+row+'">'+
                                        '<div class="form-group">'+
                                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                                '<div class="input-group">'+
                                                    '<input id="attachment" name="attachment" type="file">'+
                                                '</div>'+
                                            '</div> '+
                                            '<hr />'+
                                            '<span class="input-group-btn">'+
                                                '<button type="button" class="btn btn-primary xwb-upload-attachment">Upload</button>'+
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
                }); 

                /**
                *generate attachment datatable
                *
                *
                */
                table_attachment = $('.table_attachment').DataTable({
                    "ajax": {
                        "url": _args.varGetAttachment,
                        "data": function ( d ) {
                            d.row = $('#row').val();
                        }
                      },
                      "order": [[ 0, "desc" ]]
                });
            });
        };



        /**
        * View Attachment for the items preview
        *
        * @param element el
        *
        * @return mixed
        */
        $.fn.viewAttachmentPreview = function (el) {
            $( document ).delegate( '.xwb-view-attach-preview', "click", function(e){
                e.preventDefault();
                e.stopPropagation();

                var row = $(this).closest('tr').attr('class');
                row = row.split("_");
                row = row[1];
                bootbox.dialog({
                    title: "Add Attachment",
                    message: '<div class="row">'+
                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                            '<div class="panel panel-success">'+
                                '<div class="panel-heading">'+
                                  '<h3 class="panel-title">Attachment</h3>'+
                                '</div>'+
                                '<div class="panel-body">'+
                                    '<div class="table-responsive">'+
                                        '<input type="hidden" name="row" id="row" value="'+row+'">'+
                                        '<table class="table table_attachment_preview">'+
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
                }); 

                /**
                *generate attachment datatable
                *
                *
                */
                table_attachment_preview = $('.table_attachment_preview').DataTable({
                    "ajax": {
                        "url": _args.varGetAttachmentPreview,
                        "data": function ( d ) {
                            d.row = $('#row').val();
                        }
                      },
                      "order": [[ 0, "desc" ]]
                });
            });

        };





        /**
        * Upload attachment
        *
        *
        * @return void
        */
        $.fn.submitAttachment = function (){
            $( document ).delegate( '.xwb-upload-attachment', "click", function(e){
                e.preventDefault();
                e.stopPropagation();

                $("#form_add_attachment").ajaxForm({
                    url: _args.varAddAttachment,
                    type: "post",
                    beforeSubmit: function(arr, $form, options){
                        var csrf = xwb.getCSRF();
                        arr = $.merge(arr,csrf);
                    },
                    success: function(data){
                        data = $.parseJSON(data);
                        xwb.setCSRF(data.csrf_hash);
                        if(data.status == true){
                            xwb.Noty(data.message,'success');
                            table_attachment.ajax.reload();
                            $("#attachment").val('');
                            
                        }else{
                            xwb.Noty(data.message,'error');
                        }
                    },
                }).submit();
            });
        };


        /**
        * Remove temporary file attachment
        *
        *
        * @return void
        */
        $.fn.deleteReqAttachment = function (row,key){
            $( document ).delegate( '.xwb-remove-attachment', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var key = $(this).data('key');
                var row = $(this).data('row');

                bootbox.confirm("Are you sure you want to delete this attachment?", function(result){ 
                    if(result){
                        $.ajax({
                            url: _args.varRemoveAttachment,
                            type: "post",
                            data: {
                                row:row,
                                key:key,
                            },
                            success: function(data){
                                data = $.parseJSON(data);
                                if(data.status == true){
                                    xwb.Noty(data.message,'success');
                                    table_attachment.ajax.reload();
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


        $('.xwb-add-item').addItem();
        $('.xwb-remove-item').removeItem();
        $('.xwb-view-attachment').viewAttachment();
        $('.xwb-upload-attachment').submitAttachment();
        $('.xwb-remove-attachment').deleteReqAttachment();
        $('.xwb-view-attach-preview').viewAttachmentPreview();
        
        


        $(document).ajaxStart(function() {
            $("div.loader").show();
        }).ajaxStop(function() {
            $("div.loader").hide();
        });


    });
    
}));