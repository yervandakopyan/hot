$(document).ready(function(){

	$('#personal_btn').click(function() {
		if($('#disp_info_div').css('display') != 'none') {
			$('#disp_info_div').hide();
		}
		
		if($('#image_div').css('display') != 'none') {
			$('#image_div').hide();
		}

		$('#pers_info_div').toggle();
	});

	$('#display_btn').click(function(){
		if($('#pers_info_div').css('display') != 'none') {
			$('#pers_info_div').hide();
		}
		
		if($('#image_div').css('display') != 'none') {
			$('#image_div').hide();
		}

		$('#disp_info_div').toggle();
	});

	$('#image_btn').click(function(){
		if($('#pers_info_div').css('display') != 'none') {
			$('#pers_info_div').hide();
		}
		
		if($('#disp_info_div').css('display') != 'none') {
			$('#disp_info_div').hide();
		}

		$('#image_div').toggle();
	});

});