<?php
define('CONCATENATE_SCRIPTS', false);
define('WP_AUTO_UPDATE_CORE', false);// This setting was defined by WordPress Toolkit to prevent WordPress auto-updates. Do not change it to avoid conflicts with the WordPress Toolkit auto-updates feature.
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
define('DB_NAME', 'wp_irjui_villagerantoul');

/** MySQL database username */
define('DB_USER', 'wp_c5t4e');

/** MySQL database password */
define('DB_PASSWORD', '!28rem0VER');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

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
define('AUTH_KEY', '1V0439gkYo:P3+HCt8:tqGb6)O2*(v)HxW@~ntm~Ma*K9&dDx7c]H1W6s+j&0KpO');
define('SECURE_AUTH_KEY', '40lJ]BB6s:3a)%67~tD4:]8uT~]#l5v!2H~olC48WvHEVNP#R1o5D774uzy9_)d%');
define('LOGGED_IN_KEY', 'Ql32|m3~7ISD35@f+41|jG%~71NV@%~5AlP*!n9m3|azOg[q898HhsiT2TrNG0F6');
define('NONCE_KEY', '5Xa8]46R+EGt|#pmU2|j[(Tx;flZgt:5OkjQ%)19HKU6&#u7Wxgmk3rjZ%j8/3w(');
define('AUTH_SALT', '+v%v7;uVcQ4rG#;N2aS8)bUz9[9|b3B[F*]SL*-8Rg|0:-U8h]ybZ+4B0g(N%5Tn');
define('SECURE_AUTH_SALT', '~]U:!m4r6c:v/O63+K#4[KwS:Kv-F0Ns!!jvBL9fLvNT7tHbjPixvt4l&H&D/L[+');
define('LOGGED_IN_SALT', '/Lt*xZL-~r~9s@-y~5GqT5WDvb_s8*%~5Jyrq1M#772v;6Tz85JDR6&Zy(_4245R');
define('NONCE_SALT', '|OB5h(:Du!|j!YL[0W9H+&~&0Iu71morP!(l;#uiMvR%4b9|R3C/nNG1puz5;f54');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'PfIXi6344M_';

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

define( 'WP_ALLOW_MULTISITE', true );

define ('FS_METHOD', 'direct');
