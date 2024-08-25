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

        $user1 = $this->factory->user->create(['role' => 'administrator']);
        $user2 = $this->factory->user->create(['role' => 'author']);
        $user3 = $this->factory->user->create(['role' => 'editor']); 

        update_post_meta($post_id, '_rtcamp_contributors', [$user1, $user2, $user3]);
        
        $contributors = get_post_meta($post_id, '_rtcamp_contributors', true);

        $this->assertContains($user1, $contributors, 'User 1 should be a contributor.');
        $this->assertContains($user2, $contributors, 'User 2 should be a contributor.');
        $this->assertContains($user3, $contributors, 'User 3 should be a contributor.');
    }
}
