<?php
/**
* Plugin Name: SSL REDIRECT
* Plugin URI: https://wpoven.com
* Description: The ssl redirect redirect web site to HTTPS://
* Version: 0.0.1
* Author: BaseApp
* Author URI: https://wpoven.com
* */


class SSLRedirect {
	private $redirect;
	private $uri;
	private $domain;
	private $site_url;
	private $home_url;

	
	function __construct() {
		$this->uri = $_SERVER['REQUEST_URI'];
		$this->domain = $_SERVER['HTTP_HOST'];
		$this->site_url = get_site_url();
		$this->home_url = home_url();
		
		add_action( 'admin_init', array($this, 'ssl_register_settings'));
		add_action( 'admin_menu', array($this, 'ssl_register_options_page'));
		add_action( 'template_redirect', array($this, "ssl_redirect_function"));
		
	}

	
	public function ssl_redirect_function(){
		$this->redirect = esc_attr(get_option('ssl-redirect-check'));
				
		if ($this->redirect == "true") {

			if($this->site_url != "https://". $this->domain && $this->home_url != "https://". $this->domain){
 			
	 			if(isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] )){	

					if ($_SERVER['HTTPS'] != "on") {
						$ssl_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
				        wp_redirect($ssl_url, 301);
				        exit();
					}
					
			    }
			} else {
				
				if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
				   
				    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
				    exit();
				}
			}
		}
		
	}



	if ( !is_ssl() && is_admin() ) {

		        if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {

		            wp_redirect( preg_replace( '|^http://|', 'https://', $_SERVER['REQUEST_URI'] ), 301 );

		            exit();

		        } else {

		            wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );

		            exit();

		        }

		    }

	public function ssl_register_options_page() {
		add_options_page('SSL Redirect', 'SSL Redirect', 'manage_options', 'ssl_redirect', array($this, 'ssl_redirect_plugin_options_page'));
	}

	public function ssl_register_settings() {
	   
	   register_setting( 'ssl_option_group', 'ssl-redirect-check', array( $this, 'sanitize' ) );
	   
	}

	public function ssl_redirect_plugin_options_page() {
		$redirect = esc_attr(get_option('ssl-redirect-check'));
		?>
		<div>
		<h2>SSL Redirect</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'ssl_option_group' ); ?>
				<table>
					<tr valign="top">
						<td><label for="ssl-redirect-check">SSL-Redirect <input type="checkbox" id="ssl-redirect-check" name="ssl-redirect-check" value="true" <?php echo ($redirect == 'true') ? 'checked' : 'unchecked'; ?>></label></td>
						
					</tr>
					
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
new SSLRedirect();

