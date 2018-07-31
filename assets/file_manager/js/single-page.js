$(document).ready(function(){
	$("table.table.list-view tr td").click(function(){
		var query_string = $(this).data("directory-url");
		if( $.type( query_string ) != "undefined" ){
			location.href = query_string;
		}
	});
	$(document).on("click", "#rename-button", function () {
		var myBookId = $(this).data('id');
		$(".modal-body #bookId").val( myBookId );
	});

	$(document).on("click", "#download-folder", function () {
		var mydownload = $(this).data('id');
		$(".modal-body.download #download").val( mydownload );
	});

	$(document).on("click", ".wis_lock", function () {
		var lock = $(this).val();
		var mydirect = $(this).data('id');
		$(".modal-body.lock #mydirect").val( mydirect );
		$(".modal-body.lock #lock_count").val( lock );
	});

	function getRandomInt(max) {
		return Math.floor(Math.random() * Math.floor(max));
	}
	$(document).on("click", "#share-link", function () {
		var myshare = $(this).data('id');
		var append_string = '<input  type="hidden" name="share_url[]" id="share_url" value="'+myshare+'">';
		var number = getRandomInt(100000);
		var pageURL = window.location.hostname;
        var url = pageURL+'/authentication/download_file?id='+number;
		$(".modal-body.share #append_string").html( append_string );
		$(".modal-body.share #copylink").val( url );
		$(".modal-body.share #share_cpount").val( number );		
	});
	

	$(document).on("click", "#share-file-button", function () {
		var append_string = '';
		$( "input[type=checkbox]:checked").each(function(index,value){				
			var myshare = $(value).val();
			append_string += '<input  type="hidden" name="share_url[]" id="share_url" value="'+myshare+'">';			
		})		
		var number = getRandomInt(100000);
		var pageURL = window.location.hostname;
		var url = pageURL+'/authentication/download_file?id='+number;
		$(".modal-body.share #append_string").html( append_string );
		$(".modal-body.share #copylink").val( url );
		$(".modal-body.share #share_cpount").val( number );		
	});
	// $(document).toggle("click", ".k_menu_7", function () {
	// 	 $(".k_menu-item").css("display", "block");
	// });
	$('.k_menu_7 .md-48').click(function(){
		$('.k_menu-item').toggle();
	})
	$('.k_menu_5 .k_menu-open-button').click(function(){
		 $(this).nextAll().toggleClass('k_active_button'); 
	})

});


