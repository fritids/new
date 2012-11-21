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
//define('WP_MEMORY_LIMIT', '128M');

define('DB_NAME', 'jess'); 

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'wp248mysql!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', ''); 

ini_set('upload_max_filesize', '50M');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ')x[-cP?7O IbWrjpzJZ+N9.N!+s|c.N]{2hi~zcWe;J{h0mziirf:h!n.W@|]]>`');
define('SECURE_AUTH_KEY',  'DUua^@} z;-`XgDs +v[ $Bih}kP(`p[an}SpuDp6$e2.M$Z EeR]s({Pui/I>fg');
define('LOGGED_IN_KEY',    'byyTFOY0M0|:*#7wGSpp>;k-@#< yNeVcX7.05m6b/^H2*<M1IQkTAXs)+y%ES2#');
define('NONCE_KEY',        '9)(p9=w6d4H4TIp$2vd^Q;MCx(28Xi-zg$!|(jge{H7~=p{b,?9?lm),p:mUQsV)');
define('AUTH_SALT',        'j ~+Z@d!T5FVXb$v?C[[*t+ae{gS;s!<|OXdE+xagDlG}f2<mL>SI[UY5Q9INb-0');
define('SECURE_AUTH_SALT', 'Bimw;`f3+kUftd+-~ iC{g&$x hDx&`!>(rXl^!wPfKOS6]mj&?@>m4HzbXj{X?q');
define('LOGGED_IN_SALT',   'ZhgLYJp|b6jfwJZ+u$j-MaILv-Sa$1:Dq 5Wq+9vh[Bfe{`uH-2e~`G.;/CUa{3*');
define('NONCE_SALT',       'kjE#+4zz9l/Wv!yof7+Nk,-!6Bb~PVA6G 9h)u@mFVju8c,+H}r]_o9M>U&m(vM(');

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
	
define('WP_CACHE', true);
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
