<?php
/**
 * Show Admin notice
 *
 * @since v1.0.0
 * @package TutorLMSBunnyNetIntegration\Views
 */

use Tutor\BunnyNetIntegration\AdminNotice\AdminNotice;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$tutor_basename = 'tutor/tutor.php';
$source_file    = WP_PLUGIN_DIR . '/' . $tutor_basename;

$action_btn   = '';
$button_txt   = '';
$button_class = '';

$plugin_data = TutorLMSBunnyNetIntegration::meta_data();

if ( file_exists( $source_file ) && ! is_plugin_active( $tutor_basename ) ) {
	$action_btn = 'activate_tutor_free';
	$button_txt = __( 'Activate Tutor LMS', 'tutor-lms-bunnynet-integration' );
} elseif ( ! file_exists( $source_file ) ) {
	$action_btn   = 'install_tutor_plugin';
	$button_txt   = __( 'Install Tutor LMS', 'tutor-lms-bunnynet-integration' );
	$button_class = 'install-tbi-dependency-plugin-button';
}
if ( $action_btn || ! AdminNotice::is_tutor_core_has_req_verion() ) :
	?>
<div class="notice notice-error tbi-install-notice">
	<div class="tbi-install-notice-inner" style="display:flex; gap: 20px; padding: 10px 0;">
		<div class="tbi-install-notice-icon">
			<img src="<?php echo esc_url( $plugin_data['assets'] . 'images/tutor-logo.jpg' ); ?>" alt="Tutor LMS BunnyNet Integration">
		</div>
		<div class="tbi-notice-content-area" style="display: flex; flex-direction: column; gap: 10px;">
			<div class="tbi-install-notice-content">
				<h2>
					<?php esc_html_e( 'Thanks for using Tutor LMS BunnyNet Integration', 'tutor-lms-bunnynet-integration' ); ?>
				</h2>
				<p>
					<?php esc_html_e( 'To use Tutor LMS BunnyNet Integration, you must have installed & activated Tutor LMS Version ', 'tutor-lms-bunnynet-integration' ); ?>
					<?php echo esc_html( "({$plugin_data['tutor_req_ver']})" );?>
				</p>
				<a href="https://wordpress.org/plugins/tutor/" style="margin-right: 20px;">
					<?php echo esc_html_e( 'Free installed and activated', 'tutor-lms-bunnynet-integration' ); ?>
				</a>
				<a href="https://www.themeum.com/product/tutor-lms/" target="_blank">
					<?php esc_html_e( 'Learn more about Tutor LMS', 'tutor-lms-bunnynet-integration' ); ?>
				</a>
			</div>
		</div>
	</div>
   
</div>

<?php endif; ?>
