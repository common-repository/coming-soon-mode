<?php
/**
 * Plugin Name: Coming Soon Mode
 * Plugin URI: https://seosthemes.com/coming-soon-mode/
 * Contributors: seosbg
 * Author: seosbg
 * Description: Coming Soon Mode WordPress plugin manage your website while it's under maintenance mode or coming soon or site offline mode.
 * Version: 1.1.0
 * License: GPL2
*/

	//Add Admin Setting
	add_action('admin_menu', 'csm_menu');
	function csm_menu() {
		add_menu_page('Coming Soon Mode', 'Coming Soon Mode', 'administrator', 'csm-settings-group', 'csm_settings_page', plugins_url( 'images/icon.png' , __FILE__ )
    );

    add_action('admin_init', 'csm_register_settings');
}
	// Admin Enqueue Scripts
	add_action( 'admin_enqueue_scripts', 'csm_admin_styles' );
	
	function csm_admin_styles() {
		
    wp_enqueue_script('jquery');

	wp_enqueue_media();
	
	wp_enqueue_style( 'farbtastic' );
	
    wp_enqueue_script( 'farbtastic' );
	
	wp_register_style('csm_style', plugin_dir_url(__FILE__) . '/css/admin.css');
	wp_enqueue_style('csm_style');	
	}
	// Register Setting
	function csm_register_settings() {
		register_setting( 'csm-settings-group', 'csm_construction' );
		register_setting( 'csm-settings-group', 'csm_activate' );
		register_setting( 'csm-settings-group', 'csm_opacity' );
		register_setting( 'csm-settings-group', 'csm_img1' );
		register_setting( 'csm-settings-group', 'csm_background_color' );
	}
		
	function csm_settings_page() { ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				
				var fileFrame;
				jQuery('.upload_image_button').on('click', function(e){
					e.preventDefault();
					
					element = jQuery(this);
					id = jQuery(element).attr('data-id');
					
					if(fileFrame){
						fileFrame.open();
						return;
					}
					
					fileFrame = wp.media.frames.file_frame = wp.media({
						tile: jQuery(this).data('uploader_title'),
						button: {
							text: jQuery(this).data('uploader_button_text')
						},
						multiple: false,
						library: { type: 'image' }
					});
					
					fileFrame.on('select', function(){
						attachment = fileFrame.state().get('selection').first().toJSON();
						
						if(attachment.url){					
							jQuery('input[name="csm_img' + id + '"]').val(attachment.url);
							jQuery('#slider_preview_img' + id).attr('src', attachment.url).fadeIn();
							jQuery(element).parent().find('.slider_remove_img').show();
						}
					});
					
					fileFrame.open();
				});
				
				jQuery('.slider_remove_img').click(function(e){
					e.preventDefault();
					
					element = jQuery(this);
					id = jQuery(element).attr('data-id');
					image = jQuery('#slider_preview_img' + id);
					
					jQuery('input[name="csm_img' + id + '"]').val('');
					jQuery(image).fadeOut();
					jQuery(element).hide();
					
				});		
			});
		</script>		
		<div class="wrap">
			<form name="csm_form" action="options.php" method="post" class="csm-form">
			<?php settings_fields( 'csm-settings-group' ); ?>
			<?php do_settings_sections( 'csm-settings-group' ); ?>
			
				<div id="csm_wrap">
					<div class="csm">
						<a target="_blank" href="https://seosthemes.com/">
							<div class="btn s-red">
								 <?php _e('SEOS', 'csm'); echo ' <img class="ss-logo" src="' . plugins_url( 'images/logo.png' , __FILE__ ) . '" alt="logo" />';  _e(' THEMES', 'csm'); ?>
							</div>
						</a>
					</div>
						<?php for ($i = 1; $i < 3; $i++) { ?>
							<script>
								jQuery(document).ready(function($) {
									$('#colorpicker<?php echo $i; ?>').hide();
									$('#colorpicker<?php echo $i; ?>').farbtastic('#color<?php echo $i; ?>');

									$('#color<?php echo $i; ?>').click(function() {
										$('#colorpicker<?php echo $i; ?>').fadeIn();
									});

									$(document).mousedown(function() {
										$('#colorpicker<?php echo $i; ?>').each(function() {
											var display = $(this).css('display');
											if ( display == 'block' )
												$(this).fadeOut();
										});
									});
								});
							</script>
						<?php } ?>
						<h2><?php _e( 'Coming Soon Mode', 'csm' ); ?></h2>
						<br />
						<br />
						
		<!-- ------------------- Activation------------------ -->
		
			<div class="form-group">
						<label><?php _e('Activate Coming Soon: ', 'csm'); ?></label>
						<?php $activate = esc_attr(get_option( 'csm_activate' )); ?>
						<select name="csm_activate">
							<option value="1" <?php if ( $activate == "1" ) echo 'selected="selected"'; ?>><?php _e('Activate', 'csm'); ?></option>
							<option value="2" <?php if ( $activate == "2" ) echo 'selected="selected"'; ?>><?php _e('Deactivate', 'csm'); ?></option>
						</select>
			</div>						
						<hr />
			
			<!-- ------------------- Load Background ------------------ -->

										
			<div class="form-group">
				<label><?php _e('Load Image: ', 'csm'); ?></label>
				<p>
					<input type="text" name="csm_img1" size="50" value="<?php echo get_option( 'csm_img1' ); ?>" />
					<a class="upload_image_button" data-id="1" href="#" title="Select image">
					<img src="<?php echo plugins_url( 'images/find-img.png' , __FILE__ ); ?>" style="width:20px;height:20px;">
					</a>
					<a class="slider_remove_img" data-id="1" href="#" title="Remove image" <?php if(!get_option( 'csm_img1' )){ echo 'style="display:none;"'; } ?>>
					<img src="<?php echo plugins_url( 'images/remove.png' , __FILE__ ); ?>" style="width:20px;height:20px;">
					</a>
				</p>
				<p><img id="slider_preview_img1" src="<?php echo get_option( 'csm_img1' ) ; ?>" alt="Slider1" <?php if(!get_option( 'csm_img1' )){ echo 'style="display:none;"'; } ?>></p>
			</div>

						

			
		<!-- ------------------- Coming Soon Text  ------------------ -->
		<div class="form-group">
						<br />
						<br />
						<label><?php _e('Coming Soon Text', 'csm'); ?></label>
						<p><input type="text" name="csm_construction" size="50" value="<?php echo esc_html(get_option( 'csm_construction' )); ?>" /></p>				

						
		<!-- ------------------- Opacity ------------------ -->
										

				<label><?php _e('Coming Soon Transparent: ', 'csm'); ?></label>
				<?php $opacity = get_option( 'csm_opacity' ); ?>
				<select name="csm_opacity">
					<option value="" selected="selected"> none</option>
					<option value="0.1" <?php if ( $opacity == "0.1" ) echo 'selected="selected"'; ?>><?php _e('0.1', 'csm'); ?></option>
					<option value="0.2" <?php if ( $opacity == "0.2" ) echo 'selected="selected"'; ?>><?php _e('0.2', 'csm'); ?></option>
					<option value="0.3" <?php if ( $opacity == "0.3" ) echo 'selected="selected"'; ?>><?php _e('0.3', 'csm'); ?></option>
					<option value="0.4" <?php if ( $opacity == "0.4" ) echo 'selected="selected"'; ?>><?php _e('0.4', 'csm'); ?></option>
					<option value="0.5" <?php if ( $opacity == "0.5" ) echo 'selected="selected"'; ?>><?php _e('0.5', 'csm'); ?></option>
					<option value="0.6" <?php if ( $opacity == "0.6" ) echo 'selected="selected"'; ?>><?php _e('0.6', 'csm'); ?></option>
					<option value="0.7" <?php if ( $opacity == "0.7" ) echo 'selected="selected"'; ?>><?php _e('0.7', 'csm'); ?></option>
					<option value="0.8" <?php if ( $opacity == "0.8" ) echo 'selected="selected"'; ?>><?php _e('0.8', 'csm'); ?></option>
					<option value="0.9" <?php if ( $opacity == "0.9" ) echo 'selected="selected"'; ?>><?php _e('0.9', 'csm'); ?></option>
				</select>
				<br />
				<br />				
				<label><?php _e('Coming Soon Background Color:', 'custom-colors'); ?></label>
				<br />
				<br />											
				<div class="color-picker" style="position: relative;">
					<input placeholder="Set Color" class="form-control" style="width: 100px;" type="text" name="csm_background_color" id="color1" value="<?php if (esc_html(get_option( 'csm_background_color'))) : echo esc_html(get_option( 'csm_background_color')); else : echo "Set Color"; endif; ?>"/>
					<div style="position: absolute; z-index: 999; background: #fff; border: 1px solid #C0C0C0;" id="colorpicker1"></div>
				</div>
				<br />
				<br />
				<br />
				<br />				
				<br />				
				<br />				
				<br />				
				<br />				
				<br />				
									
				
				
			</div>
			<hr />	
								<?php submit_button(); ?>			
				</div>
			</form>
		</div>
		<div class="clear"></div>
	<?php
	}
	
/************************* Translation *************************/	
	
	function csm_language_load() {
	  load_plugin_textdomain('csm_language_load', FALSE, basename(dirname(__FILE__)) . '/languages');
	}
	add_action('init', 'csm_language_load');

	
/*********************************************************************************************************
* Under Construction
**********************************************************************************************************/
	$activate = esc_attr(get_option( 'csm_activate' ));
	if ($activate == 1):
			function csm_under_Construction (){
				if(!is_admin() && !is_user_logged_in()) { ?>
				
				<?php if (get_option('csm_construction')) : ?><div class="csm-construction"><?php echo esc_attr(get_option('csm_construction'));?></div><?php endif; ?>
				
				<?php echo "
				<style>
					nav, header, footer, div, article, p, span, main, aside { 
						display: none;
					}
					
					@media screen and (min-width: 59.6875em){	
						body:before, body{
								background: #FFD800 !important;
								position: static !important;
						}
					}
					
					.csm-construction {
						width: 100% !important;
						background: #B50000;
						box-shadow: inset 0 0 0 #333333, inset 0 1px 84px #333333, inset 0 0 0 #333333 !important;
						padding: 20px 0 20px 0 !important;
						height: auto !important;
						display: block  !important;
						text-align: center !important;
						color: #fff !important;
						margin-top: 200px !important;
						font-size: 5vw !important;
						font-weight: 900 !important;
					}
				</style>";
					}
				}
			 
			add_action('wp_head','csm_under_Construction');
	endif;
	
/*********************************************************************************************************
* Function Opacity
**********************************************************************************************************/	

	function csm_width () {	
	if (get_option( 'csm_opacity')) : ?>
		<style>
		.csm-construction {
				opacity: <?php echo get_option( 'csm_opacity'); ?>;
		}
		</style>
		<?php endif;
	}
	add_action('wp_head','csm_width');
	
/*********************************************************************************************************
* Function Opacity
**********************************************************************************************************/	

	function csm_img () {
	$activate = esc_attr(get_option( 'csm_activate' ));		
		if((!is_admin() && !is_user_logged_in()) and $activate == 1){
		if (get_option( 'csm_img1')) : ?>
			<style>
				@media screen and (min-width: 1.6875em){
					body:before, body {
						background: url(<?php echo get_option( 'csm_img1'); ?>) no-repeat !important;
						background-size:  cover !important;
						width:100%;	
				}
			}
			</style>
		<?php endif;
	}
	}
	add_action('wp_head','csm_img');
	
/*********************************************************************************************************
* Background Color
**********************************************************************************************************/		
	
		function csm_background_color() { ?>
			<style type="text/css">
				<?php if(esc_html(get_option( 'csm_background_color' ))) : ?> .csm-construction { background: <?php echo esc_html(get_option( 'csm_background_color' )); ?> !important;}<?php endif; ?>
			</style>
		<?php
		}

	add_action('wp_head', 'csm_background_color'); 
	