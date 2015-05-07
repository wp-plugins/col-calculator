<?php

class COL_Download{
	
	public function __construct() {	
		
		add_action('do_feed_COL_calculator', array($this, 'generate_download'));			
		
	}

	function maybeEncodeCSVField($string) {
		if(strpos($string, ',') !== false || strpos($string, '"') !== false || strpos($string, "\n") !== false) {
			$string = '"' . str_replace('"', '""', $string) . '"';
		}
		return $string;
	}
	
	function generate_download() {
	
		global $wpdb;
		
		$user = wp_get_current_user();
		
		if($user->ID!=0){
		
			$table_name = $wpdb->prefix . "col_calculator_responses_data";
			
			$responses = $wpdb->get_results("SELECT name,zip,place,compensation,date_submitted,classes,email,response FROM " . $table_name . " order by place ASC");

			$csv = "";

			foreach($responses as $response){
							
				$csv .=  $this->maybeEncodeCSVField($response->name) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->zip) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->place) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->compensation) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->classes) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->response) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->email) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->compensation) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->classes) . ',';
				$csv .=  $this->maybeEncodeCSVField(date("l, jS F Y", $response->date_submitted)) . ',';
				$csv .=  $this->maybeEncodeCSVField($response->email) . '
				';
				
			}
			
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=calculator_download.csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			echo $csv;
			
		}else{
		
			status_header(404);
			nocache_headers();
			include( get_404_template() );
			exit;
		
		}
			
	}
	
} 

$COL_Download = new COL_Download();
