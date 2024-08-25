<?php
/**
 * Class for handling the frontend display of contributors.
 *
 * This class hooks into the post content and appends a "Contributors" box at the end of the post.
 *
 * @package rtcamp-contributors
 * @author  Younes DRO <younesdro@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Frontend functionality for RTCamp Contributors Plugin.
 *
 * This class handles the display of contributors on the frontend, including
 * rendering the contributors box at the end of posts.
 *
 * @package rtcamp-contributors
 * @since 1.0.0
 * @version 1.0.0
 * @author Younes DRO <younesdro@gmail.com>
 */
class RTCamp_Contributors_Front {

	/**
	 * Constructor for the RTCamp_Contributors_Front class.
	 *
	 * Initializes the frontend hooks.
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'display_contributors_box' ) );
	}

	/**
	 * Displays the Contributors box at the end of the post content.
	 *
	 * @param string $content The original post content.
	 * @return string The modified post content with the Contributors box appended.
	 */
	public function display_contributors_box( $content ) {
		if ( is_singular( 'post' ) ) {
			global $post;

			$contributors = get_post_meta( $post->ID, '_rtcamp_contributors', true );
			if ( ! empty( $contributors ) && is_array( $contributors ) ) {

				$contributors_box  = '<div class="rtcamp-contributors-box">';
				$contributors_box .= '<h3>' . esc_html__( 'Contributors', 'rtcamp-contributors' ) . '</h3>';
				$contributors_box .= '<ul>';

				foreach ( $contributors as $contributor_id ) {
					$user_info   = get_userdata( $contributor_id );
					$author_link = get_author_posts_url( $contributor_id );
					$avatar      = get_avatar( $contributor_id, 32 );
					$name        = esc_html( $user_info->display_name );

					$contributors_box .= '<li>';
					$contributors_box .= '<a href="' . esc_url( $author_link ) . '">' . $avatar . ' ' . $name . '</a>';
					$contributors_box .= '</li>';
				}

				$contributors_box .= '</ul>';
				$contributors_box .= '</div>';

				$content .= $contributors_box;
			}
		}

		return $content;
	}
}
