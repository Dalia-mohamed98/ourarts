<?php
namespace WebAppick\AppServices;
use WP_Theme;
use WP_User;

/**
 * WebAppick Insights
 *
 * This is a tracker class to track plugin usage based on if the customer has opted in.
 * No personal information is being tracked by this class, only general settings, active plugins, environment details
 * and admin email.
 */
class Insights {
	
	/**
	 * The notice text
	 *
	 * @var string
	 */
	public $notice;
	
	/**
	 * Wheather to the notice or not
	 *
	 * @var boolean
	 */
	protected $show_notice = true;
	
	/**
	 * If extra data needs to be sent
	 *
	 * @var array
	 */
	protected $extra_data = array();
	
	/**
	 * WebAppick\AppServices\Client
	 *
	 * @var Client
	 */
	protected $client;
	
	/**
	 * Initialize the class
	 *
	 * @param Client $client
	 * @param string $name
	 * @param string $file
	 */
	public function __construct( $client, $name = null, $file = null ) {
		if ( is_string( $client ) && ! empty( $name ) && ! empty( $file ) ) {
			$client = new Client( $client, $name, $file );
		}
		
		if ( is_object( $client ) && is_a( $client, 'WebAppick\AppServices\Client' ) ) {
			$this->client = $client;
		}
	}
	
	/**
	 * Don't show the notice
	 *
	 * @return Insights
	 */
	public function hide_notice() {
		$this->show_notice = false;
		return $this;
	}
	
	/**
	 * Add extra data if needed
	 *
	 * @param array $data
	 *
	 * @return Insights
	 */
	public function add_extra( $data = array() ) {
		$this->extra_data = $data;
		
		return $this;
	}
	
	/**
	 * Set custom notice text
	 *
	 * @param  string $text
	 *
	 * @return Insights
	 */
	public function notice( $text ) {
		$this->notice = $text;
		
		return $this;
	}
	
	/**
	 * Initialize insights
	 *
	 * @return void
	 */
	public function init() {
		if ( $this->client->type == 'plugin' ) {
			$this->init_plugin();
		} else if ( $this->client->type == 'theme' ) {
			$this->init_theme();
		}
	}
	
	/**
	 * Initialize theme hooks
	 *
	 * @return void
	 */
	public function init_theme() {
		$this->init_common();
		
		add_action( 'switch_theme', array( $this, 'deactivation_cleanup' ) );
		add_action( 'switch_theme', array( $this, 'theme_deactivated' ), 12, 3 );
	}
	
	/**
	 * Initialize plugin hooks
	 *
	 * @return void
	 */
	public function init_plugin() {
		// plugin deactivate popup
		if ( ! $this->__is_local_server() ) {
			add_action( 'plugin_action_links_' . $this->client->basename, array( $this, 'plugin_action_links' ) );
			add_action( 'admin_footer', array( $this, 'deactivate_scripts' ) );
		}
		
		$this->init_common();
		
		register_activation_hook( $this->client->file, array( $this, 'activate_plugin' ) );
		register_deactivation_hook( $this->client->file, array( $this, 'deactivation_cleanup' ) );
	}
	
	/**
	 * Initialize common hooks
	 *
	 * @return void
	 */
	protected function init_common() {
		if ( $this->show_notice ) {
			// tracking notice
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		}
		add_action( 'admin_init', array( $this, 'handle_optIn_optOut' ) );
		add_action( 'removable_query_args', array( $this, 'add_removable_query_args' ), 10, 1 );
		// uninstall reason
		add_action( 'wp_ajax_' . $this->client->slug . '_submit-uninstall-reason', array( $this, 'uninstall_reason_submission' ) );
		// cron events
		add_filter( 'cron_schedules', array( $this, 'add_weekly_schedule' ) );
		add_action( $this->client->slug . '_tracker_send_event', array( $this, 'send_tracking_data' ) );
		// add_action( 'admin_init', array( $this, 'send_tracking_data' ) ); // test
	}
	
	/**
	 * Send tracking data to WebAppick server
	 *
	 * @param  boolean  $override
	 *
	 * @return void
	 */
	public function send_tracking_data( $override = false ) {
		// skip on AJAX Requests
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) return;
		if ( ! $this->__tracking_allowed() && ! $override ) return;
		// Send a maximum of once per week
		$last_send = $this->__get_last_send();
		if ( $last_send && $last_send > strtotime( '-1 week' ) ) return;
		$this->client->send_request( $this->get_tracking_data(), 'track' );
		update_option( $this->client->slug . '_tracking_last_send', time() );
	}
	
	/**
	 * Get the tracking data points
	 *
	 * @return array
	 */
	protected function get_tracking_data() {
		$all_plugins = $this->__get_all_plugins();
		$admin_user =  $this->__get_admin();
		//@TODO get batch limit
		//@TODO get php (config) execution time
		$data = array(
			'version'          => $this->client->project_version,
			'url'              => esc_url( home_url() ),
			'site'             => $this->__get_site_name(),
			'admin_email'      => get_option( 'admin_email' ) . ',' . $admin_user->user_email,
			'first_name'       => $admin_user->first_name ? $admin_user->first_name : $admin_user->display_name,
			'last_name'        => $admin_user->last_name,
			'hash'             => $this->client->hash,
			'server'           => $this->__get_server_info(),
			'wp'               => $this->__get_wp_info(),
			'users'            => $this->__get_user_counts(),
			'active_plugins'   => $all_plugins['active_plugins'],
			'inactive_plugins' => $all_plugins['inactive_plugins'],
			'ip_address'       => $this->__get_user_ip_address(),
			'theme'            => get_stylesheet(),
		);
		// for child classes
		if ( $extra = $this->get_extra_data() ) {
			$data['extra'] = $extra;
		}
		return apply_filters( $this->client->slug . '_tracker_data', $data );
	}
	
	/**
	 * If a child class wants to send extra data
	 *
	 * @return mixed
	 */
	protected function get_extra_data() {
		return $this->extra_data;
	}
	
	/**
	 * Explain the user which data we collect
	 *
	 * @return array
	 */
	protected function data_we_collect() {
		$data = array(
			esc_html__( 'Server environment details (php, mysql, server, WordPress versions)', 'webappick' ),
			esc_html__( 'Number of users in your site', 'webappick' ),
			esc_html__( 'Number of products in your site', 'webappick' ),
			esc_html__( 'Site language', 'webappick' ),
			esc_html__( 'Number of active and inactive plugins', 'webappick' ),
			esc_html__( 'Site name and url', 'webappick' ),
			esc_html__( 'Your name and email address', 'webappick' ),
		);
		return $data;
	}
	
	/**
	 * Get Site SuperAdmin
	 * Returns Empty WP_User instance if fails
	 * @return WP_User
	 */
	private function __get_admin() {
		$admins = get_users( array(
			'role'    => 'administrator',
			'orderby' => 'ID',
			'order'   => 'ASC',
			'number'  => 1,
			'paged'   => 1,
		) );
		return ( is_array( $admins ) && ! empty( $admins ) ) ? $admins[0] : new WP_User();
	}
	
	/**
	 * Check if the user has opted into tracking
	 *
	 * @return bool
	 */
	private function __tracking_allowed() {
		return 'yes' == get_option( $this->client->slug . '_allow_tracking', 'no' );
	}
	
	/**
	 * Get the last time a tracking was sent
	 *
	 * @return false|string
	 */
	private function __get_last_send() {
		return get_option( $this->client->slug . '_tracking_last_send', false );
	}
	
	/**
	 * Check if the notice has been dismissed or enabled
	 *
	 * @return boolean
	 */
	private function __notice_dismissed() {
		$hide_notice = get_option( $this->client->slug . '_tracking_notice', 'no' );
		if ( 'hide' == $hide_notice ) return true;
		return false;
	}
	
	/**
	 * Check if the current server is localhost
	 *
	 * @return boolean
	 */
	private function __is_local_server() {
		return apply_filters( 'WebAppick_is_local', in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) );
	}
	
	/**
	 * Schedule the event weekly
	 *
	 * @return void
	 */
	private function __schedule_event() {
		$hook_name = $this->client->slug . '_tracker_send_event';
		if ( ! wp_next_scheduled( $hook_name ) ) {
			wp_schedule_event( time(), 'weekly', $hook_name );
		}
	}
	
	/**
	 * Clear any scheduled hook
	 *
	 * @return void
	 */
	private function __clear_schedule_event() {
		wp_clear_scheduled_hook( $this->client->slug . '_tracker_send_event' );
	}
	
	/**
	 * Display the admin notice to users that have not opted-in or out
	 *
	 * @return void
	 */
	public function admin_notice() {
		if ( $this->__notice_dismissed() ) return;
		
		if ( $this->__tracking_allowed() ) return;
		if ( ! current_user_can( 'manage_options' ) ) return;
		
		// don't show tracking if a local server
		if ( ! $this->__is_local_server() ) {
			$optOutUrl = add_query_arg( $this->client->slug . '_tracker_optOut', 'true' );
			$optInUrl  = add_query_arg( $this->client->slug . '_tracker_optIn', 'true' );
			
			if ( empty( $this->notice ) ) {
				$notice = sprintf( esc_html__( 'Want to help make %1$s even more awesome? Allow %1$s to collect non-sensitive diagnostic data and usage information.', 'webappick' ), '<strong>'.$this->client->name.'</strong>' );
			} else {
				$notice = $this->notice;
			}
			
			$notice .= ' (<a class="' . $this->client->slug . '-insights-data-we-collect" href="#">' . esc_html__( 'what we collect', 'webappick' ) . '</a>)';
			$notice .= '<p class="description" style="display:none;">' . implode( ', ', $this->data_we_collect() ) . '. '. esc_html__( 'No sensitive data is tracked.', 'webappick' ) .'</p>';
			echo '<div class="updated"><p>';
			echo $notice;
			echo '</p><p class="submit">';
			echo '&nbsp;<a href="' . esc_url( $optOutUrl ) . '" class="button button-secondary">' . esc_html__( 'No thanks', 'webappick' ) . '</a>';
			echo '&nbsp;<a href="' . esc_url( $optInUrl ) . '" class="button button-primary">' . esc_html__( 'Allow', 'webappick' ) . '</a>';
			echo '</p></div>';
			echo "<script type='text/javascript'>jQuery('." . $this->client->slug . "-insights-data-we-collect').on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).parents('.updated').find('p.description').slideToggle('fast');
            });</script>";
		}
	}
	
	/**
	 * handle the optIn/optOut
	 *
	 * @return void
	 */
	public function handle_optIn_optOut() {
		
		if( isset( $_GET[ $this->client->slug . '_tracker_optIn' ] ) && $_GET[ $this->client->slug . '_tracker_optIn' ] == 'true' ) {
			$this->optIn();
			wp_redirect( remove_query_arg( $this->client->slug . '_tracker_optIn' ) );
			exit;
		}
		if ( isset( $_GET[ $this->client->slug . '_tracker_optOut' ] ) && $_GET[ $this->client->slug . '_tracker_optOut' ] == 'true' ) {
			$this->optOut();
			wp_redirect( remove_query_arg( $this->client->slug . '_tracker_optOut' ) );
			exit;
		}
	}
	
	/**
	 * Add query vars to removable query args array
	 * @param array $removable_query_args
	 * @return array
	 */
	public function add_removable_query_args( $removable_query_args ) {
		return array_merge( $removable_query_args, array($this->client->slug . '_tracker_optIn', $this->client->slug . '_tracker_optOut' ) );
	}
	
	/**
	 * Tracking optIn
	 *
	 * @return void
	 */
	public function optIn() {
		update_option( $this->client->slug . '_allow_tracking', 'yes' );
		update_option( $this->client->slug . '_tracking_notice', 'hide' );
		$this->__clear_schedule_event();
		$this->__schedule_event();
		$this->send_tracking_data();
	}
	
	/**
	 * optOut from tracking
	 *
	 * @return void
	 */
	public function optOut() {
		update_option( $this->client->slug . '_allow_tracking', 'no' );
		update_option( $this->client->slug . '_tracking_notice', 'hide' );
		$this->__clear_schedule_event();
	}
	
	/**
	 * Get the number of post counts
	 *
	 * @param  string  $post_type
	 *
	 * @return integer
	 */
	public function get_post_count( $post_type ) {
		global $wpdb;
		return (int) $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts WHERE post_type = '$post_type' and post_status = 'publish'");
	}
	
	/**
	 * Get server related info.
	 *
	 * @return array
	 */
	private function __get_server_info() {
		global $wpdb;
		$server_data = array(
			'software'              => ( isset( $_SERVER['SERVER_SOFTWARE'] ) && ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) ? $_SERVER['SERVER_SOFTWARE'] : 'N/A',
			'php_version'           => ( function_exists( 'phpversion' ) ) ? phpversion() : 'N/A',
			'mysql_version'         => $wpdb->db_version(),
			'php_max_upload_size'   => size_format( wp_max_upload_size() ),
			'php_default_timezone'  => date_default_timezone_get(),
			'php_soap'              => class_exists( 'SoapClient' ) ? 'Yes' : 'No',
			'php_fsockopen'         => function_exists( 'fsockopen' ) ? 'Yes' : 'No',
			'php_curl'              => function_exists( 'curl_init' ) ? 'Yes' : 'No',
			'php_ftp'               => function_exists( 'ftp_connect' ) ? 'Yes' : 'No',
			'php_sftp'              => function_exists( 'ssh2_connect' ) ? 'Yes' : 'No',
		);
		return $server_data;
	}
	
	/**
	 * Get WordPress related data.
	 *
	 * @return array
	 */
	private function __get_wp_info() {
		$wp_data = array(
			'memory_limit'  => WP_MEMORY_LIMIT,
			'debug_mode'    => ( defined('WP_DEBUG') && WP_DEBUG ) ? 'Yes' : 'No',
			'locale'        => get_locale(),
			'version'       => get_bloginfo( 'version' ),
			'multisite'     => is_multisite() ? 'Yes' : 'No',
		);
		return $wp_data;
	}
	
	/**
	 * Get the list of active and inactive plugins
	 * @return array
	 */
	private function __get_all_plugins() {
		if( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}
		$plugins             = get_plugins();
		$active_plugins      = array();
		$active_plugins_keys = get_option( 'active_plugins', array() );
		foreach ( $plugins as $k => $v ) {
			// Take care of formatting the data how we want it.
			$formatted = array(
					'name'          => strip_tags( $v['Name'] ),
					'version'       => isset( $v['Version'] ) ? strip_tags( $v['Version'] ) : 'N/A',
					'author'        => isset( $v['Author'] ) ? strip_tags( $v['Author'] ) : 'N/A',
					'network'       => isset( $v['Network'] ) ? strip_tags( $v['Network'] ) : 'N/A',
					'plugin_uri'    => isset( $v['PluginURI'] ) ? strip_tags( $v['PluginURI'] ) : 'N/A',
			);
			if ( in_array( $k, $active_plugins_keys ) ) {
				unset( $plugins[$k] ); // Remove active plugins from list so we can show active and inactive separately
				$active_plugins[$k] = $formatted;
			} else {
				$plugins[$k] = $formatted;
			}
		}
		return array( 'active_plugins' => $active_plugins, 'inactive_plugins' => $plugins );
	}
	
	/**
	 * Get user totals based on user role.
	 *
	 * @return array
	 */
	public function __get_user_counts() {
		$user_count          = array();
		$user_count_data     = count_users();
		$user_count['total'] = $user_count_data['total_users'];
		// Get user count based on user role
		foreach ( $user_count_data['avail_roles'] as $role => $count ) {
			$user_count[ $role ] = $count;
		}
		return $user_count;
	}
	
	/**
	 * Add weekly cron schedule
	 *
	 * @param array  $schedules
	 *
	 * @return array
	 */
	public function add_weekly_schedule( $schedules ) {
		$schedules['weekly'] = array(
			'interval' => DAY_IN_SECONDS * 7,
			'display'  => __( 'Once Weekly', 'webappick' )
		);
		return $schedules;
	}
	
	/**
	 * Plugin activation hook
	 *
	 * @return void
	 */
	public function activate_plugin() {
		$allowed = get_option( $this->client->slug . '_allow_tracking', 'no' );
		// if it wasn't allowed before, do nothing
		if ( 'yes' !== $allowed ) return;
		// re-schedule and delete the last sent time so we could force send again
		wp_schedule_event( time(), 'weekly', $this->client->slug . '_tracker_send_event' );
		wp_schedule_event( time(), 'daily', $this->client->slug . '_license_check_event' );
		delete_option( $this->client->slug . '_tracking_last_send' );
		$this->send_tracking_data( true );
	}
	
	/**
	 * Clear our options upon deactivation
	 *
	 * @return void
	 */
	public function deactivation_cleanup() {
		$this->__clear_schedule_event();
		if ( 'theme' == $this->client->type ) {
			delete_option( $this->client->slug . '_tracking_last_send' );
			delete_option( $this->client->slug . '_allow_tracking' );
		}
		delete_option( $this->client->slug . '_tracking_notice' );
	}
	
	/**
	 * Hook into action links and modify the deactivate link
	 *
	 * @param  array  $links
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		
		if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace( '<a', '<a class="' . $this->client->slug . '-deactivate-link"', $links['deactivate'] );
		}
		
		return $links;
	}
	
	/**
	 * Deactivation reasons
	 * @return array
	 */
	private function __get_uninstall_reasons() {
		
		$reasons = array(
			
			array(
				'id'          => 'could-not-understand',
				'text'        => esc_html__( 'I couldn\'t understand how to make it work', 'webappick' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Would you like us to assist you?', 'webappick' ),
			),
			array(
				'id'          => 'found-better-plugin',
				'text'        => esc_html__( 'I found a better plugin', 'webappick' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Which plugin?', 'webappick' ),
			),
			array(
				'id'          => 'not-have-that-feature',
				'text'        => esc_html__( 'The plugin is great, but I need specific feature that you don\'t support', 'webappick' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Could you tell us more about that feature?', 'webappick' ),
			),
			array(
				'id'          => 'is-not-working',
				'text'        => esc_html__( 'The plugin is not working', 'webappick' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Could you tell us a bit more whats not working?', 'webappick' ),
			),
			array(
				'id'          => 'looking-for-other',
				'text'        => esc_html__( 'It\'s not what I was looking for', 'webappick' ),
				'type'        => '',
				'placeholder' => '',
			),
			array(
				'id'          => 'did-not-work-as-expected',
				'text'        => esc_html__( 'The plugin didn\'t work as expected', 'webappick' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'What did you expect?', 'webappick' ),
			),
			array(
				'id'          => 'other',
				'text'        => esc_html__( 'Other', 'webappick' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Could you tell us a bit more?', 'webappick' ),
			),
		);
		
		return $reasons;
	}
	
	/**
	 * Plugin deactivation uninstall reason submission
	 *
	 * @return void
	 */
	public function uninstall_reason_submission() {
		
		if ( ! isset( $_POST['reason_id'] ) ) wp_send_json_error();
		
		$current_user = wp_get_current_user();
		global $wpdb;
		// @TODO remove deprecated data after server update
		$data = array(
			'hash'          => $this->client->hash,
			'reason_id'     => sanitize_text_field( $_REQUEST['reason_id'] ), // WPCS: CSRF ok, Input var ok.
			'reason_info'   => isset( $_REQUEST['reason_info'] ) ? trim( stripslashes( $_REQUEST['reason_info'] ) ) : '',
			'plugin'        => $this->client->name, // deprecated
			'site'          => $this->__get_site_name(),
			'url'           => esc_url( home_url() ),
			'admin_email'   => get_option( 'admin_email' ),
			'user_email'    => $current_user->user_email,
			'user_name'     => $current_user->display_name, // deprecated
			'first_name'    => ( ! empty( $current_user->first_name ) ) ? $current_user->first_name : $current_user->display_name,
			'last_name'     => $current_user->last_name,
			'server'        => $this->__get_server_info(),
			'software'      => $_SERVER['SERVER_SOFTWARE'], // deprecated, using $data['server'] for wp info
			'php_version'   => phpversion(), // deprecated, using $data['server'] for wp info
			'mysql_version' => $wpdb->db_version(), // deprecated, using $data['server'] for wp info
			'wp'            => $this->__get_wp_info(),
			'wp_version'    => get_bloginfo( 'version' ), // deprecated, using $data['wp'] for wp info
			'locale'        => get_locale(), // deprecated, using $data['wp'] for wp info
			'multisite'     => is_multisite() ? 'Yes' : 'No', // deprecated, using $data['wp'] for wp info
			'ip_address'    => $this->__get_user_ip_address(),
			'version'       => $this->client->project_version,
		);
		// Add extra data
		if ( $extra = $this->get_extra_data() ) {
			$data['extra'] = $extra;
		}
		$this->client->send_request( $data, '' );
		wp_send_json_success();
	}
	
	/**
	 * Handle the plugin deactivation feedback
	 *
	 * @return void
	 */
	public function deactivate_scripts() {
		global $pagenow;
		if ( 'plugins.php' !== $pagenow ) return;
		$reasons = $this->__get_uninstall_reasons();
		?>
		<div class="wapk-dr-modal" id="<?php echo $this->client->slug; ?>-wapk-dr-modal">
			<div class="wapk-dr-modal-wrap">
				<div class="wapk-dr-modal-header">
					<h3><?php _e( 'If you have a moment, please let us know why you are deactivating:', 'domain' ); ?></h3>
				</div>
				<div class="wapk-dr-modal-body">
					<ul class="reasons">
					<?php foreach ($reasons as $reason) { ?>
						<li data-type="<?php echo esc_attr( $reason['type'] ); ?>" data-placeholder="<?php echo esc_attr( $reason['placeholder'] ); ?>">
							<label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"> <?php echo $reason['text']; ?></label>
						</li>
					<?php } ?>
					</ul>
				</div>
				<div class="wapk-dr-modal-footer">
					<a href="#" class="dont-bother-me"><?php _e( 'I rather wouldn\'t say', 'domain' ); ?></a>
					<button class="button-secondary"><?php _e( 'Submit & Deactivate', 'domain' ); ?></button>
					<button class="button-primary"><?php _e( 'Cancel', 'domain' ); ?></button>
				</div>
			</div>
		</div>
		<style type="text/css">
			.wapk-dr-modal { position: fixed; z-index: 99999; top: 0; right: 0; bottom: 0; left: 0; background: rgba(0,0,0,0.5); display: none; }
			.wapk-dr-modal.modal-active { display: block; }
			.wapk-dr-modal-wrap { width: 475px; position: relative; margin: 10% auto; background: #fff; }
			.wapk-dr-modal-header { border-bottom: 1px solid #eee; padding: 8px 20px; }
			.wapk-dr-modal-header h3 { line-height: 150%; margin: 0; }
			.wapk-dr-modal-body { padding: 5px 20px 20px 20px; }
			.wapk-dr-modal-body .reason-input { margin-top: 5px; margin-left: 20px; }
			.wapk-dr-modal-footer { border-top: 1px solid #eee; padding: 12px 20px; text-align: right; }
		</style>
		<script type="text/javascript">
            (function($) {
                $(function() {
                    /**
                     * Ajax Helper For Submitting Deactivation Reasons
                     * @param {Object} data
                     * @param {*|jQuery|HTMLElement} buttonElem
                     * @param {String} redirectTo
                     * @returns {*|jQuery}
                     * @private
                     */
                    function _ajax( data, buttonElem, redirectTo ) {
                        if ( buttonElem.hasClass('disabled') ) return;
                        return $.ajax( {
                            url: ajaxurl,
                            type: 'POST',
                            data: $.fn.extend( {}, data, { action: '<?php echo $this->client->slug; ?>_submit-uninstall-reason' } ),
                            beforeSend: function() {
                                buttonElem.addClass("disabled");
                                buttonElem.text('Processing...');
                            },
                            complete: function(x,y,z) {
                                window.location.href = redirectTo;
                            }
                        } );
                    }
                    var modal = $( '#<?php echo $this->client->slug; ?>-wapk-dr-modal' ), deactivateLink = '';
                    $( '#the-list' ).on('click', 'a.<?php echo $this->client->slug; ?>-deactivate-link', function(e) {
                        e.preventDefault();
                        modal.addClass('modal-active');
                        deactivateLink = $(this).attr('href');
                        modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'left');
                    });
                    modal.on('click', 'button.button-primary', function(e) {
                        e.preventDefault();
                        modal.removeClass('modal-active');
                    }).on('click', 'input[type="radio"]', function () {
                        modal.find('.reason-input').remove();
                        var parent = $(this).parents('li:first'),
	                        inputType = parent.data('type'),
                            inputPlaceholder = parent.data('placeholder'),
                            reasonInputHtml = '<div class="reason-input">' + ( ( 'text' === inputType ) ? '<input type="text" size="40" />' : '<textarea rows="5" cols="45"></textarea>' ) + '</div>';
                        if ( inputType !== '' ) {
                            parent.append( $(reasonInputHtml) );
                            parent.find('input, textarea').attr('placeholder', inputPlaceholder).focus();
                        }
                    }).on('click', '.dont-bother-me', function(e) {
                        e.preventDefault();
                        _ajax( { reason_id: 'no-comment', reason_info: 'I rather wouldn\'t say' }, $(this), deactivateLink );
                    }).on('click', 'button.button-secondary', function(e) {
                        e.preventDefault();
                        var $radio = $( 'input[type="radio"]:checked', modal ),
	                        $selected_reason = $radio.parents('li:first'),
                            $input = $selected_reason.find('textarea, input[type="text"]');
                        _ajax( {
                            reason_id: ( 0 === $radio.length ) ? 'none' : $radio.val(),
                            reason_info: ( 0 !== $input.length ) ? $input.val().trim() : ''
                        }, $(this), deactivateLink );
                    });
                });
            }(jQuery));
		</script>
		<?php
	}
	
	/**
	 * Run after theme deactivated
	 * @param  string $new_name
	 * @param  WP_Theme $new_theme
	 * @param  WP_Theme $old_theme
	 * @return void
	 */
	public function theme_deactivated( $new_name, $new_theme, $old_theme ) {
		// Make sure this is WebAppick theme
		if( $old_theme->get_template() == $this->client->slug ) {
			$current_user = wp_get_current_user();
			$data = array(
				'hash'        => $this->client->hash,
				'reason_id'   => 'none',
				'reason_info' => json_encode( [
					'new_theme' => [
						'name' => $new_name,
						'version' => $new_theme->version,
						'parent_theme' => $new_name->parent_theme,
						'author' => $new_name->parent_theme,
					]
				] ),
				'site'        => $this->__get_site_name(),
				'url'         => esc_url( home_url() ),
				'admin_email' => get_option( 'admin_email' ),
				'user_email'  => $current_user->user_email,
				'first_name'  => $current_user->first_name,
				'last_name'   => $current_user->last_name,
				'server'      => $this->__get_server_info(),
				'wp'          => $this->__get_wp_info(),
				'ip_address'  => $this->__get_user_ip_address(),
				'version'     => $this->client->project_version,
			);
			$this->client->send_request( $data, 'deactivate' );
		}
	}
	
	/**
	 * Get user IP Address
	 * @return string
	 */
	private function __get_user_ip_address() {
		$response = wp_remote_get( 'https://icanhazip.com/' );
		if( is_wp_error( $response ) ) return '';
		$ip = trim( wp_remote_retrieve_body( $response ) );
		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) return '';
		return $ip;
	}
	
	/**
	 * Get site name
	 * @return string
	 */
	private function __get_site_name() {
		$site_name = get_bloginfo( 'name' );
		if ( empty( $site_name ) ) {
			$site_name = get_bloginfo( 'description' );
			$site_name = wp_trim_words( $site_name, 3, '' );
		}
		if ( empty( $site_name ) ) $site_name = get_bloginfo( 'url' );
		return $site_name;
	}
}
// End of file Insights.php
