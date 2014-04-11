<?php

class COL_Manage{
	
	public function __construct() {	
		
		if ( is_admin() ) {
			add_action('admin_menu', array($this, 'menu_option'));			
			add_action('admin_enqueue_scripts', array($this, 'manage_col_js'));
		}
		
	}
	
	function manage_col_js() {
	
		wp_enqueue_script( 'col_manage_place_js', plugins_url('/js/col_manage_data.js', __FILE__), array(), '1.0.0', true );
		wp_enqueue_script( 'col_responses_sorted', plugins_url('/js/jquery.tablesorter.min.js', __FILE__), array(), '1.0.0', true );
		wp_localize_script( 'col_manage_place_js', 'col_manage_place', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'answerNonce' => wp_create_nonce( 'col_manage_place_js_nonce' ) ) );
		
	}
	
	function responses_page() {
	
		global $wpdb;
		
		$table_name = $wpdb->prefix . "col_calculator_responses_data";
		
		$responses = $wpdb->get_results("SELECT name,zip,place,compensation,date_submitted,classes,email,response FROM " . $table_name . " order by place ASC");

		?>
		<div class="wrap">
			<h1>Cost Of Living Calculator Responses</h1>
			<table id="myTable" class="tablesorter"> 
				<thead> 
					<tr> 
						<th>Name</th> 
						<th>Zip</th> 
						<th>Place</th> 
						<th>Pay</th> 
						<th>Classes</th> 
						<th>Date Submitted</th> 
						<th>Response</th> 
						<th>Email</th> 
					</tr> 
				</thead> 
				<tbody>
			<?PHP
			
				foreach($responses as $response){
					
					echo "<tr>";				
					echo "<td>" . $response->name . "</td>";
					echo "<td>" . $response->zip . "</td>";
					echo "<td>" . $response->place . "</td>";
					echo "<td>" . $response->compensation . "</td>";
					echo "<td>" . $response->classes . "</td>";
					echo "<td>" . date("l, jS F Y", $response->date_submitted) . "</td>"; 
					echo "<td>" . $response->response . "</td>"; 
					echo "<td>" . $response->email . "</td>"; 
					echo "</tr>";
					
				}
			
			?>
				</tbody>
			</table> 
		<script type="text/javascript" language="javascript">
			jQuery(document).ready(function() 
				{ 
					jQuery("#myTable").tablesorter(); 
				} 
			);
		</script>
		</div>
		<?PHP
		
	}
	
	function data_page() {
	
		global $wpdb;
		
		$table_name = $wpdb->prefix . "col_calculator_places_data";

		?>
		<div class="wrap">
			<h1>Cost Of Living Calculator Data</h1>
			<h3>Add a new place</h3>
			<form method="post" action=""><?PHP
			
					wp_nonce_field('col_calculator_add_new','col_calculator_add_new');
			
				?><p>Place : <input name='new_place' size='40' value='Enter new place here' /> Rent : <input name='new_rent' value='Enter rent here' />
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />			
			</form>
			<h3>Manage existing places</h3><?PHP
			
				$places = $wpdb->get_results("SELECT id,place,rent FROM " . $table_name . " order by place ASC");
			
				foreach($places as $place){
				
					if($place->place!=""){
				
						echo "<p>Place : <input id='" . $place->id . "_place' size='40' value='" . $place->place . "' />"; 
						echo " Rent : <input id='" . $place->id . "_rent' value='" . $place->rent . "' />";
						echo "<button onClick='javascript:col_updated(" . $place->id . ");'>Update</button></p>";
					
					}
					
				}
			
			?>
		</div>
		<?php
		
	}
	
	function options_page() {

	  ?>
	  <div class="wrap">
		<h1>Cost Of Living Calculator Config</h1>
		<form method="post" action="">
			<?PHP
		
			wp_nonce_field('col_calculator_manage','col_calculator_manage');
		
			$args = array(
							'child_of' => 0,
							'sort_order' => 'ASC',
							'sort_column' => 'post_title',
							'hierarchical' => 1,
							'parent' => -1,
							'offset' => 0,
							'post_type' => 'page',
							'post_status' => 'publish'
						); 
		
			$pages = get_pages( $args ); 
			
			$calculator_page = get_option("col_calculator_page_on");	
			
			?><h3>Main Calculator Page</h3><?PHP
			
			if($calculator_page){
				
				?><p> 
					Turn Calculator page on? <input type="checkbox" name="col_calculator_page_on" checked />
				</p>
				<p>
					<label>Which page do you want to append the calculator too?</label> <select name='col_calculator_page'><?PHP
				
				$selected_page = get_option("col_calculator_page");		
				
				echo "<option value=0>Not set</option>";

				foreach($pages as $page){
				
					echo "<option ";

					if($page->ID == $selected_page){
					
						echo " selected ";
					
					}

					echo " value=\"" . $page->ID . "\">" . $page->post_title . "</option>";
				
				}
				
				?></select>
				</p><?PHP
			
			}else{
			
				?><p> 
					Turn Calculator page on? <input type="checkbox" name="col_calculator_page_on" />
				</p><?PHP
				
			}
			?>
			<h2>Request to add a place</h2>
			<p>
				email address to receive emails when a new place is suggested<br/>
				<textarea name="col_calculator_place_email" rows="1" cols="50"><?PHP echo get_option("col_calculator_place_email"); ?></textarea>
			</p>
			<h2>Calculator display options</h2>
			<p>
				Label for the name box<br/>
				<textarea name="col_calculator_name_label" rows="1" cols="50"><?PHP echo get_option("col_calculator_name_label"); ?></textarea>
			</p>
			<p>
				Label for the ZIP box<br/>
				<textarea name="col_calculator_zip_label" rows="1" cols="50"><?PHP echo get_option("col_calculator_zip_label"); ?></textarea>
			</p>
			<p>
				Label for the location box<br/>
				<textarea name="col_calculator_teach_label" rows="1" cols="50"><?PHP echo get_option("col_calculator_teach_label"); ?></textarea>
			</p>
			<p>
				Label for the pay box<br/>
				<textarea name="col_calculator_pay_label" rows="1" cols="50"><?PHP echo get_option("col_calculator_pay_label"); ?></textarea>
			</p>
			<p>
				Label for the email box<br/>
				<textarea name="col_calculator_email_label" rows="1" cols="50"><?PHP echo get_option("col_calculator_email_label"); ?></textarea>
			</p>
			<p>
				Label for the new location box<br/>
				<textarea name="col_calculator_location_label" rows="1" cols="50"><?PHP echo get_option("col_calculator_location_label"); ?></textarea>
			</p>
			<p>
				Label for the calculator response<br/>
				<textarea name="col_calculator_response_label" rows="1" cols="50"><?PHP echo get_option("col_calculator_response_label"); ?></textarea>
			</p>
			<p>
				Text to appear in the name box<br/>
				<textarea name="col_calculator_name_box_text" rows="1" cols="50"><?PHP echo get_option("col_calculator_name_box_text"); ?></textarea>
			</p>
			<p>
				Text to appear in the ZIP box<br/>
				<textarea name="col_calculator_zip_box_text" rows="1" cols="50"><?PHP echo get_option("col_calculator_zip_box_text"); ?></textarea>
			</p>
			<p>
				Text to appear in the pay box<br/>
				<textarea name="col_calculator_pay_box_text" rows="1" cols="50"><?PHP echo get_option("col_calculator_pay_box_text"); ?></textarea>
			</p>
			<p>
				Text to appear in the email box<br/>
				<textarea name="col_calculator_email_box_text" rows="1" cols="50"><?PHP echo get_option("col_calculator_email_box_text"); ?></textarea>
			</p>
			<p>
				Text to appear in the new location box<br/>
				<textarea name="col_calculator_location_box_text" rows="1" cols="50"><?PHP echo get_option("col_calculator_location_box_text"); ?></textarea>
			</p>
			<p>
				Text to appear after submitting response to calculator<br/>
				<textarea name="col_calculator_response_thanks" rows="1" cols="50"><?PHP echo get_option("col_calculator_response_thanks"); ?></textarea>
			</p>
			<h2>Social media sharing</h2>
			<p>
				Twitter text to share (?? is replaced with the number of classes)<br/>
				<textarea name="col_calculator_twitter_share_text" rows="5" cols="50"><?PHP echo get_option("col_calculator_twitter_share_text"); ?></textarea>
			</p>			
			<p>
				Twitter share hashtag<br/>
				<textarea name="col_calculator_twitter_hashtag" rows="5" cols="50"><?PHP echo get_option("col_calculator_twitter_hashtag"); ?></textarea>
			</p>
			<p>
				Twitter / Facebook share title (Use ?? to show the number of classes someone will need to work)<br/>
				<textarea name="col_calculator_twitter_title" rows="5" cols="50"><?PHP echo get_option("col_calculator_twitter_title"); ?></textarea>
			</p>
			<p>
				Twitter / Facebook share description<br/>
				<textarea name="col_calculator_twitter_desc" rows="5" cols="50"><?PHP echo get_option("col_calculator_twitter_desc"); ?></textarea>
			</p>
			<p>
				Twitter / Facebook image url<br/>
				<textarea name="col_calculator_twitter_img_url" rows="5" cols="50"><?PHP echo get_option("col_calculator_twitter_img_url"); ?></textarea>
			</p>
			<p>
				Email Subject<br/>
				<textarea name="col_calculator_email_subject" rows="5" cols="50"><?PHP echo get_option("col_calculator_email_subject"); ?></textarea>
			</p>
			<p>
				Email Body<br/>
				<textarea name="col_calculator_email_body" rows="5" cols="50"><?PHP echo get_option("col_calculator_email_body"); ?></textarea>
			</p>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>		
	</div>
	  <?php
	}
		
	function main_col_menu(){
		echo "<h1>Welcome to the 'Cost of Living Calculator'</h1>";
		echo "<p>Please use the <a href='" . admin_url("admin.php?page=COL_calculator_options") . "'>config page</a> to change settings</p>";
		echo "<p>Please use the <a href='" . admin_url("admin.php?page=COL_calculator_data") . "'>places page</a> to add new places and update places and rents</p>";
		echo "<p>Please use the <a href='" . admin_url("admin.php?page=COL_calculator_responses") . "'>responses page</a> to see responses people have made</p>";	
		echo "<p><a href='" . site_url("?feed=COL_calculator") . "'>Download responses</a> received on the calculator</p>";	
	}
	
	function menu_option() {
	
		add_menu_page("Cost of Living Calculator", "Cost of Living Calculator", "manage_options", "COL", array($this, "main_col_menu"));	
		add_submenu_page('COL', 'Calculator Config', 'Cost Of Living Calculator Config', 'manage_options', 'COL_calculator_options', array($this, 'options_page'));
		add_submenu_page('COL', 'Calculator Data', 'Cost Of Living Calculator Places', 'manage_options', 'COL_calculator_data', array($this, 'data_page'));
		add_submenu_page('COL', 'Calculator Responses', 'Cost Of Living Calculator Responses', 'manage_options', 'COL_calculator_responses', array($this, 'responses_page'));
		
	}

} 

$COL_Manage = new COL_Manage();
