////////////////////////////////////////////
// XWB Purchasing                         //
// Author - Jay-r Simpron                 //
// Copyright (c) 2017, Jay-r Simpron      //
//                                        //
// Recommending Approval Script goes here //
////////////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var xwb_var = window.xwb_var;
    var xwb = window.xwb;
    var bootbox = window.bootbox;
    var $ = window.jQuery;

    var func = {
        assignItems: function () {
            var values = $("input.items:checked").map(function(){return $(this).val();}).get();

            $.ajax({
                url: xwb_var.varAssignItems,
                type: "post",
                data: {
                    user_id : $("#head_users").val(),
                    request_id : xwb_var.requestID,
                    approval_id : xwb_var.approvalID,
                    items : values,
                },
                success: function(data){
                    data = $.parseJSON(data);
                    if(data.status == true){
                        xwb.Noty(data.message,'success');
                        $("#head_users").change();
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
        },
        deleteApprovingUser: function (){
            bootbox.confirm("Are you sure you want to delete this record?", function(result){ 
                if(result){

                    $.ajax({
                        url: xwb_var.varDeleteApprovingUser,
                        type: "post",
                        data: {
                            user_id : $("#head_users").val(),
                            request_id : xwb_var.requestID,
                        },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(data.status == true){
                                xwb.Noty(data.message,'success');
                                $("#head_users").change();
                                xwb_var.table_items_approval.ajax.reload();
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
        },
    };

    library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {
        /**
        *generate approval items datatable
        *
        *
        */
        _args.table_items_approval = $('.table_items_approval').DataTable({
            "ajax": {
                "url": _args.varGetApprovalItems,
                "data": function ( d ) {
                    d.user_id = $("#head_users").val();
                    d.request_id = _args.requestID;
                    d.approval_id = _args.approvalID;
                }
              },
              "order": [[ 0, "desc" ]]
        });



        $("#head_users").select2({}).on('change',function(){
            var user = $(this).val();
            if(user == 'Select User'){
                $(".assign").addClass('disabled');
            }else{
                $(".assign").removeClass('disabled');
            }

            _args.table_items_approval.ajax.reload();

            $.ajax({
                url: _args.varGetItemsForApproval,
                type: "get",
                data: {
                    user_id : user,
                    request_id : _args.requestID,
                    approval_id : _args.approvalID,
                },
                success: function(data){
                    data = $.parseJSON(data);
                    if(data.user_exists)
                        $(".delete_approve_user").removeClass('disabled');
                    else
                        $(".delete_approve_user").addClass('disabled');

                    data = data.items;
                    $(".items").prop('checked',false);
                    $.each(data, function(key,value){
                        $(".item_"+value).prop('checked',true);
                    });
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });


        $('#checkall').click(function() {
            var checked = $(this).prop('checked');
            $('input:checkbox.items').prop('checked', checked);
        });

        $(document).ajaxStart(function() {
            $("div.loader").show();
        }).ajaxStop(function() {
            $("div.loader").hide();
        });

    });
    
}));