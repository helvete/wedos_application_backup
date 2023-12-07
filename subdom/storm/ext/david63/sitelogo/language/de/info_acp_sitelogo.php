<?php

/**
*
* @package Site Logo Extension
* @copyright (c) 2014 david63
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* German translation by tas2580 (https://tas2580.net)
*/
/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
$lang = array_merge($lang, array(
	'LOGO_CENTRE'					=> 'Zentriert',
	'LOGO_LEFT'						=> 'Links',
	'LOGO_RIGHT'					=> 'Rechts',
	'SITE_LOGO'						=> 'Benutzerdefiniertes Seiten Logo',
	'SITE_LOGO_EXPLAIN'				=> 'Hier kannst du die Optionen für ein benutzerdefiniertes Logo einrichten um das Standard-Logo ersetzen.',
	'SITE_LOGO_HEIGHT'				=> 'Logo Höhe',
	'SITE_LOGO_HEIGHT_EXPLAIN'		=> 'Leer lassen um die original Höhe zu verwenden.<br />Die Standard Höhe ist 52px.',
	'SITE_LOGO_IMAGE'				=> 'Pfad zum benutzerdefinierten Seiten Logo.',
	'SITE_LOGO_IMAGE_EXPLAIN'		=> 'Leer lassen um das Standard Logo zu verwenden.<br />Du kannst eine volle URL oder einen relativen Pfad zum Logo angeben.',
	'SITE_LOGO_LEFT'				=> 'Linke Ecken',
	'SITE_LOGO_LEFT_EXPLAIN'		=> 'Runde die Ecken auf der linken Seite ab.',
	'SITE_LOGO_LOG'					=> '<strong>Benutzerdefiniertes Seiten Logo aktualisiert</strong>',
	'SITE_LOGO_MANAGE'				=> 'Logo verwalten',
	'SITE_LOGO_OPTIONS'				=> 'Seiten Logo Einstellungen',
	'SITE_LOGO_PIXELS'				=> 'Pixel',
	'SITE_LOGO_PIXELS_EXPLAIN'		=> 'Legt die Anzahl der Pixel für die Rundung fest.',
	'SITE_LOGO_POSITION'			=> 'Seiten Logo Position',
	'SITE_LOGO_REMOVE'				=> 'Seiten Logo entfernen',
	'SITE_LOGO_REMOVE_EXPLAIN'		=> 'Wenn die Option gesetzt ist wird die Anzeige des Seiten Logos verhindert.',
	'SITE_LOGO_RIGHT'				=> 'Rechte Ecken',
	'SITE_LOGO_RIGHT_EXPLAIN'		=> 'Runde die Ecken auf der rechten Seite ab.',
	'SITE_LOGO_WIDTH'				=> 'Logo Breite',
	'SITE_LOGO_WIDTH_EXPLAIN'		=> 'Leer lassen um die original Breite zu verwenden.<br />Die Standard Höhe ist 149px.',
	'SITE_NAME_SUPRESS'				=> 'Textanzeige unterdrücken',
	'SITE_NAME_SUPRESS_EXPLAIN'		=> 'Wenn die Option gesetzt ist wird der <strong>Seiten Name</strong> und die <strong>Seiten Beschreibung</strong> nicht angezeigt.',
	'SITE_SEARCH_REMOVE'			=> 'Suchbox verstecken',
	'SITE_SEARCH_REMOVE_EXPLAIN'	=> 'Wenn die Option gesetzt ist wird wird die Suchbox im Header nicht angezeigt.',
));
