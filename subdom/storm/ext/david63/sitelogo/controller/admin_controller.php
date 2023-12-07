<?php
/**
*
* @package Site Logo Extension
* @copyright (c) 2014 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\sitelogo\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Admin controller
*/
class admin_controller implements admin_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var ContainerInterface */
	protected $container;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin controller
	*
	* @param \phpbb\config\config				$config		Config object
	* @param \phpbb\request\request				$request	Request object
	* @param \phpbb\template\template			$template	Template object
	* @param \phpbb\user						$user		User object
	* @param ContainerInterface					$container	Service container interface
	*
	* @return \phpbb\boardrules\controller\admin_controller
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, ContainerInterface $container)
	{
		$this->config		= $config;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->container	= $container;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		// Create a form key for preventing CSRF attacks
		$form_key			= 'sitelogo';
		add_form_key($form_key);

		// Is the form being submitted
		if ($this->request->is_set_post('submit'))
		{
			// Is the submitted form is valid
			if (!check_form_key($form_key))
			{
				trigger_error($this->user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// If no errors, process the form data
			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$phpbb_log = $this->container->get('log');
			$phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SITE_LOGO_LOG');

			// Option settings have been updated and logged
			// Confirm this to the user and provide link back to previous page
			trigger_error($this->user->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		// Set output vars for display in the template
		// Positions
		$positions = array();

		$positions[] = $this->user->lang('LOGO_LEFT');
		$positions[] = $this->user->lang('LOGO_CENTRE');
		$positions[] = $this->user->lang('LOGO_RIGHT');

		foreach ($positions as $value => $label)
		{
			$this->template->assign_block_vars('positions', array(
				'S_CHECKED'	=> ($this->config['site_logo_position'] == $value) ? true : false,
				'LABEL'		=> $label,
				'VALUE'		=> $value,
			));
		}

		$this->template->assign_vars(array(
			'SITE_LOGO_HEIGHT'		=> isset($this->config['site_logo_height']) ? $this->config['site_logo_height'] : '',
			'SITE_LOGO_IMAGE'		=> isset($this->config['site_logo_image']) ? $this->config['site_logo_image'] : '',
			'SITE_LOGO_LEFT'		=> isset($this->config['site_logo_left']) ? $this->config['site_logo_left'] : '',
			'SITE_LOGO_PIXELS'		=> isset($this->config['site_logo_pixels']) ? $this->config['site_logo_pixels'] : '',
			'SITE_LOGO_REMOVE'		=> isset($this->config['site_logo_remove']) ? $this->config['site_logo_remove'] : '',
			'SITE_LOGO_RIGHT'		=> isset($this->config['site_logo_right']) ? $this->config['site_logo_right'] : '',
			'SITE_LOGO_WIDTH'		=> isset($this->config['site_logo_width']) ? $this->config['site_logo_width'] : '',
			'SITE_NAME_SUPRESS'		=> isset($this->config['site_name_supress']) ? $this->config['site_name_supress'] : '',
			'SITE_SEARCH_REMOVE'	=> isset($this->config['site_search_remove']) ? $this->config['site_search_remove'] : '',

			'U_ACTION'			=> $this->u_action,
		));
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_options()
	{
		$this->config->set('site_logo_height', $this->request->variable('site_logo_height', ''));
		$this->config->set('site_logo_image', $this->request->variable('site_logo_image', ''));
		$this->config->set('site_logo_left', $this->request->variable('site_logo_left', 0));
		$this->config->set('site_logo_pixels', $this->request->variable('site_logo_pixels', 0));
		$this->config->set('site_logo_position', $this->request->variable('site_logo_position', 0));
		$this->config->set('site_logo_remove', $this->request->variable('site_logo_remove', 0));
		$this->config->set('site_logo_right', $this->request->variable('site_logo_right', 0));
		$this->config->set('site_logo_width', $this->request->variable('site_logo_width', ''));
		$this->config->set('site_name_supress', $this->request->variable('site_name_supress', 0));
		$this->config->set('site_search_remove', $this->request->variable('site_search_remove', 0));
	}
}
