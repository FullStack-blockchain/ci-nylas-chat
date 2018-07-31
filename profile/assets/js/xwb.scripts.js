///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Main jQuery/Javascript goes here  //
///////////////////////////////////////
var xwb = (function(library) {
	var func = function (){};
    var $ = window.jQuery;
    var Noty = window.Noty;

	$.extend(func, {
	    Noty: function(message,type){
    		new Noty({text: message, layout: 'topCenter', type: type,timeout: 5000}).show(); 
    	},
        /**
         * Get the CSRF hash
         * 
         * @param  {[Boolean]} arg [option to objectify the hash key from serializedArray]
         * @return {[String]}     [CSRF key]
         */
    	getCSRF: function(arg){
    		var csrf;
		    csrf = $('input.xwb-csrf').serializeArray();
		    if(arg===true){
		    	csrf = func.objectifyForm(csrf);
		    }

		    return csrf;
    	},
        /**
         * pass the CSRF hash to the hidden input
         * 
         */
    	setCSRF: function(key){
    		$('input.xwb-csrf').val(key);
    	},
        /**
         * Convert the serialized data to json object
         * 
         * @param  {[Array]} formArray [serialized Data]
         * @return Object
         */
    	objectifyForm: function (formArray) {
		  	var returnArray = {};
		  	for (var i = 0; i < formArray.length; i++){
		    	returnArray[formArray[i].name] = formArray[i].value;
		  	}
		  	return returnArray;
		}
	});
    window.func = func;
    library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var func = window.func;

    $(function() {
    	/**
    	 * Get CSRF hash before sending ajax
    	 * 
    	 */
        $.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
        	var newData;
        	if($.isArray(originalOptions.data)){
        		newData = $.merge(originalOptions.data, func.getCSRF());
                options.data = $.param(newData);
        	}else{
        		newData = $.extend({},originalOptions.data, func.getCSRF(true));
        		options.data = $.param(newData);
        	}

		});

        /**
         * Set new CSRF has to the hidden input
         */
		$( document ).ajaxSuccess(function( event, request, settings ) {
			var response = request.responseText;
			response = JSON.parse(response);
			func.setCSRF(response.csrf_hash);
		});

        /**
         * On ajax error
         */
        $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
            new Noty({
                text: jqxhr.responseText,
                layout: 'topCenter',
                type: 'error',
                timeout: 5000,
                callbacks: {
                    onClose: function() {
                        location.reload();
                    }
                }
            }).show(); 
        });
    });
    
}));

