<?php
# Database Configuration
define( 'DB_NAME', 'wp_gslptest' );
define( 'DB_USER', 'gslptest' );
define( 'DB_PASSWORD', 'I7cZfXPbNcDi7wXirXTf' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '&{ne8hQ%]i[<c*]ryE|d?vea5f+ +-ugJX#y~AAB25-=wEz !.TWgQ88nk~kiXVl');
define('SECURE_AUTH_KEY',  '-$G9+u0,Om;h|d.E28Rp8C=N#Gme23|VmG{ j;YVUV{%0peNcPE0!q +ITI(J<[a');
define('LOGGED_IN_KEY',    '|8+-~J`^V$eH_ `uL:hhg[R;,=e|j2y]YaC:.plq*r=iRU9AhCo1B#rFGV+Uf Dn');
define('NONCE_KEY',        'JZAS.Q:ah|,=/+tE1#?N{/QL/7oJ|@h>$TYty0yyy}B&XOz+u{/q*zz=0M.3|b%H');
define('AUTH_SALT',        'Z~8s-`w1i74gp$lCKeuqv+33 ->dM`jC.TrR~GA1K0kWBuy%w;(^DL]&.p0^s#!P');
define('SECURE_AUTH_SALT', 'X.(p#3IP/Gxd&V]w@TE579Kx.0hXL1T.rI!(e(U#/]]w@9n+;,t W0hcz#@E&#m+');
define('LOGGED_IN_SALT',   'TrU4t+apbl!,] RDb+KT4-YLpwF;gob_bF:Pw_}PvyZB]1V+qa|9-f]|.A/h:}Yq');
define('NONCE_SALT',       '-_04Z3*pZAj[Ii6[i-_i}QsB0w%K]fZN`Q=%|#@;s$;;5ACDnp$&tfyA0zb42,{)');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'gslptest' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

umask(0002);

define( 'WPE_APIKEY', 'd881864471b783173f882f2260b84c843f830c4e' );

define( 'WPE_CLUSTER_ID', '101411' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'gslptest.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-101411', );

$wpe_special_ips=array ( 0 => '35.197.89.54', );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings




$base = '/';


# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');
require_once(ABSPATH . 'wp-settings.php');
