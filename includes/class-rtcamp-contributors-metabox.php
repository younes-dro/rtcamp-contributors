<?php
/**
 * Class for handling the Contributors Meta Box.
 *
 * This class is responsible for adding a meta box to the post editor screen
 * that allows users to select additional contributors for a post. It also
 * handles the saving of this data and ensures that it is securely stored
 * and retrieved from the post meta.
 *
 * @package rtcamp-contributors
 * @author  Younes DRO <younesdro@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Meta box functionality for RTCamp Contributors Plugin.
 *
 * This class handles the creation and management of the "Contributors" meta box
 * in the post editor screen, allowing users to select multiple contributors for a post.
 *
 * @package rtcamp-contributors
 * @since 1.0.0
 * @version 1.0.0
 * @author Younes DRO <younesdro@gmail.com>
 */
class RTCamp_Contributors_MetaBox {

	/**
	 * Constructor for the RTCamp_Contributors_MetaBox class.
	 *
	 * Initializes the meta box actions.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_contributors_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_contributors_meta_box' ) );
	}

	/**
	 * Adds the Contributors meta box to the post editor screen.
	 *
	 * @return void
	 */
	public function add_contributors_meta_box() {
		add_meta_box(
			'rtcamp_contributors_meta_box',
			__( 'Contributors', 'rtcamp-contributors' ),
			array( $this, 'render_contributors_meta_box' ),
			'post',
			'side',
			'default'
		);
	}

	/**
	 * Renders the Contributors meta box.
	 *
	 * @param WP_Post $post The current post object.
	 * @return void
	 */
	public function render_contributors_meta_box( $post ) {
		wp_nonce_field( 'save_contributors_meta_box', 'rtcamp_contributors_nonce' );
		$contributors = get_post_meta( $post->ID, '_rtcamp_contributors', true );
		echo '<div>';
		$users = get_users( array( 'role__in' => array( 'author', 'editor', 'administrator' ) ) );
		foreach ( $users as $user ) {
			$contributors = array_map( 'intval', (array) $contributors );
			$checked      = in_array( intval( $user->ID ), $contributors, true ) ? ' checked="checked"' : '';

			echo '<p>';
			echo '<label>';
			echo '<input type="checkbox" name="rtcamp_contributors[]" value="' . esc_attr( $user->ID ) . '" ' . esc_attr( $checked ) . '>';
			echo esc_html( $user->display_name );
			echo '</label>';
			echo '</p>';
		}
		echo '</div>';
	}

	/**
	 * Saves the Contributors meta box data when the post is saved.
	 *
	 * @param int $post_id The ID of the current post.
	 * @return void
	 */
	public function save_contributors_meta_box( $post_id ) {
		if ( ! isset( $_POST['rtcamp_contributors_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rtcamp_contributors_nonce'] ) ), 'save_contributors_meta_box' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['rtcamp_contributors'] ) ) {
			$contributors = wp_unslash( $_POST['rtcamp_contributors'] ); // phpcs:ignore -- Data is sanitized in the next line the next line
			$contributors = array_map( 'sanitize_text_field', (array) $contributors );
			update_post_meta( $post_id, '_rtcamp_contributors', $contributors );
		} else {
			delete_post_meta( $post_id, '_rtcamp_contributors' );
		}
	}
}
