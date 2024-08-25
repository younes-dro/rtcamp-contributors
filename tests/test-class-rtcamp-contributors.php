<?php
/**
 * Class Test_RTCamp_Contributors
 *
 * @package rtcamp-contributors
 */

/**
 * Test cases for the RTCamp_Contributors class.
 */
class Test_RTCamp_Contributors extends WP_UnitTestCase {

	/**
	 * Test assigning multiple contributors to a post.
	 */
	public function test_assigning_multiple_contributors() {

		$post_id = $this->factory->post->create();

		$user1 = $this->factory->user->create( array( 'role' => 'administrator' ) );
		$user2 = $this->factory->user->create( array( 'role' => 'author' ) );
		$user3 = $this->factory->user->create( array( 'role' => 'editor' ) );

		update_post_meta( $post_id, '_rtcamp_contributors', array( $user1, $user2, $user3 ) );

		$contributors = get_post_meta( $post_id, '_rtcamp_contributors', true );

		$this->assertContains( $user1, $contributors, 'User 1 should be a contributor.' );
		$this->assertContains( $user2, $contributors, 'User 2 should be a contributor.' );
		$this->assertContains( $user3, $contributors, 'User 3 should be a contributor.' );
	}

	/**
	 * Test assigning a single contributor to a post.
	 */
	public function test_assigning_single_contributor() {

		$post_id = $this->factory->post->create();

		$user = $this->factory->user->create( array( 'role' => 'author' ) );

		update_post_meta( $post_id, '_rtcamp_contributors', array( $user ) );

		$contributors = get_post_meta( $post_id, '_rtcamp_contributors', true );

		$this->assertCount( 1, $contributors, 'There should be exactly one contributor.' );
		$this->assertEquals( $user, $contributors[0], 'The contributor should match the assigned user.' );
	}

	/**
	 * Test saving a post without assigning any contributors.
	 */
	public function test_no_contributors_assigned() {
		$post_id = $this->factory->post->create();

		$contributors = get_post_meta( $post_id, '_rtcamp_contributors', true );

		$this->assertEmpty( $contributors, 'There should be no contributors assigned to the post.' );
	}

	/**
	 * Test displaying the Contributors box at the end of post content.
	 */
	public function test_displaying_contributors_box() {

		$post_id = $this->factory->post->create( array( 'post_content' => 'This is a test post.' ) );

		$user1 = $this->factory->user->create(
			array(
				'role'         => 'author',
				'display_name' => 'Author One',
			)
		);
		$user2 = $this->factory->user->create(
			array(
				'role'         => 'editor',
				'display_name' => 'Editor One',
			)
		);
		$user3 = $this->factory->user->create(
			array(
				'role'         => 'administrator',
				'display_name' => 'Admin One',
			)
		);

		update_post_meta( $post_id, '_rtcamp_contributors', array( $user1, $user2, $user3 ) );

		global $post, $wp_query;
		$post                  = get_post( $post_id );
		$wp_query              = new WP_Query(
			array(
				'p'              => $post_id,
				'post_type'      => 'post',
				'posts_per_page' => 1,
			)
		);
		$wp_query->is_singular = true;
		$wp_query->is_single   = true;
		$wp_query->is_page     = false;
		$wp_query->is_home     = false;
		$wp_query->is_archive  = false;

		setup_postdata( $post );

		$post_content = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );

		$this->assertStringContainsString( 'Contributors', $post_content, 'Contributors box should be present.' );
		$this->assertStringContainsString( 'Author One', $post_content, 'Author One should be listed as a contributor.' );
		$this->assertStringContainsString( 'Editor One', $post_content, 'Editor One should be listed as a contributor.' );
		$this->assertStringContainsString( 'Admin One', $post_content, 'Admin One should be listed as a contributor.' );

		wp_reset_postdata();
	}
}
