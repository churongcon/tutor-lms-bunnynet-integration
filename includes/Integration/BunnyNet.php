<?php
/**
 * Override Tutor default & integrate BunnyNet
 *
 * @package TutorLMSBunnyNetIntegration\Integration
 * @since v1.0.0
 */

namespace Tutor\BunnyNetIntegration\Integration;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add action & filter to override Tutor default
 * & incorporate BunnyNet
 *
 * @version  1.0.0
 * @package  TutorLMSBunnyNetIntegration\Integration
 * @category Integration
 * @author   Themeum <support@themeum.com>
 */
class BunnyNet {

	/**
	 * Register action & filter hooks
	 *
	 * @since v1.0.0
	 */
	public function __construct() {
		add_filter( 'tutor_preferred_video_sources', __CLASS__ . '::filter_preferred_sources' );
		add_filter( 'tutor_single_lesson_video', __CLASS__ . '::filter_lesson_video', 10, 3 );
		add_filter( 'tutor_course/single/video', __CLASS__ . '::filter_course_video' );
		add_action( 'tutor_after_video_meta_box_item', __CLASS__ . '::meta_box_item' );
		add_filter( 'should_tutor_load_template', __CLASS__ . '::filter_template_load', 99, 2 );
	}

	/**
	 * Filter tutor default video sources
	 *
	 * @since v1.0.0
	 *
	 * @param array $video_source default video sources.
	 *
	 * @return array
	 */
	public static function filter_preferred_sources( array $video_source ): array {
		$video_source['bunnynet'] = array(
			'title' => __( 'BunnyNet', 'tutor-lms-bunnynet-integration' ),
			'icon'  => 'code',
		);

		return $video_source;
	}

	/**
	 * Filter single lesson video on the course content
	 * (aka spotlight) section
	 *
	 * @since v1.0.0
	 *
	 * @param string $content  tutor's default lesson content.
	 *
	 * @return string
	 */
	public static function filter_lesson_video( $content ) {
		$bunny_video_id = self::is_bunnynet_video_source();
		if ( false !== $bunny_video_id ) {
			ob_start();
			?>
			<div class="tutor-video-player">
				<div style="position: relative; padding-top: 56.25%;">
					<iframe src="https://iframe.mediadelivery.net/embed/<?php echo esc_attr( $bunny_video_id ); ?>?autoplay=false" loading="lazy" style="border: none; position: absolute; top: 0; height: 100%; width: 100%;" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" allowfullscreen="true"></iframe>
				</div>
			</div>
			<?php
			$content = ob_get_clean();
		}
		return $content;
	}

	/**
	 * Filter course intro video if source if bunny net
	 *
	 * @param sting $content course intro video content.
	 *
	 * @return string
	 */
	public static function filter_course_video( $content ) {
		$bunny_video_id = self::is_bunnynet_video_source();

		if ( false !== $bunny_video_id ) {
			?>
			<div class="tutor-video-player">
				<div style="position: relative; padding-top: 56.25%;">
					<iframe src="https://iframe.mediadelivery.net/embed/<?php echo esc_html( $bunny_video_id ); ?>?autoplay=false" loading="lazy" style="border: none; position: absolute; top: 0; height: 100%; width: 100%;" allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;" allowfullscreen="true"></iframe>
				</div>
			</div>
			<?php
			$content = ob_get_clean();
		}
		return $content;
	}

	/**
	 * Add bunny net source field on the meta box
	 *
	 * @param object $post  post object.
	 *
	 * @return void
	 */
	public static function meta_box_item( $post ):void {
		$video          = maybe_unserialize( get_post_meta( $post->ID, '_video', true ) );
		$video_source   = tutor_utils()->avalue_dot( 'source', $video, 'bunnynet' );
		$bunnyet_source = tutor_utils()->avalue_dot( 'source_bunnynet', $video );
		?>
		<div class="tutor-mt-16 video-metabox-source-item video_source_wrap_bunnynet tutor-dashed-uploader" style="<?php tutor_video_input_state( $video_source, 'bunnynet' ); ?>">
			<input class="tutor-form-control" type="text" name="video[source_bunnynet]" value="<?php echo esc_attr( $bunnyet_source ); ?>" placeholder="<?php esc_html_e( 'Place your bunnynet video code here', 'tutor-lms-bunnynet-integration' ); ?>">
		</div>
		<?php
	}

	/**
	 * If video source is bunny net then let not
	 * load the template from tutor
	 *
	 * @param boolean $should_load should load template.
	 * @param string  $template  template name.
	 *
	 * @return bool
	 */
	public static function filter_template_load( bool $should_load, string $template ):bool {
		if ( false !== self::is_bunnynet_video_source() && 'single.video.bunnynet' === $template ) {
			$should_load = false;
		}
		return $should_load;
	}

	/**
	 * Check video source is bunnynet
	 *
	 * @return mixed  video source if exists otherwise false
	 */
	public static function is_bunnynet_video_source() {
		$video_info = tutor_utils()->get_video_info();
		$response   = false;
		if ( $video_info ) {
			$bunny_video_id = tutor_utils()->array_get( 'source_bunnynet', $video_info );
			$bunny_video_id = str_replace( 'https://video.bunnycdn.com/play/', ' ', $bunny_video_id );
			$video_source   = $video_info->source;
			if ( 'bunnynet' === $video_source && '' !== $bunny_video_id ) {
				$response = $bunny_video_id;
			}
		}
		return $response;
	}
}
