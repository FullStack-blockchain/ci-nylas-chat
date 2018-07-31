///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Console Script goes here          //
///////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var _args = window.xwb_var;
    var xwb = window.xwb;
    var bootbox = window.bootbox;


    $.extend(func, {
        startPRIncrement: function () {
            bootbox.prompt({
                title: "Set PR Number Start",
                inputType: 'number',
                callback: function (result) {
                    if(result){
                        $.ajax({
                            url: _args.varStartPRIncrement,
                            type: "post",
                            data: {
                                pr: result
                            },
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
                    }
                }
            });
        },

        startPOIncrement: function () {
            bootbox.prompt({
                title: "Set PO Number Start",
                inputType: 'number',
                callback: function (result) {
                    if(result){
                        $.ajax({
                            url: _args.varStartPOIncrement,
                            type: "post",
                            data: {
                                po: result
                            },
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
                    }
                }
            });
        }

    });
    library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {

    });

}));
