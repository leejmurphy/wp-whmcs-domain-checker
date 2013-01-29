<?php
/*
Plugin Name: LJM WHMCS Domain Checker
Plugin URI: http://www.leemurphy.co.uk/offerings/file/2-whmcs-wordpress-domain-checker
Description: Displays the WHMCS Domain Checker in a widget for WordPress
Author: Lee Murphy
Author URI: http://www.leemurphy.co.uk
License: GPLv3
Version: 1.1.2
*/

add_action( 'widgets_init', 'ljm_whmcs_domain_checker_widget' );

function ljm_whmcs_domain_checker_widget() {
	register_widget( 'Domain_Widget' );
}

class Domain_Widget extends WP_Widget {

	function Domain_Widget() {
		$widget_ops = array( 'classname' => 'domainchecker', 'description' => __('Displays the WHMCS Domain Checker', 'domainchecker') );		
		$this->WP_Widget( 'whmcsdomainchecker-widget', __('WHMCS Domain Checker', 'domainchecker'), $widget_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		// Variables from the settings
		$title			= apply_filters('widget_title', $instance['title'] );
		$whmcs_path		= $instance['whmcs_path'];
		$form_type		= $instance['form_type'];

		echo $before_widget;

		// Display the title 
		if ( $title )
			echo $before_title . $title . $after_title;

		// Display the form
		if ( $whmcs_path && $form_type)
		{
			// Remove trailing slash(s) (if exists) so we can ensure we only add 1
			$whmcs_path = rtrim($whmcs_path, '/');
			
			if( $form_type == 'domainavailablity' )
			{
				$form_path	= $whmcs_path . '/domainchecker.php';
				$sld_name	= 'domain';
				$tld_name	= 'ext';
			}
			elseif( $form_type == 'domainordering' )
			{
				$form_path	= $whmcs_path . '/cart.php?a=add&domain=register" ';
				$sld_name	= 'sld';
				$tld_name	= 'tld';
			}
			else print_r( '<b>An error has occured saving the form type. Please try again or submit a bug report.</b>' );
			
			printf( '' . __('<form action="%1$s" method="post">', 'domainchecker') . '', $form_path );
			
			echo "	<input type=\"hidden\" name=\"direct\" value=\"true\" />
					<label for=\"".$sld_name."\">WWW:</label><input type=\"text\" placeholder=\"yoursite\" id=\"".$sld_name."\" name=\"".$sld_name."\" size=\"20\" /> <select name=\"".$tld_name."\">
						<option>.com</option>
						<option>.co.uk</option>
						<option>.info</option>
						<option>.biz</option>
						<option>.org</option>
						<option>.net</option>
						<option>.mobi</option>
						<option>.es</option>
						<option>.eu</option>
						<option>.de</option>
						<option>.ru</option>
						<option>.sx</option>
						<option>.me</option>
						<option>.org.uk</option>
						<option>.me.uk</option>
						<option>.uk.com</option>
						<option>.co</option>
						<option>.us</option>
						<option>.tel</option>
						<option>.tv</option>
						<option>.name</option>
						<option>.cc</option>
						<option>.pro</option>
						<option>.xxx</option>
					</select>
				<input type=\"submit\" value=\"Go\" />
			</form>";
		}
		else print_r( '<b>You must enter a valid path to your WHMCS directory.</b>' );
		
		echo $after_widget;
	}


	//Update the widget 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title']			= strip_tags( $new_instance['title'] );
		$instance['whmcs_path']		= strip_tags( $new_instance['whmcs_path'] );
		$instance['form_type']		= strip_tags( $new_instance['form_type'] );

		return $instance;
	}

	
	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Domain Checker', 'domainchecker'), 'name' => __('/whmcs', 'domainchecker') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'domainchecker'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'whmcs_path' ); ?>"><?php _e('Path to WHMCS Directory:', 'domainchecker'); ?></label>
			<input id="<?php echo $this->get_field_id( 'whmcs_path' ); ?>" name="<?php echo $this->get_field_name( 'whmcs_path' ); ?>" value="<?php echo $instance['whmcs_path']; ?>" style="width:100%;" />
		</p>
        
        <p>
        	<label for="<?php echo $this->get_field_id( 'form_type' ); ?>"><?php _e('Form Type:', 'example'); ?></label>
            <select id="<?php echo $this->get_field_id( 'form_type' ); ?>" name="<?php echo $this->get_field_name( 'form_type' ); ?>" style="width:100%;">
            	<option <?php echo $instance['form_type'] == "domainavailablity" ? "selected" : ""; ?> value="domainavailablity">Domain Availablity</option>
              	<option <?php echo $instance['form_type'] == "domainordering" ? "selected" : ""; ?> value="domainordering">Domain Ordering</option>
            </select>
        </p>

	<?php
	}
}
?>