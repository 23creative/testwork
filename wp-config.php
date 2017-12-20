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
define('DB_NAME', 'arunkumar');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '&0F1)Pt(eaTWs.-cesEZcIke54-sTxu&Z_:RwW,B~QXLZ;$%#s&}&ofa}vMQA/{*');
define('SECURE_AUTH_KEY',  '2R3#V^j%Gd`hd.KS6kQ01J3 &qXDErvjDA=-E[+5hW_aki?AM+gRFBF1whKSP=tj');
define('LOGGED_IN_KEY',    '7I:$xqXl{v#*~fhyxw_ydb?eZT6mJ!DV+{.[s1w*n{, m$}cvy`b1Qm&w%L=)@/Y');
define('NONCE_KEY',        'l{V!,qR4*|8S}FvmR!3&Oqrw>nMy]AxWOO4P)x9n:auqR3]ClL;q;P,v8/NDjt0b');
define('AUTH_SALT',        'EnhCQxE{D1S&$*`8U(4Fz}SO6YAsO@Xr!vbh*ZBG#iI3psY8bNV}l~rx:U#1V-l~');
define('SECURE_AUTH_SALT', 'pez&{Q=9jYa~q^T=Z% u4<NcK0!w7(Q`]&Q>cc_Rmwed[!,QF:bF>;?L[fGKVW#y');
define('LOGGED_IN_SALT',   '0BkDxPw;&<,NVVGbm9<2^,z?=(YAAdkSPyeu|o};tJ;Rx ~le]vdSJ5SMmi$%T:+');
define('NONCE_SALT',       'h[]^~3YiQ=mvAG</%-?5b`QA,u/pMa)2WT>&s SaXPPOqq&]g&C5Dpl(Q)#iqNYT');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
