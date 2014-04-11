<?php

class COL_display_calculator{
	
	public function __construct() {	
		
		add_filter('the_content', array($this, 'display_calculator'));			
		add_action('wp_enqueue_scripts', array($this, 'display_col_js'));			
		add_action('wp_head', array($this, 'display_social_media_data'));			
		
	}
	
	function display_social_media_data() {
	
		if(isset($_GET['response_id'])){
		
			global $wpdb;
			
			$table_name = $wpdb->prefix . "col_calculator_responses_data";
			
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $table_name . " WHERE ID = %d",  $_GET['response_id']) );
		
			$title = get_option("col_calculator_twitter_title");
			$desc = get_option("col_calculator_twitter_desc");			

			echo '<meta name="twitter:title" content="' . str_replace("??", $data->classes, $title) . '">';
			echo '<meta property="og:title" content="' . str_replace("??", $data->classes, $title) . '">';
			echo '<meta name="twitter:description" content="' . $desc . '">';
			echo '<meta property="og:description" content="' . $desc . '">';
	
		}
		
		echo "<meta name='twitter:card' content='summary'>";
		echo "<meta name='twitter:site' content='@adjunctaction'>";
		echo '<meta property="og:image" content="' . get_option("col_calculator_twitter_img_url") . '">';
		echo '<meta property="og:url" content="' . $this->curPageURL() . '">';
		echo "<meta name='twitter:image' content='" . get_option("col_calculator_twitter_img_url") . "'>";
	
	}

	function curPageURL() {
 		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	function display_col_js() {
	
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('col_calculate_places', plugins_url('/js/col_calculate_places.js', __FILE__), array(), '1.0.0', true );
		wp_enqueue_script('col_rent_data', plugins_url('/js/col_rent_data.js', __FILE__), array(), '1.0.0', true );
		wp_enqueue_script('col_calculate_js', plugins_url('/js/col_data_calculate.js', __FILE__), array(), '1.0.0', true );
		wp_localize_script('col_calculate_js', 'col_calculate', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'answerNonce' => wp_create_nonce( 'col_calculate_js_nonce' ) ) );
		wp_enqueue_script('col_calculate_place_request', plugins_url('/js/col_calculator_place_request.js', __FILE__), array(), '1.0.0', true );
		wp_localize_script('col_calculate_place_request', 'col_calculate_place_request', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'answerNonce' => wp_create_nonce( 'col_calculate_place_request_js_nonce' ) ) );
		
	}
	
	function display_calculator($the_content){
	
		global $post;
	
		$calculated_page = get_option("col_calculator_page");		
				
		if($post->ID==$calculated_page){
			return str_replace("[col_calculator]", $this->draw_calculator(), $the_content);
		}else{
			return $the_content;
		}
				
	}
	
	function draw_calculator(){
		
		$output = "<link rel='stylesheet' href='" . plugins_url('/css/col_cal.css', __FILE__) . "' type='text/css' media='all' />";
		$output .= "<div class='col_calculator'>";
		$output .= "<div>";
		$output .= "<div><p><label>" . get_option("col_calculator_name_label") . "</label></p></div>";
		$output .= "<div><input class='col_cal_focus' id='col_calculator_name' value='" . get_option("col_calculator_name_box_text") . "'></div>";
		$output .= "</div>";
		$output .= "<div>";
		$output .= "<div><p><label>" . get_option("col_calculator_zip_label") . "</label></p></div>";
		$output .= "<div><input class='col_cal_focus' id='col_calculator_zip' value='" . get_option("col_calculator_name_box_text") . "'></div>";
		$output .= "</div>";
		$output .= "<div>";
		$output .= "<div><label for='tags'>" . get_option("col_calculator_teach_label") . '</label></div>';
		$output .= '<div><select id="col_calculator_teach"><option>Select a location</option></select></div>';
		$output .= "</div>";
		$output .= "<div>";
		$output .= "<div><label>" . get_option("col_calculator_pay_label") . "</label></div>";
		$output .= "<div><input class='col_cal_focus' id='col_calculator_pay' value='" . get_option("col_calculator_pay_box_text") . "'></div>";
		$output .= "</div>";
		$output .= "<div>";
		$output .= "<div><label>" . get_option("col_calculator_email_label") . "</label></div>";
		$output .= "<div><input class='col_cal_focus' id='col_calculator_email' value='" . get_option("col_calculator_email_box_text") . "'></div>";
		$output .= "</div>";
		$output .= "<div id='col_total'><p>Results appear here</p></div>";
		$output .= "<div id='col_button'>";
		$output .= "<button onclick='javascript:col_calculator_maths()'>Calculate</button>";
		$output .= "</div>";
		$output .= "<div id='col_social'>";
		$output .= "<a target='_blank' id='col_cal_twitter' link_hashtag='" . str_replace("#","",get_option("col_calculator_twitter_hashtag")) . "' link_text='" . addslashes(get_option('col_calculator_twitter_share_text')) . "' href=''><img src='" . plugins_url('/img/tw.png', __FILE__) . "' /></a>";
		$output .= '<a target="_blank" id="col_cal_email" email_body="' . str_replace('"',"'",get_option("col_calculator_email_body")) . '" email_subject="' . str_replace('"',"'",get_option("col_calculator_email_subject")) . '" href=""><img src="' . plugins_url('/img/email.png', __FILE__) . '" /></a>';
		$output .= "<a target='_blank' id='col_cal_fb' href=''><img src='" . plugins_url('/img/fb.png', __FILE__) . "' /></a>";
		$output .= "<p>" . get_option("col_calculator_response_label") . "</p>";
		$output .= "<textarea class='col_cal_focus' update_id='' id='col_calculator_response'>" . get_option("col_calculator_email_box_text") . "</textarea>";
		$output .= "<button class='col_calculator_thoughts_button' onclick='javascript:col_calculator_your_response()'>Let us know</button>";
		$output .= "<p id='col_cal_thoughts_response'></p>";
		$output .= "</div>";
		$output .= "</div>";
		$output .= "<p>";
		$output .= get_option("col_calculator_location_label") . "<input class='col_cal_focus' id='col_calculator_new_place' value='" . get_option("col_calculator_location_box_text") . "'></p>";
		$output .= "<button class='col_calculator_place_button' onclick='javascript:col_calculator_place_request()'>Let us know</button>";
		$output .= "<p id='col_calculator_thanks'>Thanks for submitting a place</p>";
		$output .= "</p>";
		
		return $output;
	
	}

} 

$COL_calculator = new COL_display_calculator();
