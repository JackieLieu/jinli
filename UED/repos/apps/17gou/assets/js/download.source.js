$(function(){

	$('#J_contact_btn').on('click', function() {
		$.layer({
		    type: 1,
		    title: false,
		    area: ['996px', '493px'],
		    shade: [0.4, '#000'],
		    shadeClose: true,
		    offset: ['140px', ''],
		    border: [0],
		    page: {
		        dom: '#J_contact_box'
		    }
	    })
	});
});