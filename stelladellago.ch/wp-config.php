<?php
/**
 * In dieser Datei werden die Grundeinstellungen für WordPress vorgenommen.
 *
 * Zu diesen Einstellungen gehören: MySQL-Zugangsdaten, Tabellenpräfix,
 * Secret-Keys, Sprache und ABSPATH. Mehr Informationen zur wp-config.php gibt es auf der {@link http://codex.wordpress.org/Editing_wp-config.php
 * wp-config.php editieren} Seite im Codex. Die Informationen für die MySQL-Datenbank bekommst du von deinem Webhoster.
 *
 * Diese Datei wird von der wp-config.php-Erzeugungsroutine verwendet. Sie wird ausgeführt, wenn noch keine wp-config.php (aber eine wp-config-sample.php) vorhanden ist,
 * und die Installationsroutine (/wp-admin/install.php) aufgerufen wird.
 * Man kann aber auch direkt in dieser Datei alle Eingaben vornehmen und sie von wp-config-sample.php in wp-config.php umbenennen und die Installation starten.
 *
 * @package WordPress
 */

/**  MySQL Einstellungen - diese Angaben bekommst du von deinem Webhoster. */
/**  Ersetze database_name_here mit dem Namen der Datenbank, die du verwenden möchtest. */
define('DB_NAME', 'ironsmit_stelladellago');

/** Ersetze username_here mit deinem MySQL-Datenbank-Benutzernamen */
define('DB_USER', 'ironsmit_stella');

/** Ersetze password_here mit deinem MySQL-Passwort */
define('DB_PASSWORD', 'stella14');

/** Ersetze localhost mit der MySQL-Serveradresse */
define('DB_HOST', 'localhost');

/** Der Datenbankzeichensatz der beim Erstellen der Datenbanktabellen verwendet werden soll */
define('DB_CHARSET', 'utf8');

/** Der collate type sollte nicht geändert werden */
define('DB_COLLATE', '');

/* alte Konstanten vom Seitenaufbau
define('WP_HOME','http://ironsmith.ch/stella');
define('WP_SITEURL','http://ironsmith.ch/stella');
*/
/*
// Temporaere Konstanten
define('WP_HOME','http://ironsmith.ch/stelladellago.ch/www/http/');
define('WP_SITEURL','http://ironsmith.ch/stelladellago.ch/www/http/');
*/

// neue Konstanten
define('WP_HOME','http://stelladellago.ch/');
define('WP_SITEURL','http://stelladellago.ch/');


/**#@+
 * Sicherheitsschlüssel
 *
 * Ändere jeden KEY in eine beliebige, möglichst einzigartige Phrase. 
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service} kannst du dir alle KEYS generieren lassen.
 * Bitte trage für jeden KEY eine eigene Phrase ein. Du kannst die Schlüssel jederzeit wieder ändern, alle angemeldeten Benutzer müssen sich danach erneut anmelden.
 *
 * @seit 2.6.0
 */
define('AUTH_KEY',         'DpZjk8e#[;H8l7nOzdJ?C.-e}k=K:~q: yP_l`2_U$-;77J<8W96qjydWI)r9h,f');
define('SECURE_AUTH_KEY',  'aO]E_u&v%Sv+oTa8b9> Rx/I:w2U:FX?ZYl|Qo}nJ>>NS`Xv+IGB Wo$RCUaR}eO');
define('LOGGED_IN_KEY',    '-&lL;Ft2_;M^m>-ZK}:=DFqX1![@+8;Q0rYP0{B7mO8kd/4o8A`sQF+fMY@/UL8?');
define('NONCE_KEY',        'b,686DOMHv{)X<tk@i0]%-f%2L?#UNFIZFFa57/9(R(PB9|Y4v|S?|7a|vpEw+W)');
define('AUTH_SALT',        '0Z=d}_q<CFBhO0>%^e/i6z17/Z[WaY]o>0#$olMj|vY{Q%z.&gQ7~ukr}SGv+G@[');
define('SECURE_AUTH_SALT', 'th!2T6$fGsqT-@E/~mIz#|vRwQc7BWFrs-:.R^{&;x3q#cPjIpN1-b~TZEo(TRga');
define('LOGGED_IN_SALT',   'm<~puGfdq,+MYv&40sF`tKW4ldGpF86i8_Gk-?UNR`G%-}N!8||^XUB^N;d(y^1.');
define('NONCE_SALT',       '*e>,vlsFZy%8/4|kyKS&3|THlNqGZ*3;oUWEt-)#[=m3wvyJFt6z+i}r2R;-_0r+');

/**#@-*/

/**
 * WordPress Datenbanktabellen-Präfix
 *
 *  Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 *  verschiedene WordPress-Installationen betreiben. Nur Zahlen, Buchstaben und Unterstriche bitte!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Sprachdatei
 *
 * Hier kannst du einstellen, welche Sprachdatei benutzt werden soll. Die entsprechende
 * Sprachdatei muss im Ordner wp-content/languages vorhanden sein, beispielsweise de_DE.mo
 * Wenn du nichts einträgst, wird Englisch genommen.
 */
define('WPLANG', 'de_DE');

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
