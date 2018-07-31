///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Settings Script goes here         //
///////////////////////////////////////



(function(library) {
    "use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {

        /**
         * Update Settings function
         * 
         */
        $.fn.updateSettings = function(){
            this.bind( "click", function(e) {
            var form_data = $("#xwb-form-settings").serializeArray();
            var csrf = xwb.getCSRF();
            var frm_data = $.extend(csrf,form_data);
                $.ajax({
                    url: _args.varUpdateSettings,
                    type: "post",
                    data: frm_data,
                    success: function(data){
                        data = $.parseJSON(data);
                        xwb.setCSRF(data.csrf_hash);
                        if(data.status == true){
                            xwb.Noty(data.message, 'success'); 
                            bootbox.hideAll();
                        }else{
                            xwb.Noty(data.message, 'error'); 
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


        $(document).on('change', '#logo', function(){
            $("div.loader").show();
            $("#xwb-form-settings").ajaxForm({
                //target: '#preview'
                type: "post",
                beforeSubmit: function(arr, $form, options){
                    var csrf = xwb.getCSRF();
                    arr = $.merge(arr,csrf);
                },
                success: function(data){
                    data = $.parseJSON(data);
                    xwb.setCSRF(data.csrf_hash);
                    if(data.status == true){
                        $("#company_logo").prop('src',data.src);
                        xwb.Noty(data.message, 'success'); 
                        $("div.loader").hide();
                    }else{
                        xwb.Noty(data.message, 'error'); 
                        $("div.loader").hide();
                    }
                },
            }).submit();
        });


        ////////////////////////////////////
        // jQuery function call goes here //
        ////////////////////////////////////
       $('.xwb-update-settings').updateSettings();

    });
    
}));