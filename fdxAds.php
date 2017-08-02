<?php
/*
Plugin Name: flytedesk ads 
Description: A plugin to help implement the flytedesk ad tags into a website
Author: flytedesk inc.
Version: 1.2.1

*/

function fdxAdsShortCode_func( $atts ) 
{
	switch ($atts['align'])
	{
		case "left":
			$align="left";
			break;
		case "none":
			$align="none";
			break;
		case "right":
			$align="right";
			break;
		default:
			$align="none";
			break;
	}
	$containerString = ($atts['container'] != "") ? "data-container=\"" . $atts['container'] . "\"" : "";

	$shortCodeEnabled = get_option( 'fdxAdsShortCodeEnable', 'false' );
	$widgetEnabled = get_option( 'fdxAdsWidgetEnable', 'false' );
	if(($widgetEnabled == "false") && ($shortCodeEnabled == "true"))
	{
		add_action('wp_footer',create_function( '', 'echo(\'<script type="text/javascript" data-id="fdx" ' . $containerString . ' data-float="' . $align . '" data-token="' . $atts['id'] . '" src="//static.flytedesk.com/fdx.js"></script><!-- version 1.2.1 -->\');' ),1,0);
		return $retString;
	}
	else 
	{
		return "";
	}
}
add_shortcode( 'fdxAds', 'fdxAdsShortCode_func' );

add_action( 'admin_init', 'fdxAds_admin_init' );
function fdxAds_admin_init() 
{
   wp_register_style( 'fdxAdsStyle', plugins_url('css/fdxAdsStyle.css', __FILE__) );
}
function fdxAds_admin_styles() 
{
   wp_enqueue_style( 'fdxAdsStyle' );
}

function fdxAds_plugin_setup_menu(){
	$page = add_menu_page( 'flytedesk Ads', 'flytedesk Ads', 'manage_options', 'fdxAds', 'fdxAds_init', plugins_url('assets/flytedesk-icon.png', __FILE__) );
	add_action( 'admin_print_styles-' . $page, 'fdxAds_admin_styles' ); 
}
add_action('admin_menu', 'fdxAds_plugin_setup_menu');

function fdxAds_init()
{
	$widgetEnable = get_option( 'fdxAdsWidgetEnable', 'false' );
	$shortCodeEnabled = get_option('fdxAdsShortCodeEnable', 'false');

	$fdxAlign = get_option('fdxAlign', 'none');
	?>
	<input type="hidden" id="fdxAlignValue" value='<?php echo $fdxAlign ?>'>
	<div class="container" style="text-align:left;">
		<div class="row" style="padding-top:15px;padding-bottom:15px;">
			<div class="col-sm-2">
				<img src="<?php echo plugins_url('assets/flytedesk-logo.png', __FILE__); ?>" class="img-responsive" />		
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">	
				<p>This plugin is designed to make it really easy to implement flytedesk ad tags.  
				You have three options: 

				<div>1.  Just enter the specific ID that you recieve from flytedesk in the box below and click "Save".  Ads will appear on every page of your site.</div>
				<blockquote>
			    <span class="fourteen">Enter the ID that you recieved from flytedesk here.  If you have not recieved one yet, use the test ID (79391).</span>
				<div><input type="text" id="fdxAdsId" name="fdxAdsId" value="<?php echo get_option( 'fdxAdsId', '79391' ); ?>" /></div>
				</blockquote>
				2. Use a widget, which gives you a little more control over which pages the ads will appear on.  Ads will only appear on the pages which contain this widget.
				<blockquote>
				<div style="padding-top:15px;padding-bottom:15px;"><input style="margin:0px;" type="checkbox" id="fdxAdsWidgetEnable" name="fdxAdsWidgetEnable" value="1" <?php checked( 'true', $widgetEnable ); ?>/><span class="fourteen">&nbsp;&nbsp;Check this box to use the widget instead of the default plugin behavior.</span></div>
				<div id="fdxWidgetMessage" class="fourteen" style="display:none;">Make sure to visit <strong>Appearance->Widgets</strong> and add the &quot;flytedesk Ads&quot; widget to a sidebar.  Then add the ID you got from flytedesk and click save!</div>
				</blockquote>
				3. Use a shortcode, which will give you precise control over where the ads display, but you will have to paste it into every page/post.  Remember the more places you run the ads, the more $$ you make!
				<blockquote>
				<div style="padding-top:15px;padding-bottom:15px;"><input style="margin:0px;" type="checkbox" id="fdxAdsShortCodeEnable" name="fdxAdsShortCodeEnable" value="1" <?php checked( 'true', $shortCodeEnabled ); ?>/><span class="fourteen">&nbsp;&nbsp;Check this box to use the shortcode.</span>
				<div id="fdxAdsShortCodeMessage" class="fourteen" style="display:none;">To use the shortcode, just copy and paste this [fdxAds id="79391" align=left|none|right] (only use one of "left","none" or "right") into any page or post.  Remember to replace 79391 with your ID once you get it.</div></blockquote>



				<strong>THATS IT!</strong>  The plugin ads a script tag to the footer of your site.  That script tag will place the ad unit after the first paragraph tag it finds on a given page.
				<br/><br/>
				
				<div class="row row-pad">
					<div class="col-sm-4">
						<div style="padding-bottom:15px;">If you would like to customize if and how the ads are wrapped within the content of the page, select one of the options below.  Leaving "none" selected will have no effect.</div>
						<div class="row row-pad">
							<div class="col-sm-4 text-center">
								<img src="<?php echo plugins_url('assets/align-left.png', __FILE__); ?>" class="img-responsive"  />
								<div>Align Left</div>
								<input type="radio" id="fdxAlignLeft" name="fdxAlign" value="left" <?php checked( 'left', $fdxAlign ); ?> >
							</div>
							<div class="col-sm-4 text-center">
								<img src="<?php echo plugins_url('assets/align-none.png', __FILE__); ?>" class="img-responsive" <?php checked( 'none', $fdxAlign ); ?> />			
								<div>Align None</div>
								<input type="radio" id="fdxAlignNone" name="fdxAlign" value="none" <?php checked( 'none', $fdxAlign ); ?> >
							</div>
							<div class="col-sm-4 text-center">
								<img src="<?php echo plugins_url('assets/align-right.png', __FILE__); ?>" class="img-responsive" <?php checked( 'right', $fdxAlign ); ?> />		
								<div>Align Right</div>
								<input type="radio" id="fdxAlignRight" name="fdxAlign" value="right" <?php checked( 'right', $fdxAlign ); ?> >
							</div>
						</div>

					</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-4">
						<div class="row row-pad">
							<div class="col-sm-12">Enter a classname or id of your content container <input type="text" id="fdxContainer" name="fdxContainer" value="<?php echo get_option( 'fdxContainer', '' ); ?>"></div>
							<small>Use this if the ads are not appearing in the main body of your content.  Typically your site will have a DIV tag as a container for your articles.  Enter either the classname or the ID of that container here.
						</div>
					</div>

				</div>



				</p>
			    <br/> 
			    <input type="button" id="fdxAdsBtnUpdate" class="btn btn-primary" name="fdxAdsBtnUpdate" value="Save" /><span id="fdxAdsMessage" style="padding-left:15px;"></span>
			    <br/><br/>

			    <div><small>For testing, please use a shortcode: [fdxAds id="79391" align="none"].  Place that code into a test page you create.  Email us the URL to that page at &quot;piper@flytedesk.com&quot;.  You should have instructions from flytedesk via email.</small></div>
			    <div><small>** note, your theme must implement <a href="https://codex.wordpress.org/Function_Reference/wp_footer" target="_blank">&quot;wp_footer&quot;</a> (which is pretty standard), and your pages must contain at least one paragraph tag (&lt;p&gt;), also pretty standard.</small></div>
			</div>
		</div>
	</div>
    <?php
}

function fdxAds_save_click_javascript() 
{ 
	?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) 
	{
		$("#fdxAlignLeft").click(function(){
			$("#fdxAlignValue").val('left');
		});
		$("#fdxAlignNone").click(function(){
			$("#fdxAlignValue").val('none');
		});
		$("#fdxAlignRight").click(function(){
			$("#fdxAlignValue").val('right');
		});

		if($("#fdxAdsWidgetEnable").is(":checked"))
		{
			$("#fdxWidgetMessage").fadeIn();
			$("#fdxAdsId").prop('disabled','disabled');
			$("#fdxAdsShortCodeEnable").prop("checked", false);
		}
		else
		{
			$("#fdxWidgetMessage").fadeOut();	
			$("#fdxAdsId").prop('disabled','');
		}

		if($("#fdxAdsShortCodeEnable").is(":checked"))
		{
			$("#fdxAdsShortCodeMessage").fadeIn();
			$("#fdxAdsId").prop('disabled','disabled');
			$("#fdxAdsWidgetEnable").prop("checked", false);
		}
		else
		{
			$("#fdxAdsShortCodeMessage").fadeOut();	
			$("#fdxAdsId").prop('disabled','');
		}	

		$("#fdxAdsWidgetEnable").click(function()
		{
			if($("#fdxAdsWidgetEnable").is(":checked"))
			{
				$("#fdxAdsShortCodeMessage").fadeOut();	
				$("#fdxWidgetMessage").fadeIn();
				$("#fdxAdsId").prop('disabled','disabled');
				$("#fdxAdsShortCodeEnable").prop("checked", false);
			}
			else
			{
				$("#fdxWidgetMessage").fadeOut();	
				$("#fdxAdsId").prop('disabled','');
			}
		});

		$("#fdxAdsShortCodeEnable").click(function()
		{
			if($("#fdxAdsShortCodeEnable").is(":checked"))
			{
				$("#fdxAdsShortCodeMessage").fadeIn();
				$("#fdxWidgetMessage").fadeOut();
				$("#fdxAdsId").prop('disabled','disabled');
				$("#fdxAdsWidgetEnable").prop("checked", false);
			}
			else
			{
				$("#fdxAdsShortCodeMessage").fadeOut();	
				$("#fdxAdsId").prop('disabled','');
			}
		});


		$("#fdxAdsBtnUpdate").click(function()
		{
			var data = 
			{
				'action': 'fdxadssave',
				'fdxAdsId': $("#fdxAdsId").val(),
				'fdxAdsWidgetEnable': $("#fdxAdsWidgetEnable").is(":checked"),
				'fdxAdsShortCodeEnable': $("#fdxAdsShortCodeEnable").is(":checked"),
				'fdxAlign': $("#fdxAlignValue").val(),
				'fdxContainer': $("#fdxContainer").val()
			};
			$.post(ajaxurl, data, function(response) 
			{
				$("#fdxAdsMessage").html(response);
				$("#fdxAdsMessage").fadeIn().delay(5000).fadeOut();
			});
		});
	});
	</script> 
	<?php
}
add_action( 'admin_footer', 'fdxAds_save_click_javascript' ); 

function fdxadssave_callback() 
{
	update_option( 'fdxAdsId', $_POST['fdxAdsId'] );
	update_option( 'fdxAdsWidgetEnable', $_POST['fdxAdsWidgetEnable'] );
	update_option( 'fdxAdsShortCodeEnable', $_POST['fdxAdsShortCodeEnable'] );
	update_option( 'fdxAlign', $_POST['fdxAlign'] );
	update_option( 'fdxContainer', $_POST['fdxContainer'] );

    echo "Save Successful";
	wp_die(); // this is required to terminate immediately and return a proper response
}
add_action( 'wp_ajax_fdxadssave', 'fdxadssave_callback' );

function fdxAds_AddJsToFooter() 
{
	$widgetEnabled = get_option( 'fdxAdsWidgetEnable', 'false' );
	$shortCodeEnabled = get_option( 'fdxAdsShortCodeEnable', 'false' );
	$fdxContainer = get_option('fdxContainer', '');
	$containerData = ($fdxContainer !="") ? "data-container='" . $fdxContainer . "'" : "";
	if(($widgetEnabled == "false") && ($shortCodeEnabled == "false"))
	{
		?>
		<script type="text/javascript" data-id='fdx' <?php echo $containerData; ?> data-float='<?php echo get_option('fdxAlign','none'); ?>' data-token='<?php echo get_option( 'fdxAdsId', '79391' ); ?>' src="//static.flytedesk.com/fdx.js"></script><!-- version 1.2.1 -->
		<?php
	}
}
add_action( 'wp_footer', 'fdxAds_AddJsToFooter' );

/**
 * Add fdxAdsWidget
 */
class fdxAdsWidget extends WP_Widget 
{
	function __construct() 
	{
		parent::__construct(
			'fdxAds_Widget', // Base ID
			__('flytedesk Ads', 'text_domain'), // Name
			array( 'description' => __( 'Add flytedesk ads.', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) 
	{
		$shortCodeEnabled = get_option( 'fdxAdsShortCodeEnable', 'false' );
		$widgetEnabled = get_option( 'fdxAdsWidgetEnable', 'false' );
		$fdxContainer = str_replace(' ','',$instance['fdxContainer']);

		$containerData = ($fdxContainer !="") ? "data-container=\"" . $fdxContainer . "\"" : "";
		if(($widgetEnabled == "true") && ($shortCodeEnabled == "false"))
		{
			add_action('wp_footer',create_function( '', 'echo(\'<script type="text/javascript" data-id="fdx" ' . $containerData . ' data-float="' . $instance['fdxAlign'] . '" data-token="' . $instance['fdxAdsId'] . '" src="//static.flytedesk.com/fdx.js"></script><!-- version 1.2.1 -->\');' ),1,0);
		}
	}

	public function form( $instance ) 
	{
		$fdxAlign = (isset($instance['fdxAlign'])) ? $instance['fdxAlign'] : 'none';
		if ( isset( $instance[ 'fdxAdsId' ] ) ) 
		{
			$fdxAdsId = $instance[ 'fdxAdsId' ];
		}
		else 
		{
			$fdxAdsId = __( '79391', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'fdxAdsId' ); ?>"><?php _e( 'Place the ID provided by flytedesk here (79391 is a test id):' ); ?></label> 
			<input class="widefat fdxAdIdClass" onblur="fdxAdsCheckValue(event);"  id="<?php echo $this->get_field_id( 'fdxAdsId' ); ?>" name="<?php echo $this->get_field_name( 'fdxAdsId' ); ?>" type="text" value="<?php echo esc_attr( $fdxAdsId ); ?>">
 			<div style="margin-bottom:3px;">Ad Alignment:</div>
			<div>
				Left <input type="radio" id="<?php echo $this->get_field_id( 'fdxAlign' ); ?>_left" name="<?php echo $this->get_field_name( 'fdxAlign' ); ?>" <?php checked( 'left', $fdxAlign ); ?> value="left">
 				None <input type="radio" id="<?php echo $this->get_field_id( 'fdxAlign' ); ?>_none" name="<?php echo $this->get_field_name( 'fdxAlign' ); ?>" <?php checked( 'none', $fdxAlign ); ?> value="none">
				Right <input type="radio" id="<?php echo $this->get_field_id( 'fdxAlign' ); ?>_right" name="<?php echo $this->get_field_name( 'fdxAlign' ); ?>" <?php checked( 'right', $fdxAlign ); ?> value="right">
			</div> 
			<div style="margin-top:8px;">
				Data Container ID (optional)
			</div> 						
			<div>
				<input type="text" id="<?php echo $this->get_field_id( 'fdxContainer' ); ?>" name="<?php echo $this->get_field_name( 'fdxContainer' ); ?>" value="<?php echo $instance['fdxContainer']; ?>" />
			</div>
		</p>
		<script type="text/javascript">
			function fdxAdsCheckValue(e)
			{
				fdxAdsId_x = document.getElementById(e.target.id);
				if((fdxAdsId_x.value.match(/[^$,.\d]/)) || (fdxAdsId_x.value == ""))
				{
					fdxAdsId_x.value = "79391";
				}
			}
		</script>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['fdxAdsId'] = ( ! empty( $new_instance['fdxAdsId'] ) ) ? strip_tags( $new_instance['fdxAdsId'] ) : '';
		$instance['fdxAlign'] = ( ! empty( $new_instance['fdxAlign'] ) ) ? strip_tags( $new_instance['fdxAlign'] ) : '';
		$instance['fdxContainer'] = ( ! empty( $new_instance['fdxContainer'] ) ) ? strip_tags( $new_instance['fdxContainer'] ) : '';
		return $instance;
	}
} // end class fdxAdsWidget

add_action( 'widgets_init', function()
{
     register_widget( 'fdxAdsWidget' );
});	
?>
