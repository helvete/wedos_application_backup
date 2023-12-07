<?php
/**
*
* @package Site Logo Extension
* @copyright (c) 2014 david63
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* Swedish translation by Holger (http://www.maskinisten.net)
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
	'LOGO_CENTRE'					=> 'Centrerat',
	'LOGO_LEFT'						=> 'Vänster',
	'LOGO_RIGHT'					=> 'Höger',

	'SITE_LOGO'						=> 'Egen logga',

	'SITE_LOGO_EXPLAIN'				=> 'Här kan du ändra inställningarna för att visa din egna sidlogga som ska ersätta standardloggan.',

	'SITE_LOGO_HEIGHT'				=> 'Loggans höjd',
	'SITE_LOGO_HEIGHT_EXPLAIN'		=> 'Lämnas detta fält tomt så används standardloggans höjd (52px).',

	'SITE_LOGO_IMAGE'				=> 'Sökväg till den egna sidloggan.',
	'SITE_LOGO_IMAGE_EXPLAIN'		=> 'Lämna detta fält tomt för att använda standardloggan.<br />Om du vill använda en logga från en annan domän anger du hela URLen, annars anger du den lokala sökvägen.',

	'SITE_LOGO_LEFT'				=> 'Vänstra hörn',
	'SITE_LOGO_LEFT_EXPLAIN'		=> 'Runda av de vänstra hörnen för att matcha sidhuvudet.',
	'SITE_LOGO_LOG'					=> '<strong>Inställningarna för den egna loggan har sparats</strong>',

	'SITE_LOGO_MANAGE'				=> 'Hantera egen logga',

	'SITE_LOGO_OPTIONS'				=> 'Inställningar för egen logga',

	'SITE_LOGO_PIXELS'				=> 'pixlar',
	'SITE_LOGO_PIXELS_EXPLAIN'		=> 'Ställer in pixlarna för hörnets rundning.',
	'SITE_LOGO_POSITION'			=> 'Loggans position',

	'SITE_LOGO_REMOVE'				=> 'Dölj loggan',
	'SITE_LOGO_REMOVE_EXPLAIN'		=> 'Denna inställning döljer sidans logga.',
	'SITE_LOGO_RIGHT'				=> 'Högra hörn',
	'SITE_LOGO_RIGHT_EXPLAIN'		=> 'Runda av de högra hörnen för att matcha sidhuvudet.',

	'SITE_LOGO_WIDTH'				=> 'Loggans bredd',
	'SITE_LOGO_WIDTH_EXPLAIN'		=> 'Lämnas detta fält tomt så används standardloggans bredd (149px).',

	'SITE_NAME_SUPRESS'				=> 'Dölj texterna intill loggan',
	'SITE_NAME_SUPRESS_EXPLAIN'		=> 'Denna inställning döljer texterna <strong>Site Name</strong> och <strong>Site Description</strong>.',

	'SITE_SEARCH_REMOVE'			=> 'Dölj sökrutan',
	'SITE_SEARCH_REMOVE_EXPLAIN'	=> 'Denna inställning döljer sökrutan i sidhuvudet.',
));
