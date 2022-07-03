<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Robot2' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ':I8)dwu(&DE^uU`a3kL Fx$s=5oy9&Mu+Er?,1: +6|+3S-`o> gvURLdzgxZd&4' );
define( 'SECURE_AUTH_KEY',  '5JsEx)%ZN-6lq{Al=A=/cs(VItn#;*~3!3!.[Rq`X#b@14@>nVWswb/GO~p8hW3,' );
define( 'LOGGED_IN_KEY',    '[1/YQ|HCTLTWXyD%R&xlJ0RqmPC&grnD#IIld1U193]zuQI/0mE%j]e^ipnS$,/_' );
define( 'NONCE_KEY',        '#(NVCvx-J?VP#b7}WowVn&uq|&6fo?Kf=rI/h79J-f~B+$`qKos&Ae{fFx7 s2R@' );
define( 'AUTH_SALT',        'd[N==nY6?lBD4AbHUl%0D6D#QI)bI* w5jIxIXnF0[N+*Y[W7AJGjJ2BhzeZtf1s' );
define( 'SECURE_AUTH_SALT', '3FiBU1h#1PE>Z*$V_oeGWy7[*x)C>jiuNiT*UR&!d#+S`p^B?Wx9;6iHo1==CYMU' );
define( 'LOGGED_IN_SALT',   'TN=01/Ur/wpsP`9s6iTIv/A1,0<gwU=0H}XrA_OSwc+tXYtXVDA]8LLlCw)*i~HM' );
define( 'NONCE_SALT',       'm>[Dbn`QmG-4kUl/_KhX7W_7P1EfDDhLD-HFPdkzix:,JzG.Qj3]={C1h*zZrX&=' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'rb_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
