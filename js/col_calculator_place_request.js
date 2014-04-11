function col_calculator_place_request(){ 

	jQuery(document).ready(function($) {

		console.log(jQuery("#col_calculator_new_place").val());

		var data = {
			action: "col_place_request",
			place:jQuery("#col_calculator_new_place").val(),
			nonce: col_calculate_place_request.answerNonce
		};

		jQuery.post(col_calculate_place_request.ajaxurl, data, function(response) {

		});
		
		jQuery("#col_calculator_thanks")
			.fadeIn(1000,
					function(){
						jQuery("#col_calculator_thanks")
							.fadeOut(3000,
								function(){
									
								}
							)
					}
			);
		
	});
	
}