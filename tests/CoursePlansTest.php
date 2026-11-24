<?php

use WordpressCourses\CoursePlans;

class CoursePlansTest extends WP_UnitTestCase {
    private int $owner;
    private int $other;
    private int $plan;

    public function set_up(): void {
        parent::set_up();
        CoursePlans::register_post_type();
        $this->owner = self::factory()->user->create();
        $this->other = self::factory()->user->create();
        $this->plan = self::factory()->post->create( [
            'post_type' => CoursePlans::PLAN_POST_TYPE,
            'post_status' => 'publish',
            'post_author' => $this->owner,
            'post_content' => '<p>Original plan notes</p>',
        ] );
        update_post_meta( $this->plan, '_wordpress_courses_completed_lesson_ids', [ 11, 22 ] );
    }

    public function test_owner_progress_replaces_previous_ids_and_persists_each_lesson_once(): void {
        CoursePlans::set_completed_lesson_ids( $this->owner, $this->plan, [ 22, '22', 33, 0 ] );
        $this->assertSame( [ 22, 33 ], get_post_meta( $this->plan, '_wordpress_courses_completed_lesson_ids', true ) );
        $this->assertSame( [ 22, 33 ], CoursePlans::get_completed_lesson_ids( $this->owner, $this->plan ) );
    }

    public function test_another_user_cannot_read_or_replace_the_owners_progress(): void {
        $this->assertSame( [], CoursePlans::get_completed_lesson_ids( $this->other, $this->plan ) );
        CoursePlans::set_completed_lesson_ids( $this->other, $this->plan, [ 77 ] );
        $this->assertSame( [ 11, 22 ], get_post_meta( $this->plan, '_wordpress_courses_completed_lesson_ids', true ) );
    }

    public function test_only_owner_can_restore_a_trashed_plan_with_progress_and_notes_intact(): void {
        CoursePlans::trash_plan( $this->owner, $this->plan );
        $this->assertSame( 'trash', get_post_status( $this->plan ) );
        $this->assertFalse( CoursePlans::restore_plan( $this->other, $this->plan ) );
        $this->assertSame( 'trash', get_post_status( $this->plan ) );
        $this->assertTrue( CoursePlans::restore_plan( $this->owner, $this->plan ) );
        $this->assertSame( 'publish', get_post_status( $this->plan ) );
        $this->assertSame( [ 11, 22 ], CoursePlans::get_completed_lesson_ids( $this->owner, $this->plan ) );
        $this->assertSame( '<p>Original plan notes</p>', CoursePlans::get_plan_notes( $this->owner, $this->plan ) );
    }
}
