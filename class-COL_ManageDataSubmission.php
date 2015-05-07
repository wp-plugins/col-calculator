<?php

class COL_ManageDataSubmission{
	
	public function __construct() {	
		
		if ( is_admin() ) {
			add_action('admin_head', array($this, 'manage_postform'));
			add_action('wp_ajax_col_place_updated', array($this, 'col_place_updated'));			
		}

		add_action('wp_ajax_nopriv_col_calculation', array($this, 'col_calculated'));
		add_action('wp_ajax_col_calculation', array($this, 'col_calculated'));
		add_action('wp_ajax_nopriv_col_place_request', array($this, 'col_place_request'));
		add_action('wp_ajax_col_place_request', array($this, 'col_place_request'));
		add_action('wp_ajax_nopriv_col_thoughts', array($this, 'col_thoughts'));
		add_action('wp_ajax_col_thoughts', array($this, 'col_thoughts'));
		
	}
	
	function col_thoughts(){

		if(wp_verify_nonce($_REQUEST['nonce'], 'col_calculate_js_nonce')){
		
			global $wpdb;
			$table_name = $wpdb->prefix . "col_calculator_responses_data";
			
			$wpdb->update( 
				$table_name, 
				array( 
					'response' => filter_var($_POST['thoughts'], FILTER_SANITIZE_STRING)
				), 
				array( 'id' => $_POST['update_id'] ), 
				array( 
					'%s'	// value1
				), 
				array( '%d' ) 
			);
			
			echo get_option("col_calculator_response_thanks");
			
		
		}
		
		die();
		
	}	
	
	function col_calculated(){

		if(wp_verify_nonce($_REQUEST['nonce'], 'col_calculate_js_nonce')){

			global $wpdb;
			
			$table_name = $wpdb->prefix . "col_calculator_responses_data";
			$wpdb->insert( $table_name, array( 	'name' => filter_var($_POST['name'], FILTER_SANITIZE_STRING),
												'zip' => filter_var($_POST['zip'], FILTER_SANITIZE_NUMBER_INT), 
												'place' => filter_var($_POST['place'], FILTER_SANITIZE_STRING), 
												'compensation' => filter_var($_POST['compensation'], FILTER_SANITIZE_NUMBER_INT),
												'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 
												'classes' => filter_var($_POST['classes'], FILTER_SANITIZE_NUMBER_INT),
												'date_submitted' => time()));
												
			echo $wpdb->insert_id;
			
			die();
	
		}
		
	}
	
	function col_place_request(){
		
		if(wp_verify_nonce($_REQUEST['nonce'], 'col_calculate_place_request_js_nonce')){
		
			wp_mail(str_replace(" ",";",get_option("col_calculator_place_email")), "New place requested", "A visitor has asked for " . $_POST['place'] . " to be added as a place");
				
		}
		
		die();
		
	}
	
	function col_place_updated(){
	
		if(wp_verify_nonce($_REQUEST['nonce'], 'col_manage_place_js_nonce')){
	
			global $wpdb;
			$table_name = $wpdb->prefix . "col_calculator_places_data";
			
			$wpdb->update( 
				$table_name, 
				array( 
					'place' => $_POST['place_name'],
					'rent' => $_POST['place_rent']	
				), 
				array( 'id' => $_POST['place_id'] ), 
				array( 
					'%s',	// value1
					'%d'	// value2
				), 
				array( '%d' ) 
			);
			
			echo $_POST['place_name'] . " updated";
			
			$this->update_rent_js();	
			
		}
		
		die();
	
	}
	
	function add_place(){
			
		global $wpdb;
		
		$table_name = $wpdb->prefix . "col_calculator_places_data";
		
		$rent = str_replace(",","",str_replace("$","",$_POST['new_rent']));
		
		$wpdb->insert( $table_name, array( 'place' => filter_var($_POST['new_place'], FILTER_SANITIZE_STRING), 'rent' => filter_var($_POST['new_rent'], FILTER_SANITIZE_NUMBER_INT)));
	
		return $this->update_rent_js();
	
	}
	
	function update_rent_js(){
	
		global $wpdb;
		$table_name = $wpdb->prefix . "col_calculator_places_data";
	
		$places = $wpdb->get_results("SELECT id,place,rent FROM " . $table_name . " order by place ASC");
		
		$js = "var places = new Array();";
		
		foreach($places as $place){
		
			if($place->place!=""){
		
				$js .= 'places["' . $place->place . '"] = ' . $place->rent . ';';
			
			}
			
		}
		
		if(file_exists(dirname(__FILE__) . "/js/col_calculate_places.js")){
			if(file_put_contents(dirname(__FILE__) . "/js/col_calculate_places.js", $js)){
				return "Updated successfully";
			}else{
				return "Error - file not updated";
			}
		}else{
			return "Error - file not found";
		}
	
	}
	
	function update_settings(){
	
		$count=0;
		
		if($_POST['col_calculator_page_on']=="on"){
			update_option("col_calculator_page_on", TRUE);
			update_option("col_calculator_page", $_POST['col_calculator_page']);
		}else{
			update_option("col_calculator_page_on", FALSE);
		}		
		
		update_option("col_calculator_page",$_POST['col_calculator_page']);
		update_option("col_calculator_place_email",stripslashes($_POST["col_calculator_place_email"]));
		update_option("col_calculator_twitter_share_text",stripslashes($_POST["col_calculator_twitter_share_text"]));
		update_option("col_calculator_twitter_hashtag",trim(stripslashes($_POST["col_calculator_twitter_hashtag"])));
		update_option("col_calculator_email_subject",trim(stripslashes($_POST["col_calculator_email_subject"])));
		update_option("col_calculator_email_body",trim(stripslashes($_POST["col_calculator_email_body"])));
		update_option("col_calculator_name_box_text",trim(stripslashes($_POST["col_calculator_name_box_text"])));
		update_option("col_calculator_zip_box_text",trim(stripslashes($_POST["col_calculator_zip_box_text"])));
		update_option("col_calculator_pay_box_text",trim(stripslashes($_POST["col_calculator_pay_box_text"])));
		update_option("col_calculator_email_box_text",trim(stripslashes($_POST["col_calculator_email_box_text"])));
		update_option("col_calculator_location_box_text",trim(stripslashes($_POST["col_calculator_location_box_text"])));
		update_option("col_calculator_twitter_title",trim(stripslashes($_POST["col_calculator_twitter_title"])));
		update_option("col_calculator_twitter_desc",trim(stripslashes($_POST["col_calculator_twitter_desc"])));
		update_option("col_calculator_twitter_img_url",trim(stripslashes($_POST["col_calculator_twitter_img_url"])));		
		update_option("col_calculator_name_label",trim(stripslashes($_POST["col_calculator_name_label"])));
		update_option("col_calculator_zip_label",trim(stripslashes($_POST["col_calculator_zip_label"])));
		update_option("col_calculator_pay_label",trim(stripslashes($_POST["col_calculator_pay_label"])));
		update_option("col_calculator_email_label",trim(stripslashes($_POST["col_calculator_email_label"])));
		update_option("col_calculator_teach_label",trim(stripslashes($_POST["col_calculator_teach_label"])));
		update_option("col_calculator_location_label",trim(stripslashes($_POST["col_calculator_location_label"])));
		update_option("col_calculator_response_thanks",trim(stripslashes($_POST["col_calculator_response_thanks"])));
		update_option("col_calculator_response_label",trim(stripslashes($_POST["col_calculator_response_label"])));
		
	}
	
	function manage_postform(){
		
		if (!empty($_POST['col_calculator_manage'])){

			if(!wp_verify_nonce($_POST['col_calculator_manage'],'col_calculator_manage') ){
			
				print 'Sorry, your nonce did not verify.';
				exit;
				
			}else{			
			
				$this->update_settings();
			
			}
		
		}
		
		if (!empty($_POST['col_calculator_add_new'])){

			if(!wp_verify_nonce($_POST['col_calculator_add_new'],'col_calculator_add_new') ){
			
				print 'Sorry, your nonce did not verify.';
				exit;
				
			}else{			
			
				$response = $this->add_place();
				echo "<p>" . $response . "</p>";
			
			}
		
		}
	
	}

} 

$COL_ManageDataSubmission = new COL_ManageDataSubmission();
