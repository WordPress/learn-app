<?php
/** The test library recreates tables here; CI supplies a dedicated database. */
$core = getenv( 'WP_CORE_DIR' );
if ( ! $core || ! is_file( $core . '/wp-settings.php' ) ) {
    throw new RuntimeException( 'WP_CORE_DIR must point to the test WordPress installation.' );
}
define( 'ABSPATH', rtrim( $core, '/' ) . '/' );
define( 'DB_NAME', getenv( 'WP_TESTS_DB_NAME' ) ?: 'wordpress_tests' );
define( 'DB_USER', getenv( 'WP_TESTS_DB_USER' ) ?: 'root' );
define( 'DB_PASSWORD', getenv( 'WP_TESTS_DB_PASSWORD' ) ?: 'root' );
define( 'DB_HOST', getenv( 'WP_TESTS_DB_HOST' ) ?: '127.0.0.1' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );
$table_prefix = 'wptests_';
define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Learn App Tests' );
define( 'WP_PHP_BINARY', 'php' );
define( 'WP_DEBUG', true );
