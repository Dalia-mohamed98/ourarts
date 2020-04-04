<?php

class WPML_TM_Page {

	private static function is_tm_page( $page = null ) {
		return is_admin() && isset( $_GET['page'] ) && $_GET['page'] === WPML_TM_FOLDER . $page;
	}

	public static function is_tm_dashboard() {
		$is_tm_page = self::is_tm_page( WPML_Translation_Management::PAGE_SLUG_MANAGEMENT );

		if ( ! $is_tm_page ) {
			return false;
		}

		return ! isset( $_GET['sm'] ) || ( isset( $_GET['sm'] ) && $_GET['sm'] === 'dashboard' );
	}

	public static function is_tm_translators() {
		return self::is_tm_page( WPML_Translation_Management::PAGE_SLUG_MANAGEMENT )
		       && isset( $_GET['sm'] ) && $_GET['sm'] === 'translators';
	}

	public static function is_settings() {
		return self::is_tm_page( WPML_Translation_Management::PAGE_SLUG_SETTINGS )
		       && ( ! isset( $_GET['sm'] ) || $_GET['sm'] === 'mcsetup' );
	}

	public static function is_translation_queue() {
		return self::is_tm_page( WPML_Translation_Management::PAGE_SLUG_QUEUE );
	}

	public static function is_translation_editor_page() {
		return self::is_translation_queue() && isset( $_GET['job_id'] );
	}

	public static function is_job_list() {
		return self::is_tm_page( WPML_Translation_Management::PAGE_SLUG_MANAGEMENT )
		       && isset( $_GET['sm'] ) && $_GET['sm'] === 'jobs';
	}

	public static function is_dashboard() {
		return self::is_tm_page( WPML_Translation_Management::PAGE_SLUG_MANAGEMENT )
		       && ( ! isset( $_GET['sm'] ) || $_GET['sm'] === 'dashboard' );
	}

	public static function is_notifications_page() {
		return self::is_tm_page( WPML_Translation_Management::PAGE_SLUG_MANAGEMENT )
		       && isset( $_GET['sm'] ) && $_GET['sm'] === 'notifications';
	}

	public static function get_translators_url( $params = array() ) {
		$url          = admin_url( 'admin.php?page=' . WPML_TM_FOLDER . '/menu/main.php' );
		$params['sm'] = 'translators';

		return add_query_arg( $params, $url );
	}
}