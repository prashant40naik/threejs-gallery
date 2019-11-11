<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('WP_DB_NAME') );

/** MySQL database username */
define( 'DB_USER', getenv('WP_DB_USER') );

/** MySQL database password */
define( 'DB_PASSWORD', getenv('WP_DB_PASS') );

/** MySQL hostname */
define( 'DB_HOST', getenv('WP_DB_HOST') );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
// replace-salt

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

define( 'WP_DEBUG', false );
define( 'WP_HOME','https://' . getenv('HTTP_HOST') . '/' . getenv('WP_STAGE') );
define( 'WP_SITEURL','https://' . getenv('HTTP_HOST') . '/' . getenv('WP_STAGE') );
define( 'FORCE_SSL_ADMIN', true );
define( 'FORCE_SSL_CONTENT', true );
define( 'CONCATENATE_SCRIPTS', false );
define( 'WP_HTTP_BLOCK_EXTERNAL', true );
define( 'DISALLOW_FILE_MODS', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'AWS_ACCESS_KEY_ID', getenv('AWS_ACCESS_KEY_ID') );
define( 'AWS_SECRET_ACCESS_KEY', getenv('AWS_SECRET_ACCESS_KEY') );
define( 'AWS_SESSION_TOKEN', getenv('AWS_SESSION_TOKEN') );

/**
 * WP Offload S3 Lite settings, reference:
 * https://deliciousbrains.com/wp-offload-s3/doc/settings-constants/
 */
define( 'WPOS3_SETTINGS', serialize( array(
	// S3 bucket to upload files
	'bucket' => getenv('WP_S3_BUCKET'),
	// S3 bucket region (e.g. 'us-west-1' - leave blank for default region)
	'region' => '',
	// Automatically copy files to S3 on upload
	'copy-to-s3' => true,
	// Rewrite file URLs to S3
	'serve-from-s3' => true,
	// S3 URL format to use ('path', 'cloudfront')
	'domain' => 'cloudfront',
	// Custom domain if 'domain' set to 'cloudfront'
	'cloudfront' => getenv('WP_CF_DOMAIN'),
	// Enable object prefix, useful if you use your bucket for other files
	'enable-object-prefix' => true,
	// Object prefix to use if 'enable-object-prefix' is 'true'
	'object-prefix' => 'wp-content/uploads/',
	// Organize S3 files into YYYY/MM directories
	'use-yearmonth-folders' => false,
	// Serve files over HTTPS
	'force-https' => true,
	// Remove the local file version once offloaded to S3
	'remove-local-file' => true,
	// Append a timestamped folder to path of files offloaded to S3
	'object-versioning' => false,
) ) );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
