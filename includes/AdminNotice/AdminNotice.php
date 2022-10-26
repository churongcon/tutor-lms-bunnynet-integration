<?php
/**
 * Show admin notice
 *
 * @package TutorLMSBunnyNetIntegration\Notice
 * @since v1.0.0
 */

namespace Tutor\BunnyNetIntegration\AdminNotice;

use TutorLMSBunnyNetIntegration;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show admin notice typically when Tutor LMS is not exits
 * or active
 *
 * @version  1.0.0
 * @package  TutorLMSBunnyNetIntegration\Notice
 * @category AdminNotice
 * @author   Themeum <support@themeum.com>
 */
class AdminNotice {

	/**
	 * Register hooks
	 *
	 * @since v1.0.0
	 */
	public function __construct() {
		add_action( 'admin_notices', __CLASS__ . '::show_admin_notice' );
	}

	/**
	 * Show notice to the admin area if
	 * Tutor is not active or not available
	 *
	 * @since v1.0.0
	 *
	 * @return void
	 */
	public static function show_admin_notice() {
        $plugin_data = TutorLMSBunnyNetIntegration::meta_data();
        require_once $plugin_data['views'] . '/notice/notice.php'; 
	}
}
