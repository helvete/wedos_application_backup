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
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
   'LOGO_CENTRE'				=> 'Midden',
   'LOGO_LEFT'                  => 'Links',
   'LOGO_RIGHT'					=> 'Rechts',

   'SITE_LOGO'					=> 'Aangepaste site logo',

   'SITE_LOGO_EXPLAIN'			=> 'Hier kunt u de opties voor een aangepaste site logo instellen en de bestaande logo vervangen.',

   'SITE_LOGO_HEIGHT'			=> 'Logo hoogte',
   'SITE_LOGO_HEIGHT_EXPLAIN'	=> 'Laat dit leeg voor de originele logo hoogte.<br />De standaard logo hoogte is 52px.',

   'SITE_LOGO_IMAGE'            => 'Url naar de standaard site logo.',
   'SITE_LOGO_IMAGE_EXPLAIN'	=> 'Laat dit leeg om de originele logo te gebruiken.<br />Als u een externe afbeelding logo wilt gebruiken voert u de volledige url in anders gewoon de locatie van de afbeelding logo op de site.',

   'SITE_LOGO_LEFT'				=> 'Linker hoeken',
   'SITE_LOGO_LEFT_EXPLAIN'		=> 'Rond de linker hoeken af om de banner te laten passen.',
   'SITE_LOGO_LOG'              => '<strong>Site logo opties bijgewerkt</strong>',

   'SITE_LOGO_MANAGE'           => 'Beheer site logo',

   'SITE_LOGO_OPTIONS'          => 'Site logo opties',

   'SITE_LOGO_PIXELS'           => 'Pixels',
   'SITE_LOGO_PIXELS_EXPLAIN'	=> 'Stel het aantal pixels in voor het afronden.',
   'SITE_LOGO_POSITION'         => 'Site logo positie',

   'SITE_LOGO_REMOVE'           => 'Verwijder site logo',
   'SITE_LOGO_REMOVE_EXPLAIN'	=> 'Deze optie voorkomt weergave site logo.',
   'SITE_LOGO_RIGHT'            => 'Rechter hoeken',
   'SITE_LOGO_RIGHT_EXPLAIN'	=> 'Rond de rechter hoeken af om de banner te laten passen.',

   'SITE_LOGO_WIDTH'            => 'Logo breedte',
   'SITE_LOGO_WIDTH_EXPLAIN'	=> 'Laat dit leeg voor de originele logo breedte.<br />De standaard logo breedte is 149px.',

   'SITE_NAME_SUPRESS'			=> 'Geen tekstweergave',
   'SITE_NAME_SUPRESS_EXPLAIN'	=> 'Deze optie voorkomt dat de <strong>Site Naam</strong> en de <strong>Site beschrijving</strong> wordt getoont.',

   'SITE_SEARCH_REMOVE'         => 'Verwijder zoekbox',
   'SITE_SEARCH_REMOVE_EXPLAIN'	=> 'Deze optie verwijderd de zoekbox uit de Banner.',
));
