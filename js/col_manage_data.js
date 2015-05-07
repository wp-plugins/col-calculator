function col_updated(place_id){ 

	jQuery(document).ready(function($) {
	
		var data = {
			action: "col_place_updated",
			place_id:place_id,
			place_name:jQuery("#" + place_id + "_place").val(),
			place_rent:jQuery("#" + place_id + "_rent").val(),
			nonce: col_manage_place.answerNonce
		};
		
		jQuery.post(col_manage_place.ajaxurl, data, function(response) {
			alert(response);
		});
	});
	
}