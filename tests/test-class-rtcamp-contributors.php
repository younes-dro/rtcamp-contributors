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
}
