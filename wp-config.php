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
define('DB_NAME', 'wordpress_new');

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
define('AUTH_KEY',         '<u(=-z:&D^+2R?Dlx>ts|j](6h~[^yB1hiNy:rDy6`[Lkeb|iN|XYE^s,UHvkBL=');
define('SECURE_AUTH_KEY',  '|$6KW:>#|DI|+es$75RuyTDBfM *)oWI,a4|[vB1P<A;_AkP!$4#(8+H[._|4c#4');
define('LOGGED_IN_KEY',    ':?AUCPat :![K-W~ve hb%J3Ykoz m^F <pX2Ta&j+09Qd=boHQ:M)eY5nz5W+#I');
define('NONCE_KEY',        '~/q4T,L9ncj-gcgHf!LdDqdL~s(GtXy+c5Swo`M&t.}9&S7)Y@JcO/i.fiTvvkgM');
define('AUTH_SALT',        ')7^JKY3C16F!G5e,P}BlJJY3f|Hk^LmB&|$9ux1F{Xs+ZjdkC^hCVG+fCYEo&V~g');
define('SECURE_AUTH_SALT', 'H_*_K~+|dWHwaLHrG$3d)g$wl0^_iI$dkptO^HQE/djj@ 48,8?}+DjvZwydz:!s');
define('LOGGED_IN_SALT',   '%+o?*T+GlH~1}`b6AZ%kD1?iOcTY]#?V)AV];B=9AtWBqDFi,a}Y`nR2HGA2PWeV');
define('NONCE_SALT',       'mSF9;`i*|E]fj-rSy=f0gi{?>FDckD-:0$JK-rNEJ/DOho6y{Tw9N:*-No~rp+O&');

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
