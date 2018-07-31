/////////////////////////////////////////
// XWB Purchasing                      //
// Author - Jay-r Simpron              //
// Copyright (c) 2017, Jay-r Simpron   //
//                                     //
// Property dashboard Script goes here //
/////////////////////////////////////////
(function(library) {
	"use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var Gauge = window.Gauge;

    /**
     * Jquery functions goes here
     * 
     */
    $(function() {
		var init_gauge = function () {
			var total_level = 0;
			var current_level = 0;
					
			if( typeof (Gauge) === 'undefined'){ return; }
				
				
			var chart_gauge_settings = {
				  lines: 12,
				  angle: 0,
				  lineWidth: 0.4,
				  pointer: {
					  length: 0.75,
					  strokeWidth: 0.042,
					  color: '#1D212A'
				  },
				  limitMax: 'false',
				  colorStart: '#1ABC9C',
				  colorStop: '#1ABC9C',
				  strokeColor: '#F0F3F3',
				  generateGradient: true
			};
				

			/* Gauge loop initialize */
			$.each(_args.gauges,function(index,value){
				var chart_gauge_elem = document.getElementById(value[0]);
				var chart_gauge = new Gauge(chart_gauge_elem).setOptions(chart_gauge_settings);

				chart_gauge.maxValue = 100;
				chart_gauge.animationSpeed = 32;
				total_level = 100 / _args.level_count;
				current_level = total_level * parseInt(value[1]);

				chart_gauge.set(current_level);
				chart_gauge.setTextField(document.getElementById("gauge-text-"+value[2]));
			});
				
					
			
		};
			init_gauge();
    });

}));
