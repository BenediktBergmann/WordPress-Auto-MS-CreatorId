<?php
/**
 * Plugin Name: Auto MS Creator ID
 * Plugin URI:  https://github.com/BenediktBergmann/WordPress-Anchor-Plugin
 * Description: Adds creator ID to every applicable link.
 * Version:     1.0.0
 * Author:      Benedikt Bergmann
 * Author URI:  https://benediktbergmann.eu
 * Text Domain: Auto-MSCreatorID 
 * License:     GPL3
 */
/* ------------- Admin Settings ------------------- */

require plugin_dir_path( __FILE__ ) . 'admin/class-autoMSCreatorId-admin.php';

new AutoMSCreatorID_Admin();

/* ------------- Plugin functionality ------------------- */

	class AutoMSCreatorID_addCreatorID{
		private $urls;
		private $creator_id;

		public function __construct()
		{
			$auto_ms_creator_id_options = get_option( 'auto_ms_creator_id_option_name' ); // Array of All Options
			$this->urls = $auto_ms_creator_id_options['urls']; // URLs
			$this->creator_id = $auto_ms_creator_id_options['creator_id']; // Creator ID
		}

		public function custom_callback($matches) {            
			$urlsArray = explode(",", $this->urls);
			
			$link = $matches[1];

			if(count($urlsArray) === 0 || strlen( $this->creator_id ) === 0 || $this->stringContainsArrayOneOf($link, $urlsArray) === false){
				return 'href="'.$link.'"';
			}
			
			if (strpos($link, '#') !== false) {
			list($link, $hash) = explode('#', $link);
			}
			$res = parse_url($link);
			
			$result = '';
			if (isset($res['scheme'])) {
				$result .= $res['scheme'].'://';
			} else if(isset($res['host'])) {
				$result .= '//';
			}
			if (isset($res['host'])) {
			$result .= $res['host'];
			}
			if (isset($res['path'])) {
			$result .= $res['path'];
			} else {
			$result .= '/';
			}
			
			if (isset($res['query'])) {
			parse_str($res['query'], $res['query']);
			} else {
			$res['query'] = [];
			}
			
			$res['query']['WT.mc_id'] = $this->creator_id;
			
			if (count($res['query']) > 0) {
			$result .= '?'.http_build_query($res['query']);
			}
			if (isset($hash)) {
			$result .= '#'.$hash;
			}

			return 'href="'.$result.'"';
		}

		private function stringContainsArrayOneOf($string, $urlsArray){
			foreach ($urlsArray as $url) {
				if (strpos($string, $url) !== false) {
					return true;
				}
			}
			return false;
		}
	}

	function AutoMSCreatorID_add_ms_creator_id( $content ) {

		$instance = new AutoMSCreatorID_addCreatorID();

		$content = preg_replace_callback('/href="([^"]+)"/i', array($instance, 'custom_callback'), $content );

		return $content;

	}
	add_filter( 'the_content', 'AutoMSCreatorID_add_ms_creator_id' );
?>
