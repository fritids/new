<?php
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
define('DB_NAME', 'edgeinco_wp_try2');

/** MySQL database username */
define('DB_USER', 'edgeinco_wpusr2');

/** MySQL database password */
define('DB_PASSWORD', 'theusr_pa55_2');

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
define('AUTH_KEY',         '78afOKLQG|*P-_ejIcp:a-Y LZl4-1P7]?,qc`d_*(?nM-FdDK$yND7g(N/yd](s');
define('SECURE_AUTH_KEY',  '|;me]wD%.- 0?O30|s}fbrd?*R!waiC7)-ROP[Tr+^B|i3oW1D/U74?vb JSoFjH');
define('LOGGED_IN_KEY',    '<xc68.,|k!vF#$uAB+xAKmyOyXSV]LJL9-fh#X1-F3|x!HgC3|Pc8|YplcuN{9;a');
define('NONCE_KEY',        'W)d|]Cw5ZivZ]FImz7#0Q9G,-,k4&^|CVfKyNlAaO}Q-`0_X+G*`[%g^@&+%u}{{');
define('AUTH_SALT',        'UIi|_vvu([-;t&-:{I79VFh)wR:[Ck#A-%w%uZ:Dj$^1]#rM-?5n+Uk4bI(DpdO.');
define('SECURE_AUTH_SALT', '8;|0xA|}ctS&d7SH?[PqH-`d~P|[o$zuKK2^79-DHt/uh|Sh|;Vg+:@v?3L7LI-9');
define('LOGGED_IN_SALT',   'of@c>na9o6?H:}HA}~+x2XadvW6pL_]])$sfYoZ]WS}*HedNM[%[vH:l@f<e?s|Y');
define('NONCE_SALT',       'O}l2[w4]#,brybfcPcdQQ`~lPVv(Jhi9j Nf$uDbew64i~4Rfr,kP7So^q8SJ<(3');
define( 'WP_ALLOW_MULTISITE', true );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', 'edgeincollegeprep.com' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

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
?>