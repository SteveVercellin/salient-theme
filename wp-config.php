<?php
define('WP_CACHE', true); // WP-Optimize Cache
//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
/**
 * Il file base di configurazione di WordPress.
 *
 * Questo file viene utilizzato, durante l’installazione, dallo script
 * di creazione di wp-config.php. Non è necessario utilizzarlo solo via web
 * puoi copiare questo file in «wp-config.php» e riempire i valori corretti.
 *
 * Questo file definisce le seguenti configurazioni:
 *
 * * Impostazioni MySQL
 * * Chiavi Segrete
 * * Prefisso Tabella
 * * ABSPATH
 *
 * * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Impostazioni MySQL - È possibile ottenere queste informazioni dal proprio fornitore di hosting ** //
/** Il nome del database di WordPress */
define( 'DB_NAME', 'ng3rcyas_wp616' );
/** Nome utente del database MySQL */
define( 'DB_USER', 'ng3rcyas_wp616' );
/** Password del database MySQL */
define( 'DB_PASSWORD', 'pSjF9)!5r1' );
/** Hostname MySQL  */
define('DB_HOST', 'localhost');
/** Charset del Database da utilizzare nella creazione delle tabelle. */
define( 'DB_CHARSET', 'utf8mb4' );
/** Il tipo di Collazione del Database. Da non modificare se non si ha idea di cosa sia. */
define('DB_COLLATE', '');
/**#@+
 * Chiavi Univoche di Autenticazione e di Salatura.
 *
 * Modificarle con frasi univoche differenti!
 * È possibile generare tali chiavi utilizzando {@link https://api.wordpress.org/secret-key/1.1/salt/ servizio di chiavi-segrete di WordPress.org}
 * È possibile cambiare queste chiavi in qualsiasi momento, per invalidare tuttii cookie esistenti. Ciò forzerà tutti gli utenti ad effettuare nuovamente il login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'cSH5U|Py~)2kW2_zl2DEr=d1^5-l+9Tz9EUZwRoWVKGbuTL49PEF>RJ%Bp~IMvg~');
define('SECURE_AUTH_KEY',  '*tq6Dzi+B?.3{LmvCgT7*MCmc<*-BWEGR0^Q~QV<UgcCTcf>,z`{(HPWXe7kh0bx');
define('LOGGED_IN_KEY',    'O2?28l7xMBC%6Jze|$Qeag [*|R]P:-?k~_-Ig4Rfz`8pejx_7~n@z9LTghm6,Rf');
define('NONCE_KEY',        'JI Ln`9orG+T|h4@1[FoX_UuM3d>yO*J!pCP=V>j;c@JiH,yc|Mx(*pI2HD_iohK');
define('AUTH_SALT',        '/vPcQ/f}AFz<L-pHyh#@:}RHV$?`h5lPer]PzL:$eL(@u@.n%%H@UP}|.XtuwDS8');
define('SECURE_AUTH_SALT', 'wg270MTU8A|/f/{2|3;-,(Fska%+Owj4_0??_@W9!R|<fzzFdCoYO>.Y^7h},gXT');
define('LOGGED_IN_SALT',   'vGy+=@#~NPNdS|+8>=8tA hKnLRJvLws5G]_=ZY]c%eV*AMlD.Toe^X|Sw~|-`jp');
define('NONCE_SALT',       'O!|c#yuBv~W+S+ip~ao:vlryuW|Ro^YxyUP4hHw2o+<08:=n~n+(58lh/S-Phuv ');
/**#@-*/
/**
 * Prefisso Tabella del Database WordPress.
 *
 * È possibile avere installazioni multiple su di un unico database
 * fornendo a ciascuna installazione un prefisso univoco.
 * Solo numeri, lettere e sottolineatura!
 */
$table_prefix = 'reinn0vam3_';
/**
 * Per gli sviluppatori: modalità di debug di WordPress.
 *
 * Modificare questa voce a TRUE per abilitare la visualizzazione degli avvisi durante lo sviluppo
 * È fortemente raccomandato agli svilupaptori di temi e plugin di utilizare
 * WP_DEBUG all’interno dei loro ambienti di sviluppo.
 *
 * Per informazioni sulle altre costanti che possono essere utilizzate per il debug,
 * leggi la documentazione
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);
/* Finito, interrompere le modifiche! Buon blogging. */
/** Path assoluto alla directory di WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Imposta le variabili di WordPress ed include i file. */
require_once(ABSPATH . 'wp-settings.php');



/** Disabilitare cron job file */
define('DISABLE_WP_CRON', true);


