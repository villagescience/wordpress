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

define('WP_HOME','http://'.$_SERVER['SERVER_NAME']);
define('WP_SITEURL','http://'.$_SERVER['SERVER_NAME']);
define( 'WP_DEFAULT_THEME', 'canvas' );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'vspi_local');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'raspberry');

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
define('AUTH_KEY',         ':aD+08aTxL?N=>D|>;@>w(wkt->cS WC`(&bTqGse1j%?QXM?+)StD`,+,MQ7?#.');
define('SECURE_AUTH_KEY',  'kNiqf/3}||?h~s[fbzms|>;+,w4bf+_AS4PfNQ>?8-Fi|<6QkS 2>*DT8}dai- )');
define('LOGGED_IN_KEY',    '%$QY#}+id|Z.fuZx[}0P[r@IP-#}o%~`Q<&c=izAE%@$1DG8gAO4BA007n0$u9YT');
define('NONCE_KEY',        '&=c5=jkrwPg+;t;IDjbLv{[=toPD:sq/NIbu%*c{O&7z@uaa3x`N1{H5KUfS3RDY');
define('AUTH_SALT',        '&PRa=ys@YDhxo9;fu4oMj,m.IaQH]zN?9BV+_%m}pJ/)(D4k0ZB)~QK)->k3YAb(');
define('SECURE_AUTH_SALT', 'MP>K1sJ=3Y[rl$q|wRZ^c)g!Ad_cbp^aC[hCTW,cs3-[vO1y(|Hex^r0]h#8HQLX');
define('LOGGED_IN_SALT',   'Vi{@9|U_iC#/jn~+HS?%+L.;C&c/G8v@WhFvg@0>*%J }:]QqDe5y}/p9a,%x,|d');
define('NONCE_SALT',       'Ex/+wqFu/Ei$P-x{Ol+2=SB}8y/c?[y2q_/IbQO}-P|J28y${Zv3i[3({78SN:hh');

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
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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