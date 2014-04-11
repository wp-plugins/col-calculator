<?php

class COL_ShortCode{
	
	public function __construct() {	
		
		add_shortcode('col_cal_display', array($this, 'col_calculator_shortcode'));
		
	}
	
	function col_calculator_shortcode(){
	
		require_once("class-COL_display_calculator.php");
		$COL_cal = new COL_display_calculator();
		return	$COL_cal->draw_calculator();

		
	}	

} 

$COL_ShortCode = new COL_ShortCode();
