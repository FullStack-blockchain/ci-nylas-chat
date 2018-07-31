///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Admin dashboard Script goes here  //
///////////////////////////////////////
(function(library) {
	"use strict";
    library(window.jQuery, window, document);

}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var chart_ongoing_req_data;
    var graph_data = _args.graph_data;
    
    /**
     * Jquery functions goes here
     * 
     */
    $(function() {
		chart_ongoing_req_data = [];

		$.each(graph_data,function(index,value){
			chart_ongoing_req_data.push([new Date(index),value]);
		});


		var chart_ongoing_req_settings = {
					grid: {
						show: true,
						aboveData: true,
						color: "#3f3f3f",
						labelMargin: 10,
						axisMargin: 0,
						borderWidth: 0,
						borderColor: null,
						minBorderMargin: 5,
						clickable: true,
						hoverable: true,
						autoHighlight: true,
						mouseActiveRadius: 100
					},
					series: {
						lines: {
							show: true,
							fill: true,
							lineWidth: 2,
							steps: false
						},
						points: {
							show: true,
							radius: 4.5,
							symbol: "circle",
							lineWidth: 3.0
						}
					},
					legend: {
						position: "ne",
						margin: [0, -25],
						noColumns: 0,
						labelBoxBorderColor: null,
						labelFormatter: function(label, series) {
							return label + '&nbsp;&nbsp;';
						},
						width: 40,
						height: 1
					},
					colors: ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'],
					shadowSize: 0,
					tooltip: true,
					tooltipOpts: {
						content: "%s: %y.0",
						xDateFormat: "%d/%m",
					shifts: {
						x: -30,
						y: -50
					},
					defaultTheme: false
					},
					yaxis: {
						min: 0,
						tickSize:1,
					},
					xaxis: {
						mode: "time",
						minTickSize: [1, "day"],
						timeformat: "%Y-%m-%d",
						timezone:null,
						min: chart_ongoing_req_data[0][0],
						max: chart_ongoing_req_data[7][0]
					}
				};

		if ($("#chart_ongoing_req").length){
			console.log('Plot2');
			
			$.plot( $("#chart_ongoing_req"), 
			[{ 
				label: "Requests ", 
				data: chart_ongoing_req_data, 
				lines: { 
					fillColor: "rgba(150, 202, 89, 0.12)" 
				}, 
				points: { 
					fillColor: "#fff" } 
			}], chart_ongoing_req_settings);
			
		}
    });

}));
