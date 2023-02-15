<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

require 'vendor/autoload.php';

function fromenv( $key, $default = null ) {
    $value = getenv( $key );
    if ( $value === false ) {
        $value = $default;
    }
    
    return $value;
}

$url = parse_url( fromenv( 'DATABASE_URL', 'mysql://root:@localhost:3306/nest360_wp' ) );

$host     = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = substr( $url["path"], 1 );

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', fromenv( 'WORDPRESS_DB_NAME', $database ) );

/** Database username */
define( 'DB_USER', fromenv( 'WORDPRESS_DB_USER', $username ) );

/** Database password */
define( 'DB_PASSWORD', fromenv( 'WORDPRESS_DB_PASSWORD', $password ) );

/** Database hostname */
define( 'DB_HOST', fromenv( 'WORDPRESS_DB_HOST', $host ) );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', fromenv( 'WORDPRESS_DB_CHARSET', 'utf8mb4' ) );


/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', fromenv( 'WORDPRESS_AUTH_KEY', ',&uY|+KfK.Vm?oHDnU$]cxFDda~f(3yfUEx}>s,ML%?T_Gb*q!J{n R*gG,`NT[}' ) );
define( 'SECURE_AUTH_KEY', fromenv( 'WORDPRESS_SECURE_AUTH_KEY', 'x?pRmkKEN2w`.CiqHnrFt5uXr{ul-?vSM3+6B}9(A|8b+~?|&M|+Ub+,F=-Ki,(R' ) );
define( 'LOGGED_IN_KEY', fromenv( 'WORDPRESS_LOGGED_IN_KEY', 'oe<ge3{#b3^D|<x3}!+hfz3}Ir~-K%=d8=z$fw=YRq;tN]?jZhz-}x}rxi(|y&4x' ) );
define( 'NONCE_KEY', fromenv( 'WORDPRESS_NONCE_KEY', 'WG:Zm/I$X*#_!;=z+t)+4/#q2_9]E:Idbn{Mt0`iw0.w1_IEbP0n:({@,TH07Jky' ) );
define( 'AUTH_SALT', fromenv( 'WORDPRESS_AUTH_SALT', 'V/YuN!-m,:`)+Bg njZ*l2x}/&$sCW-HSlY#9lx51#t,9@?-+h<ONda6ShL[;CD|' ) );
define( 'SECURE_AUTH_SALT', fromenv( 'WORDPRESS_SECURE_AUTH_SALT', 'Lc@LFW.F`qQ/Q?|Mx%thhDr7#EzT44pmM*nBg99MI}(2)P}uc y||n?(R+.1(RdR' ) );
define( 'LOGGED_IN_SALT', fromenv( 'WORDPRESS_LOGGED_IN_SALT', 'rS+GU(of3V,-uBK+gSrDDqVo6)8H*|kqihY(P-E[KA;5f(nLJa>6FjYxONs6mFT3' ) );
define( 'NONCE_SALT', fromenv( 'WORDPRESS_NONCE_SALT', 'wJ@cHo]^a]S3&:yF}-l7+hTf1N^|}~:ay8iASj.]K*vjnfzl8uG<+||ZDhu{V-D/' ) );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = fromenv( 'WORDPRESS_TABLE_PREFIX', 'wp_' );

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', (bool) fromenv( 'WORDPRESS_DEBUG', false ) );

/* Add any custom values between this line and the "stop editing" line. */
if (
    isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] )
    && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
) {
    $_SERVER['HTTPS'] = 'on';
}


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
