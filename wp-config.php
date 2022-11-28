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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', '46a338b0385c06356b659d5c262ccd847f3d890fc591088c');

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
define('AUTH_KEY',         'F.C3fzQ]QK@Z-+x|R/w.s<PULA==L>:{p7I#wfRkl8cZeEW?a1e.YK4liJ12TVA$');
define('SECURE_AUTH_KEY',  '_qV:SoxaSjDQ~Y&Kl.r*@iF#)-(!pAlShRaowXI%mM!9NQfRxDxUi-;C&}y{F8D~');
define('LOGGED_IN_KEY',    'D!?,tuQ,/Nwn[csbOp*EBf8([Z)*?Z`~/_I_~(J[<n^Idyfx!AQ]p*QovG,#w%$=');
define('NONCE_KEY',        '>k.1I+GT*Q7FA#&91U?AY@|q&1i/ny8#KkCVt]fkFMCxdP[aRXIgXs@T{mp0*izq');
define('AUTH_SALT',        'y${AsJHW .%vk.k?]P06rjYCu%h!9CvT]&+uUDh|weY}C[3:R%[aIWf-sI3[R|;4');
define('SECURE_AUTH_SALT', 'HDvvVDp<m^,%+{;9](kr0<:`<cIEiRvoAkegLRVC%V;>>|0EIn|^XH;Vr;QU@bhh');
define('LOGGED_IN_SALT',   '9ER S~S||~_->`c6TD*taJ1xcp2V[tfPbC%Xn8nr3kO+TD`4~(*/K3xXXTH+bh=2');
define('NONCE_SALT',       'SFDG;vX3]T%+5RKV2g_Muf1D@uvQw1^R&SEJ7 cN3!62WZ~O9b!R&k*$V)$DxT/`');

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
