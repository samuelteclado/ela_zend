

$(".close").live("click", function(event) {
    //code to close description, dosent work
    event.stopPropagation();
    $(".notification").fadeOut("normal", function() {
        $(this).remove();
    });
});

$(document).ready(function(){
	var sudoSlider = $(".carouselpro_wrapp").sudoSlider({
		continuous:true,auto:false,prevNext:true,autowidth:false,slideCount:4
	});
	var sudoSlider = $(".carouselsec_wrapp").sudoSlider({
		continuous:true,auto:false,prevNext:true,autowidth:false,slideCount:5
	});
});