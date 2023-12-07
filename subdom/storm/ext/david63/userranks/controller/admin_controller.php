<?php
/**
*
* @package User Ranks Extension
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\userranks\controller;

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
	* @param \phpbb\config\config		$config		Config object
	* @param \phpbb\request\request		$request	Request object
	* @param \phpbb\template\template	$template	Template object
	* @param \phpbb\user				$user		User object
	* @param ContainerInterface			$container	Service container interface
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
		$form_key = 'userranks_manage';
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
			$phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'USER_RANKS_LOG');

			// Option settings have been updated and logged
			// Confirm this to the user and provide link back to previous page
			trigger_error($this->user->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'USER_RANKS_BOTS'			=> isset($this->config['userranks_ignore_bots']) ? $this->config['userranks_ignore_bots'] : '',
			'USER_RANKS_ENABLED' 		=> isset($this->config['userranks_enable']) ? $this->config['userranks_enable'] : '',
			'USER_RANKS_HEADER'			=> isset($this->config['userranks_header_link']) ? $this->config['userranks_header_link'] : '',
			'USER_RANKS_MEMBERS' 		=> isset($this->config['userranks_members']) ? $this->config['userranks_members'] : '',
			'USER_RANKS_MEMBERS_ADMIN'	=> isset($this->config['userranks_members_admin']) ? $this->config['userranks_members_admin'] : '',
			'USER_RANKS_SPECIAL' 		=> isset($this->config['userranks_special']) ? $this->config['userranks_special'] : '',
			'USER_RANKS_SPECIAL_ADMIN'	=> isset($this->config['userranks_special_admin']) ? $this->config['userranks_special_admin'] : '',

			'U_ACTION' => $this->u_action,
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
		$this->config->set('userranks_enable', $this->request->variable('userranks_enable', 0));
		$this->config->set('userranks_header_link', $this->request->variable('userranks_header_link', 0));
		$this->config->set('userranks_ignore_bots', $this->request->variable('userranks_ignore_bots', 0));
		$this->config->set('userranks_members', $this->request->variable('userranks_members', 0));
		$this->config->set('userranks_members_admin', $this->request->variable('userranks_members_admin', 0));
		$this->config->set('userranks_special', $this->request->variable('userranks_special', 0));
		$this->config->set('userranks_special_admin', $this->request->variable('userranks_special_admin', 0));
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}

}
