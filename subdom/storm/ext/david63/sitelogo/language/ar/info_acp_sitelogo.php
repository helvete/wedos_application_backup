<?php
/**
*
* @package Site Logo Extension
* @copyright (c) 2014 david63
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Translated By : Basil Taha Alhitary - www.alhitary.net
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
	'LOGO_CENTRE'					=> 'وسط',
	'LOGO_LEFT'						=> 'يسار',
	'LOGO_RIGHT'					=> 'يمين',

	'SITE_LOGO'						=> 'تخصيص شعار المنتدى',

	'SITE_LOGO_EXPLAIN'				=> 'تستطيع من هنا ضبط الخيارات لتخصيص صورة شعار المنتدى التي تريدها واستبدال الشعار الافتراضي للمنتدى.',

	'SITE_LOGO_HEIGHT'				=> 'الإرتفاع ',
	'SITE_LOGO_HEIGHT_EXPLAIN'		=> 'سيتم استخدام ارتفاع الصورة الأصلية لو تركت هذا الحقل فارغاً. ارتفاع صورة الشعار الأفتراضي هو 152px.',

	'SITE_LOGO_IMAGE'				=> 'رابط الشعار ',
	'SITE_LOGO_IMAGE_EXPLAIN'		=> 'سيتم استخدام الشعار الإفتراضي للمنتدى لو تركت هذا الحقل فارغاً.<br />اذا أردت استخدام صورة شعار من موقع آخر , فعليك كتابة الرابط كاملاً. أما لو كانت الصورة في موقعك , فعليك فقط كتابة مكان الصورة ( مثل : images/logo.jpg ).',

	'SITE_LOGO_LEFT'				=> 'الزاوية اليسار ',
	'SITE_LOGO_LEFT_EXPLAIN'		=> 'تدوير الزوايا أو الأركان بالجهة اليسار لصورة الشعار.',
	'SITE_LOGO_LOG'					=> '<strong>تم تحديث الخيارات بنجاح</strong>',

	'SITE_LOGO_MANAGE'				=> 'الإعدادات',

	'SITE_LOGO_OPTIONS'				=> 'الخيارات',

	'SITE_LOGO_PIXELS'				=> 'بيكسل',
	'SITE_LOGO_PIXELS_EXPLAIN'		=> 'ضبط عدد البيكسل pixel لتدوير صورة الشعار.',
	'SITE_LOGO_POSITION'			=> 'مكان الشعار ',

	'SITE_LOGO_REMOVE'				=> 'حذف صورة الشعار',
	'SITE_LOGO_REMOVE_EXPLAIN'		=> 'اختيارك "نعم" يعني اخفاء شعار المنتدى.',
	'SITE_LOGO_RIGHT'				=> 'الزاوية اليمين ',
	'SITE_LOGO_RIGHT_EXPLAIN'		=> 'تدوير الزوايا أو الأركان بالجهة اليمين لصورة الشعار.',

	'SITE_LOGO_WIDTH'				=> 'العرض ',
	'SITE_LOGO_WIDTH_EXPLAIN'		=> 'سيتم استخدام عرض الصورة الأصلية لو تركت هذا الحقل فارغاً. عرض صورة الشعار الأفتراضي هو 149px.',

	'SITE_NAME_SUPRESS'				=> 'إخفاء الإسم والوصف',
	'SITE_NAME_SUPRESS_EXPLAIN'		=> 'اختيارك "نعم" يعني اخفاء <strong>اسم الموقع</strong> و <strong>وصف الموقع</strong> من الظهور في الترويسة.',

	'SITE_SEARCH_REMOVE'			=> 'إخفاء مربع البحث ',
	'SITE_SEARCH_REMOVE_EXPLAIN'	=> 'اختيارك "نعم" يعني اخفاء مربع البحث الموجودة في ترويسة المنتدى.',
));
