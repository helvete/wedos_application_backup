<?php
/**
*
* @package Site Logo Extension
* @copyright (c) 2014 david63
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
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
	'LOGO_CENTRE'					=> 'Centro',
	'LOGO_LEFT'						=> 'Sinistra',
	'LOGO_RIGHT'					=> 'Destra',

	'SITE_LOGO'						=> 'Logo personalizzato',

	'SITE_LOGO_EXPLAIN'				=> 'Impostare le opzioni per un logo personalizzato che sostituisca quello di default.',

	'SITE_LOGO_HEIGHT'				=> 'Altezza logo',
	'SITE_LOGO_HEIGHT_EXPLAIN'		=> 'Lasciare vuoto questo campo per utilizzare l\'altezza originale del logo.<br />L\'altezza predefinita è 52px.',

	'SITE_LOGO_IMAGE'				=> 'Path del logo personalizzato',
	'SITE_LOGO_IMAGE_EXPLAIN'		=> 'Lasciando vuoto questo campo, verrà utilizzato il logo predefinito.<br />Se si desidera utilizzare un\'immagine remota come logo, inserire l\'URL completo dell\'immagine, altrimenti inserire la posizione dell\'immagine sul sito.',

	'SITE_LOGO_LEFT'				=> 'Angoli del lato sinistro',
	'SITE_LOGO_LEFT_EXPLAIN'		=> 'Arrotondare gli angoli del lato sinistro.',
	'SITE_LOGO_LOG'					=> '<strong>Opzioni logo personalizzato aggiornate</strong>',

	'SITE_LOGO_MANAGE'				=> 'Impostazioni',

	'SITE_LOGO_OPTIONS'				=> 'Opzioni logo',

	'SITE_LOGO_PIXELS'				=> 'Numero di pixel',
	'SITE_LOGO_PIXELS_EXPLAIN'		=> 'Settare il numero di pixel per l\'arrotondamento degli angoli.',
	'SITE_LOGO_POSITION'			=> 'Posizione del logo',

	'SITE_LOGO_REMOVE'				=> 'Rimuovere il logo del sito',
	'SITE_LOGO_REMOVE_EXPLAIN'		=> 'Settando questa opzione, verrà nascosto il logo del sito.',
	'SITE_LOGO_RIGHT'				=> 'Angoli del lato destro',
	'SITE_LOGO_RIGHT_EXPLAIN'		=> 'Arrotondare gli angoli del lato destro.',

	'SITE_LOGO_WIDTH'				=> 'Larghezza logo',
	'SITE_LOGO_WIDTH_EXPLAIN'		=> 'Lasciare vuoto questo campo per utilizzare la larghezza originale del logo.<br />La larghezza predefinita è 149px.',

	'SITE_NAME_SUPRESS'				=> 'Rimuovere la visualizzazione del testo',
	'SITE_NAME_SUPRESS_EXPLAIN'		=> 'Settando questa opzione, verrà nascosto il <strong>Nome</strong> e la <strong>Descrizione</strong> del sito.',

	'SITE_SEARCH_REMOVE'			=> 'Rimuovere il box "Cerca..."',
	'SITE_SEARCH_REMOVE_EXPLAIN'	=> 'Settando questa opzione, verrà rimosso il box di ricerca dall\'header.',
));
