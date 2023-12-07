<?php
/**
*
* @package Site Logo Extension
* @copyright (c) 2014 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\sitelogo\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\twig\twig */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpBB root path */
	protected $root_path;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config $config phpBB config
	* @param \phpbb\template\twig\twig $template phpBB template
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\template\twig\twig $template, \phpbb\user $user, $root_path)
	{
		$this->config		= $config;
		$this->template		= $template;
		$this->user			= $user;
		$this->root_path	= $root_path;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header_after' => 'site_logo_header',
		);
	}

	/**
	* Update the template variables
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function site_logo_header($event)
	{
		$site_logo_img = ($this->config['site_logo_remove']) ? '' : $this->user->img('site_logo');

		if ($this->config['site_logo_image'] && !$this->config['site_logo_remove'])
		{
			$logo_path		= (strpos(strtolower($this->config['site_logo_image']), 'http') !== false) ? $this->config['site_logo_image'] : append_sid($this->root_path . $this->config['site_logo_image'], false, false);

			$logo_corners 	= '0px 0px 0px 0px';
			$logo_corners 	= ($this->config['site_logo_left']) ? $this->config['site_logo_pixels'] . 'px 0px 0px ' . $this->config['site_logo_pixels'] . 'px' : $logo_corners;
 			$logo_corners 	= ($this->config['site_logo_right']) ? '0px ' . $this->config['site_logo_pixels'] . 'px ' . $this->config['site_logo_pixels'] . 'px 0px' : $logo_corners;
			$logo_corners 	= ($this->config['site_logo_left'] && $this->config['site_logo_right']) ? $this->config['site_logo_pixels'] . 'px ' . $this->config['site_logo_pixels'] . 'px ' . $this->config['site_logo_pixels'] . 'px ' . $this->config['site_logo_pixels'] . 'px' : $logo_corners;

			$site_logo_img	= '<img src=' . $logo_path . ' style="max-width: 100%; height:auto; height:' . $this->config['site_logo_height'] . 'px; width:' . $this->config['site_logo_width'] . 'px; -webkit-border-radius: ' . $logo_corners . '; -moz-border-radius: ' . $logo_corners . '; border-radius: ' . $logo_corners . ';">';
		}

		$this->template->assign_vars(array(
			'SITE_DESCRIPTION'	=> ($this->config['site_name_supress']) ? '' : ($this->config['site_desc']),
			'SITE_LOGO_CENTRE'	=> ($this->config['site_logo_position'] == 1) ? true : false,
			'SITE_LOGO_IMG'		=> $site_logo_img,
			'SITE_LOGO_RIGHT'	=> ($this->config['site_logo_position'] == 2) ? true : false,
			'SITENAME_SUPRESS'	=> ($this->config['site_name_supress']) ? true : false,
			'S_IN_SEARCH'		=> ($this->config['site_search_remove']) ? true : false,
		));
	}
}
