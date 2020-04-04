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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ourartsc_3bb' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         't45}JSj)=4zyb*UGs5wh9|PHu^bIqTMB(iK=A)S?o=D@w~fCb#%Wl>wIzfRX:u=Z' );
define( 'SECURE_AUTH_KEY',  '31+n>95[eLXS8{eO~rR9h|d!DZ`H&WD/1gw%Sn@Vy^K4FyJ0W#j+3OA[a=L0lqkz' );
define( 'LOGGED_IN_KEY',    '{{WVsMaf`,*Ep9VEF%C>TYX@O]+%#S. )81B)[PSJ~<J5/mGlQlG_ee8*gy^~nRx' );
define( 'NONCE_KEY',        ':qm6!JJ6c@|<{]mR9s].`AQ2L>1rz/j(clN{nU6}=_HfX`qD9`^oIyMhD6{xCiwa' );
define( 'AUTH_SALT',        '0]@FwruIsN;GF12_0;vuQzgBu%E%nmB9@P:bg&v(`<IW.gB=J(~?BB[J6jl~s3oU' );
define( 'SECURE_AUTH_SALT', '5(+~#3>XBqA{H7Bwt2r[^1=?*^[_!= |MUp4PpstcZ;gj,Ic%+zsn O5Q07nVbg,' );
define( 'LOGGED_IN_SALT',   '%t!|.hox,B--FD_j_X%1.P%d$>^nfS3pP%7P+hX?roCqJM~)9hm%eMpn_fyMtA>p' );
define( 'NONCE_SALT',       'pse33R6b),U+c^gE-W^yNHU/{L0wJzXGGitdt@^VQ$ K@=Le~u~Xb A/f2)CKme(' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '3bb_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
