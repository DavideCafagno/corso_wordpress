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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'corso_wordpress' );

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
define( 'AUTH_KEY',         '@V1k)R>AgQbZ{!l(bE1_R[q8j]P]Nc,BLs8{%X&G8?<vXjU_@iE&1M9X^AZDGm-6' );
define( 'SECURE_AUTH_KEY',  '1C|#;*|~$!`&-vUKRwd-$zHJeU-9d^B>}X/(QdqhL=*w,Xy|Gp|mQLp8G- 09#.o' );
define( 'LOGGED_IN_KEY',    '3|4jl0~0$,| a>dUd8|.sCObsBGk9Cr^ {WlS^|0FDe0<!kVVyAA3]4uE6Bw#?Yb' );
define( 'NONCE_KEY',        ',;FJ/#?&I^-OEq=0$pn*@2[j88l_K,Q4)2O tOErTyo ,N!h}%WxGYx;iE/S*Ht*' );
define( 'AUTH_SALT',        't_!OSn[H,Z})AIxbZ[}[MIeYpHEXT_aTQI^^MwW/k]l&FL|)[tP`9bQ1.)D%{(e/' );
define( 'SECURE_AUTH_SALT', '}5bD#wK5W7.[:&+?^wqW@ jvQ`>mJ5=6JA2}HSx(T*c iLlr8e+d43/o;Eu/,gib' );
define( 'LOGGED_IN_SALT',   'y;HB06p}_7q`mCC3ao=3UCMVXp,DJ:n>[4Zb^2<kBMs65)|/@s&.r50BG|PD*ys8' );
define( 'NONCE_SALT',       'Z!+y:p.V_igaAH^U ge0b%S0Vn0QBFV m1m4ez)e}OH5]u7sC}R~*6+%AOpI $A]' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */


/* Add any custom values between this line and the "stop editing" line. */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

 

define( 'WP_AUTO_UPDATE_CORE', false );
define( 'AUTOMATIC_UPDATER_DISABLED', true );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
