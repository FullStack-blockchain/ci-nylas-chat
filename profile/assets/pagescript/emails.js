///////////////////////////////////////////////////
// XWB Purchasing                                //
// Author - Jay-r Simpron                        //
// Copyright (c) 2017, Jay-r Simpron             //
//                                               //
// Email template settings JavaScripts goes here //
///////////////////////////////////////////////////
(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var CKEDITOR = window.CKEDITOR;
    var xwb = window.xwb;
    var table_process_emails;
    

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {


        /**
        * Edit department
        * @param int dept_id
        *
        * @return mixed
        */
        $.fn.editMessage = function (){
            $( document ).delegate( '.xwb-edit-email', "click", function(e){
                e.preventDefault();
                e.stopPropagation();
                var process_key = $(this).data('key');
                $.ajax({
                    url: _args.varGetEmail,
                    type: "post",
                    data: {
                        process_key: process_key
                    },
                    success: function(data){
                        var edit_data = $.parseJSON(data);
                        bootbox.dialog({
                            title: "Edit Emails",
                            message: '<div class="row">  ' +
                                '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                                '<form class="form-horizontal" name="form_edit_emails" id="form_edit_emails"> ' +
                                    '<input type="hidden" id="process_key" name="process_key" value="'+process_key+'" />'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-2 control-label" for="subject">Subject</label>'+
                                        '<div class="col-md-10">'+
                                            '<input id="subject" value="'+edit_data.subject+'" name="subject" type="text" placeholder="Subject" class="form-control input-md"> '+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-2 control-label" for="message">Message</label>'+
                                        '<div class="col-md-10">'+
                                            '<textarea name="message" id="message" class="form-control">'+edit_data.message+'</textarea>'+
                                        '</div> '+
                                    '</div> '+
                                '</form> </div>  </div>',
                            size: 'large',
                            buttons: {
                                success: {
                                    label: "Update",
                                    className: "btn-success",
                                    callback: function () {
                                        var frm_data  = $("#form_edit_emails").serializeArray();
                                        frm_data.push({name:'email_message',value:CKEDITOR.instances.message.getData()});
                                        $.ajax({
                                            url: _args.varUpdateEmail,
                                            type: "post",
                                            data: frm_data,
                                            success: function(data){
                                                data = $.parseJSON(data);
                                                if(data.status == true){
                                                    xwb.Noty(data.message,'success');
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
                        CKEDITOR.replace( 'message',{
                            toolbarGroups : [
                                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                                    { name: 'forms', groups: [ 'forms' ] },
                                    '/',
                                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                                    { name: 'links', groups: [ 'links' ] },
                                    { name: 'insert', groups: [ 'insert' ] },
                                    '/',
                                    { name: 'styles', groups: [ 'styles' ] },
                                    { name: 'colors', groups: [ 'colors' ] },
                                    { name: 'tools', groups: [ 'tools' ] },
                                    { name: 'others', groups: [ 'others' ] },
                                    { name: 'about', groups: [ 'about' ] }
                                ],
                            removeButtons : 'Save,NewPage,Form,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Checkbox'
                        });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log(XMLHttpRequest);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            });
        };


        $.fn.scRef = function(){
            this.bind( "click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                bootbox.dialog({
                    title: "Shortcodes Reference",
                    message: '<div class="row">  ' +
                        '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                            '<em class="text-danger">Please note that some shortcode will work only in some specific process. Example is using the [po_number] shortcode in the new request process.</em>'+
                            '<ul>'+
                                '<li>'+
                                    '<label>[name_from] </label> - <span>The name of the sender</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[email_from] </label> - <span>The email of the sender</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[name_to] </label> - <span>The Name of the recipient</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[email_to] </label> - <span>The Email of the recipient</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[request_number] </label> - <span>Purchase request number</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[request_name] </label> - <span>Purchase request name/type</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[date_needed] </label> - <span>Purchase request date needed</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[message] </label> - <span>Message of the sender</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[po_num] </label> - <span>Purchase order number</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[po_number] </label> - <span>Purchase order number</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[item_number] </label> - <span>Item ID number</span>'+
                                '</li>'+
                                '<li>'+
                                    '<label>[item_name] </label> - <span>Item name</span>'+
                                '</li>'+
                            '</ul>'+
                        '</div>  </div>',
                    buttons: {
                        success: {
                            label: "Update",
                            className: "btn-success",
                            callback: function () {
                                var frm_data  = $("#form_change_pass").serializeArray();

                                $.ajax({
                                    url: _args.varChangePass,
                                    type: "post",
                                    data: frm_data,
                                    success: function(data){
                                        data = $.parseJSON(data);
                                        if(data.status == true){
                                            xwb.Noty(data.message,'success');
                                            table_users.ajax.reload();
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
            });
        };
        
        table_process_emails = $('#table_process_emails').DataTable({
            "order": [[ 0, "desc" ]]
        });



       $('.xwb-edit-email').editMessage();
       $('.xwb-sc-ref').scRef();
    });
    
}));