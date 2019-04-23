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

/**
 * Database connection information automatically provided by Pressable.
 * There is no need to set or change the following database configuration
 * values:
 *   DB_HOST
 *   DB_NAME
 *   DB_USER
 *   DB_PASSWORD
 *   DB_CHARSET
 *   DB_COLLATE
 */

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
/**#@+*/
define('AUTH_KEY',         ';8ZkT;3P@j@3Dcw~5_8W6[,R@:=<3Y7<Kc5C*%L@chqyanHDtrn>Rquc)3*.L0-i');
define('SECURE_AUTH_KEY',  '$c2dRSa,|i@p[h&+o^bhIa@7R&&A;L_9bj42}7a~cX7w=e{i|JdFF$;p~=T8)0oL');
define('LOGGED_IN_KEY',    ',giIxz?xIv.HU*_vw;mcf4CUO{G+O%z9mHan_T@6UBU4l1+G2%5^TlSHHB(.2_$Z');
define('NONCE_KEY',        'BwLkQ&c_!s!q-61rg7NHlyAdQ?7~0YSnt%(!BkG[neCK.n^>0Ly2u[ISUB)rIgOM');
define('AUTH_SALT',        'X%U|(B{j+B<g$y_2Jd*?ID9Cg8C}(lOetQB)<!txYm7A=~zXugR&25;YjL)TgQ>!');
define('SECURE_AUTH_SALT', '&$3Sh1j7gRD|%sK9DfE?B)2>vHZB1&<V2|=I{MO*-Ut$4$xRIJibO~lODk=&1FuM');
define('LOGGED_IN_SALT',   '496~Bf{_d4)7?q|g{U$(-hb:S_4B$#2L{)99*cx&&2PFm5BpdRwP^*(XXp^n~o#C');
define('NONCE_SALT',       'jE#kkoD}GK0lPPiGro}*G}#X})LkKuOaXl}]>|g^05zO<yKr9@q=v:r]fE%ao7XY');
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
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
