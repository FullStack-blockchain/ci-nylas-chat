///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Update canvassed items goes here  //
///////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var xwb = window.xwb;
    var _args = window.xwb_var;
    var bootbox = window.bootbox;

    var total_per_supplier;

    $.extend(func, {

        /**
         * View total amount per supplier
         * 
         * @param  {Number} request_id [Request ID]
         * @return null
         */
        supplierSummary: function (request_id){
            bootbox.dialog({
                title: 'Total Per Supplier',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table-total-per-supplier">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Supplier Name</th>'+
                                                '<th>Total</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                        '</tbody>'+
                                        '<tfoot>'+
                                        '</tfoot>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                buttons:{
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }

            }).init(function(){
                total_per_supplier = $('.table-total-per-supplier').DataTable({
                    "ajax": {
                        "url": _args.varSupplierSummary,
                        "data": function ( d ) {
                            d.request_id = request_id;
                        },
                        "dataSrc": function (json) {
                        $(".table-total-per-supplier tfoot").html(json.footer);
                        return json.data;
                        },
                      },
                      "order": [[ 0, "desc" ]]
                });
            });


        },



        /**
         * Update canvassed items
         * 
         * @return json
         */
        updateItem: function (){
            var frm_data = $("#form_update_items").serializeArray();
            frm_data.push({name:'net_total', value:$(".net_total").text()});
            frm_data.push({name:'request_id', value:_args.request_id});
            frm_data.push({name:'canvass_id', value:_args.canvass_id});
            frm_data.push({name:'init_canvass_date', value:$("#init_canvass_date").val()});


            var error = false;
            $(".table_products tbody tr").each(function(i,v){
                if($(v).find('input[type=checkbox]:checked').length==0){
                    xwb.Noty('You must select at least one (1) supplier per item','error');
                    error = true;
                }
            });
            
            
            if(error){
                return false;
            }

            $.ajax({
                url: _args.varUpdateItems,
                type: "post",
                data: frm_data,
                success: function(data){
                    data = $.parseJSON(data);
                    if(data.status == true){
                        xwb.Noty(data.message,'success');
                         $(".updatebtn").addClass('disabled');
                         location.reload();
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



    });

    library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;


    $(function() {

        /**
         * Get the Net total
         */
        var net_total = function (){
            var netTotal = 0;
            $('.row-total').each(function(i,n){
                var row_total = parseFloat($(n).val(),10);
                row_total = isNaN(row_total)?0:row_total;
                netTotal += parseInt(row_total,10);
            });
            $('.net_total').text(netTotal);
        };



        /**
        * Determine if numeric or alphabet
        *
        * @param int evt
        *
        * @return boolean
        */
        var isNumeric = function (evt) {
            var theEvent, key, arr;
            theEvent = evt || window.event;
            key = theEvent.keyCode || theEvent.which;
            key = theEvent.key;

            arr = ['Alt','Tab','Home','End','Shift','Control','ArrowLeft','ArrowRight','Delete','Backspace',undefined];

            if($.inArray( key, arr ) < 0){
                if (key.length == 0) return;
                var regex = /^[0-9.,\b]+$/;
                if (!regex.test(key)) {
                    theEvent.returnValue = false;
                    if (theEvent.preventDefault) theEvent.preventDefault();
                    return false;
                }else{
                    return true;
                }
            }else{
                return true;
            }
        };





        /**
         * Unit Price value change function
         * 
         * @return {mixed}
         */
        $(".unit_price").on('change keydown keyup',function (e){
            if(!isNumeric(e))
                return;
            var unit_price = $(this).val();
            var qty = $(this).parent('div').next().children('input').val();

            var total = (parseFloat(qty) * parseFloat(unit_price));

            total = (isNaN(total)?0:total);

            $(this).parent('div').next().next().children('input').val(total);

            var supplierTotal = 0;
            $(this).closest('tr').find('.supplier-total').each(function(i,n){
                var isChecked = $(n).closest('td').find('input:checked').length;
                if(isChecked>0){
                    supplierTotal += parseInt($(n).val(),10);
                }
            });

            $(this).closest('tr').find('.row-total').val(supplierTotal);
            net_total();

        });



        /**
         * Quantity value change function
         * 
         * @return mixed
         */
        $(".qty").on('change keydown keyup',function (e){
            if(!isNumeric(e))
                return;
            var unit_price = $(this).parent('div').prev().children('input').val();
            var qty = $(this).val();

            var total = (parseFloat(qty) * parseFloat(unit_price));

            total = (isNaN(total)?0:total);


            $(this).parent('div').next().children('input').val(total);

            var supplierTotal = 0;
            $(this).closest('tr').find('.supplier-total').each(function(i,n){
                var isChecked = $(n).closest('td').find('input:checked').length;
                if(isChecked>0){
                    supplierTotal += parseInt($(n).val(),10);
                }
            });

            $(this).closest('tr').find('.row-total').val(supplierTotal);
            net_total();
        });



        /**
         * Include company canvassed
         * 
         * @return mixed
         */
        $(".supplier-check").on('ifChecked ifUnchecked',function() {

            var supplierTotal = 0;
            $(this).closest('tr').find('.supplier-total').each(function(i,n){
                var isChecked = $(n).closest('td').find('input:checked').length;
                if(isChecked>0){
                    supplierTotal += parseInt($(n).val(),10);
                }
            });

            $(this).closest('tr').find('.row-total').val(supplierTotal);
            net_total();
        });




        $('.item_price').mask("#,##0.00", {reverse: true});

        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        });
        $("#init_canvass_date").datepicker({
            minDate:0,
        });


        $(".unit-measurement").select2();

        $(".supplier").select2({
            placeholder: "Select Supplier",
            allowClear: true,
            selectOnBlur: true,
            tags: true,

            createSearchChoice: function (term) {
                var lastResults;
                if(lastResults.some(function(r) { return r.text == term; })) {
                    return { id: term, text: term };
                }else {
                    return { id: '', text: term + " (new)" };
                }
            }
        });


    });
    return _args;
}));
