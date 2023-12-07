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
	'SITE_LOGO'					=> 'Logo del sitio personalizado',

	'SITE_LOGO_EXPLAIN'			=> 'Aquí puede establecer las opciones para el logo del sitio personalizado y reemplazar el logo por defecto.',

	'SITE_LOGO_HEIGHT'			=> 'Altura del logo',
	'SITE_LOGO_HEIGHT_EXPLAIN'	=> 'Dejando esto en blanco usará altura original del logotipo.<br />La altura por defecto del logo es 52px.',

	'SITE_LOGO_IMAGE'			=> 'Ruta de acceso al logo del sitio personalizado.',
	'SITE_LOGO_IMAGE_EXPLAIN'	=> 'Dejando esto en blanco usará el logo por defecto.<br />Si desea utilizar una imagen remota para el logo, introduzca la URL completa de la imagen, o de lo contrario sólo introduzca la ubicación de la imagen en el sitio.',

	'SITE_LOGO_LEFT'			=> 'Bordes de la izquierda',
	'SITE_LOGO_LEFT_EXPLAIN'	=> 'Redondear los bordes del lado izquierdo para que coincida con el banner.',
	'SITE_LOGO_LOG'				=> '<strong>Opciones del logo del sitio personalizado actualizadas</strong>',

	'SITE_LOGO_MANAGE'			=> 'Gestionar logo del sitio',

	'SITE_LOGO_OPTIONS'			=> 'Opciones del logo del sitio',

	'SITE_LOGO_PIXELS'			=> 'Píxeles',
	'SITE_LOGO_PIXELS_EXPLAIN'	=> 'Establezca el número de píxeles para el redondeo.',

	'SITE_LOGO_RIGHT'			=> 'Bordes de la derecha',
	'SITE_LOGO_RIGHT_EXPLAIN'	=> 'Redondear los bordes del lado derecho para que coincida con el banner.',

	'SITE_LOGO_SUPRESS'			=> 'Suprimir la visualización de texto',
	'SITE_LOGO_SUPRESS_EXPLAIN'	=> 'Al configurar esta opción evitará mostrar el <strong>Nombre del sitio</strong> y <strong>Descripción del sitio</strong>.',

	'SITE_LOGO_WIDTH'			=> 'Anchura del logo',
	'SITE_LOGO_WIDTH_EXPLAIN'	=> 'Dejando esto en blanco usará anchura original del logo.<br />La anchura por defecto del logo es 149px.',
));
