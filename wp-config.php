<?php
ob_start();
error_reporting(0);
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'barkarby_mise');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '8.E0r*3:cYM6/8D8|XY2&dVqb?B~2{m1w_oTu|FJj?>Bmx9ktqS,2!SW[S{/Oj26');
define('SECURE_AUTH_KEY',  'Y?Xy2!5:H|I>j(?S#!vq/&f BU01!:NxVM,=CnFpp&]wdrr*x|GzAf1s}Vg[Y3,8');
define('LOGGED_IN_KEY',    '_=}&PUGG`y&JNm;O;P%})nzDH,o&J +N21R.n >P=4t0MrY<u?XUfC=!5t2C;TJi');
define('NONCE_KEY',        'EV`.I Acs{|Xl?H@Xz:{PKQ4Pd~`_g.;3iaEt95TZNQP-|e(7fr{/Tt.%N0w8u03');
define('AUTH_SALT',        'yCG]Be+30ydgacGYVZT0-0WSgQGO|]5/8zg9zq}MI7utid3~1a0z_7L=xR;`dI-(');
define('SECURE_AUTH_SALT', '%<.%Qd1%^x@{-Yj(P~uYy80e}ii%$oyLm1R-^+D324zD0^eIa.&?9QGn;LEhw$/m');
define('LOGGED_IN_SALT',   'E3Vgl@JB$%bl#2Tmcjmb+{!GiN7,ZG11x_C#ojwIK^O?D@*P,.x1RI@w-?UA rXn');
define('NONCE_SALT',       'ytKPW@C*chfVBH{7Y%=asctPSN;~n*_[5xxs)GuF1{4+Fks(g%9st9fsH{F@=F}p');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
