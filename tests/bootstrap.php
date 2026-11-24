<?php
/** Load actual WordPress functions from the runner's dedicated test installation. */
require dirname( __DIR__ ) . '/vendor/autoload.php';
putenv( 'WP_PHPUNIT__TESTS_CONFIG=' . __DIR__ . '/wp-tests-config.php' );
$tests_dir = getenv( 'WP_PHPUNIT__DIR' );
if ( ! $tests_dir || ! is_file( $tests_dir . '/includes/bootstrap.php' ) ) {
    throw new RuntimeException( 'Install the Composer test dependencies before running tests.' );
}
require $tests_dir . '/includes/bootstrap.php';
