<?php

class AutoMSCreatorID_Admin {
	private $auto_ms_creator_id_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'auto_ms_creator_id_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'auto_ms_creator_id_page_init' ) );

		add_filter( 'plugin_action_links_auto-mscreatorid/autoMSCreatorId.php', array($this, 'AutoMSCreatorID_settings_link') );
	}

	public function auto_ms_creator_id_add_plugin_page() {
		add_options_page(
			'Auto MS Creator ID', // page_title
			'Auto MS Creator ID', // menu_title
			'manage_options', // capability
			'auto-ms-creator-id', // menu_slug
			array( $this, 'auto_ms_creator_id_create_admin_page' ) // function
		);
	}

	public function auto_ms_creator_id_create_admin_page() {
		$this->auto_ms_creator_id_options = get_option( 'auto_ms_creator_id_option_name' ); ?>

		<div class="wrap">
			<h2>Auto MS Creator ID</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'auto_ms_creator_id_option_group' );
					do_settings_sections( 'auto-ms-creator-id-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function auto_ms_creator_id_page_init() {
		register_setting(
			'auto_ms_creator_id_option_group', // option_group
			'auto_ms_creator_id_option_name', // option_name
			array( $this, 'auto_ms_creator_id_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'auto_ms_creator_id_setting_section', // id
			'Settings', // title
			array( $this, 'auto_ms_creator_id_section_info' ), // callback
			'auto-ms-creator-id-admin' // page
		);

		add_settings_field(
			'creator_id', // id
			'Creator ID', // title
			array( $this, 'creator_id_callback' ), // callback
			'auto-ms-creator-id-admin', // page
			'auto_ms_creator_id_setting_section' // section
		);

		add_settings_field(
			'urls', // id
			'URLs', // title
			array( $this, 'urls_callback' ), // callback
			'auto-ms-creator-id-admin', // page
			'auto_ms_creator_id_setting_section' // section
		);
	}

	public function auto_ms_creator_id_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['creator_id'] ) ) {
			$sanitary_values['creator_id'] = sanitize_text_field( $input['creator_id'] );
		}

		if ( isset( $input['urls'] ) ) {
			$sanitary_values['urls'] = esc_textarea( $input['urls'] );
		}

		return $sanitary_values;
	}

	public function auto_ms_creator_id_section_info() {
		
	}

	public function creator_id_callback() {
		printf(
			'<input class="regular-text" type="text" name="auto_ms_creator_id_option_name[creator_id]" id="creator_id" value="%s">',
			isset( $this->auto_ms_creator_id_options['creator_id'] ) ? esc_attr( $this->auto_ms_creator_id_options['creator_id']) : ''
		);
	}

	public function urls_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="auto_ms_creator_id_option_name[urls]" id="urls">%s</textarea>',
			isset( $this->auto_ms_creator_id_options['urls'] ) ? esc_attr( $this->auto_ms_creator_id_options['urls']) : 'learn.microsoft.com,social.technet.microsoft.com,azure.microsoft.com,techcommunity.microsoft.com,social.msdn.microsoft.com,devblogs.microsoft.com,developer.microsoft.com,channel9.msdn.com,gallery.technet.microsoft.com,cloudblogs.microsoft.com,technet.microsoft.com,docs.azure.cn,www.azure.cn,msdn.microsoft.com,blogs.msdn.microsoft.com,blogs.technet.microsoft.com,microsoft.com/handsonlabs'
		);
	}

	/* Add settings link to plugin page */
	public function AutoMSCreatorID_settings_link( $links ) {
		// Build and escape the URL.
		$url = esc_url( add_query_arg(
			'page',
			'auto-ms-creator-id',
			get_admin_url() . 'options-general.php'
		) );
		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
		// Adds the link to the end of the array.
		array_push(
			$links,
			$settings_link
		);
		return $links;
	}

}

/* 
 * Retrieve this value with:
 * $auto_ms_creator_id_options = get_option( 'auto_ms_creator_id_option_name' ); // Array of All Options
 * $creator_id_0 = $auto_ms_creator_id_options['creator_id']; // Creator ID
 * $urls_1 = $auto_ms_creator_id_options['urls']; // URLs
 */
?>