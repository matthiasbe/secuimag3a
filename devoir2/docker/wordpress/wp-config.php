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
define('DB_NAME', 'wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Admin2015');

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
define('AUTH_KEY',         'wOcc+|5q9?cTX/qjzH[rhq/_gj/E_fy.z<(jP9<s;ozyx*B~]PXrqXoh&]Cl`R[a');
define('SECURE_AUTH_KEY',  'bMLI2Y3Im,M+c}4fH*/o;>?$D-i%Pw2{%W8xa;{N 0vPyu5U+*?t@[?LO/ZYPJ(n');
define('LOGGED_IN_KEY',    'z ~hLX7ldek=aB#:({8OT^W:}MGo#Lfy>qL8q, n!Uk;-c/r@$ZZ(jEhb]g$;&-B');
define('NONCE_KEY',        '}Ulj]w(cfTfcy {W 3l4Q{P<hyP^Y~jeR#j/k_J8AD&hmCWvo 0T4VQ2n>ENBoa~');
define('AUTH_SALT',        '$DlaRFYa@u(p(#q=>zC9sdsK(z>M;8e6V(o~}7fDGC|Ot7apDB-o0^J`8x_ Tkxz');
define('SECURE_AUTH_SALT', 'qMre$yVWh=JGfTPM80YWxaRqk)V!4L<%W+bw1?(e/J!/{UA3~Vf[t6Q`OE7$B?u*');
define('LOGGED_IN_SALT',   '`U[xXs;!3cKxe,r?K^fM+.(Pkr.)7eP:%2fr@RAd^+*>&7)mF(bZLJq<]N,GKbeZ');
define('NONCE_SALT',       'r~<h/ODhau>T{9(fEx<Z.k0294W0.Dq8w0yHp%Oh^rt6U-oy6~%K?08P%[]11M^H');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


define('WP_HOM', "http://0.0.0.0:8080");

