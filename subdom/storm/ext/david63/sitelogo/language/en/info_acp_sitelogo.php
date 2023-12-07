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
	'LOGO_CENTRE'					=> 'Centre',
	'LOGO_LEFT'						=> 'Left',
	'LOGO_RIGHT'					=> 'Right',

	'SITE_LOGO'						=> 'Custom site logo',

	'SITE_LOGO_EXPLAIN'				=> 'Here you can set the options for a custom site logo to replace the default logo.',

	'SITE_LOGO_HEIGHT'				=> 'Logo height',
	'SITE_LOGO_HEIGHT_EXPLAIN'		=> 'Leaving this blank will use the logo’s original height.<br />The default logo height is 52px.',

	'SITE_LOGO_IMAGE'				=> 'Path to the custom site logo.',
	'SITE_LOGO_IMAGE_EXPLAIN'		=> 'Leaving this blank will use the default logo.<br />If you want to use a remote image as the logo then enter the full url of the image otherwise just enter the location of the image on the site.',

	'SITE_LOGO_LEFT'				=> 'Left corners',
	'SITE_LOGO_LEFT_EXPLAIN'		=> 'Round the left side corners to match the banner.',
	'SITE_LOGO_LOG'					=> '<strong>Custom site logo options updated</strong>',

	'SITE_LOGO_MANAGE'				=> 'Manage site logo',

	'SITE_LOGO_OPTIONS'				=> 'Site logo options',

	'SITE_LOGO_PIXELS'				=> 'Pixels',
	'SITE_LOGO_PIXELS_EXPLAIN'		=> 'Sets the number of pixel for rounding.',
	'SITE_LOGO_POSITION'			=> 'Site logo position',

	'SITE_LOGO_REMOVE'				=> 'Remove site logo',
	'SITE_LOGO_REMOVE_EXPLAIN'		=> 'Setting this option will prevent the display the site logo.',
	'SITE_LOGO_RIGHT'				=> 'Right corners',
	'SITE_LOGO_RIGHT_EXPLAIN'		=> 'Round the right side corners to match the banner.',

	'SITE_LOGO_WIDTH'				=> 'Logo width',
	'SITE_LOGO_WIDTH_EXPLAIN'		=> 'Leaving this blank will use the logo’s original width.<br />The default logo width is 149px.',

	'SITE_NAME_SUPRESS'				=> 'Suppress text display',
	'SITE_NAME_SUPRESS_EXPLAIN'		=> 'Setting this option will prevent the <strong>Site Name</strong> and <strong>Site Description</strong> from being displayed.',

	'SITE_SEARCH_REMOVE'			=> 'Remove search box',
	'SITE_SEARCH_REMOVE_EXPLAIN'	=> 'Setting this option will remove the search box from the header panel.',
));
