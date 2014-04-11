function col_calculator_your_response(){ 

	jQuery(document).ready(function($) {
	
		var data = {

			action: "col_thoughts",
			thoughts:jQuery("#col_calculator_response").val(),
			update_id:jQuery('#col_calculator_response').attr("update_id"),
			nonce: col_calculate.answerNonce

		};
	
		jQuery.post(col_calculate.ajaxurl, data, function(response) {
			jQuery('#col_cal_thoughts_response')
				.html("<span>" + response + "</span>")
				.fadeIn(700, function() {
						$('#col_cal_thoughts_response').fadeOut( 1500 );
					}
				);
		});
		
	});
	
}

function add_query_arg(purl,key,value){
    key = escape(key); value = escape(value);

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

    s = s.replace(r,"$1"+pair);
    //console.log(s, pair);
    if(s.indexOf(key + '=')>-1){


    }else{
        if(s.indexOf('?')>-1){
            s+='&'+pair;
        }else{
            s+='?'+pair;
        }
    }
    //if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};
    return s;
}


function col_calculator_maths(){ 

	jQuery(document).ready(function($) {
	
		if(jQuery("#col_calculator_zip").val()!=""){
	
			if(jQuery("#col_calculator_pay").val()!=""){
			
				if(jQuery("#col_calculator_teach").find(':selected').html()!="Select a location"){
									
					pay = jQuery("#col_calculator_pay").val().split("$").join("").split(",").join("");

					if(!isNaN(pay)){
										
						var data = {

							action: "col_calculation",
							name:jQuery("#col_calculator_name").val(),
							zip:jQuery("#col_calculator_zip").val(),
							place:jQuery("#col_calculator_teach").find(':selected').html(),
							compensation:pay,
							email:jQuery("#col_calculator_email").val(),
							classes:parseInt(places[jQuery("#col_calculator_teach").find(':selected').html()] / pay),
							nonce: col_calculate.answerNonce

						};

						if(jQuery("#col_calculator_name").val()!=""){
						
							extra = jQuery("#col_calculator_name").val() +", ";
						
						}else{
						
							extra = "";
						
						}

						response = extra + " you'll need to work " + parseInt(jQuery("#col_calculator_teach").val() / pay) + " classes to pay your bills";

						tw_response = jQuery('#col_cal_twitter').attr("link_text");
						
						tw_response = tw_response.split("???").join(window.location.href).split("??").join(parseInt(jQuery("#col_calculator_teach").val() / pay ));

						jQuery("#col_total").html("<p>" + response + "</p>")
									.fadeIn();

						jQuery.post(col_calculate.ajaxurl, data, function(response) {
							jQuery('#col_calculator_response').attr("update_id",response);
							
							url = encodeURIComponent(add_query_arg(location.protocol + '//' + location.host + location.pathname,'response_id',response));

							twitter = 'https://twitter.com/intent/tweet?source=tweet_button&original_referer=' + url + '&url=' + url + '&text=' + tw_response + "&hashtags=" + jQuery('#col_cal_twitter').attr("link_hashtag") ;
							fb = 'https://www.facebook.com/sharer.php?s=100&p[url]=' + encodeURIComponent(url);
							email = "mailto:put your address here?subject=" + jQuery('#col_cal_email').attr("email_subject").split('"').join("'") + "&body=" + encodeURI(jQuery('#col_cal_email').attr("email_body")).split("\\").join("").split("??").join(parseInt(jQuery("#col_calculator_teach").val() / pay));
							

							jQuery('#col_cal_twitter').attr("href",twitter);
							jQuery('#col_cal_fb').attr("href",fb);
							jQuery('#col_cal_email').attr("href",email);

							height = jQuery("#col_social").css("height");

							if(height=="0px"){

								jQuery("#col_social")
									.animate({
										height:"+=250",
										marginTop:"+=15"
									},800,function(){
									}
									);

							}
							
						});
						
					}else{
					
						alert("Please make sure compensation is a number");
					
					}
				
				}else{
				
					alert("Please select a location");
				
				}
			
			}else{
			
				alert("Please set a compensation");
			
			}
		
		}else{
			
			alert("Please set a ZIP code");
			
		}
		
	});
	
}


jQuery(document).ready(function($) {
	jQuery('.col_cal_focus')
		.each(
			function(index,value){
				jQuery(value).focus(
					function(){
						jQuery(this).val("");
					}
				)
			}
		)

});