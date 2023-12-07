<?php
/**
*
* @package User Ranks Extension
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\userranks\event;

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

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config		$config				Config object
	* @param \phpbb\template\twig\twig	$template			Template object
	* @param \phpbb\controller\helper	$controller_helper	Controller helper object
	* @param \phpbb\auth\auth 			$auth
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\template\twig\twig $template, \phpbb\controller\helper $controller_helper, \phpbb\auth\auth $auth)
	{
		$this->config				= $config;
		$this->template				= $template;
		$this->controller_helper	= $controller_helper;
		$this->auth					= $auth;
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
			'core.user_setup'	=> 'load_language_on_setup',
			'core.page_header'	=> 'page_header',
		);
	}

	/**
	* Load common user ranks language files during user setup
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_language_on_setup($event)
	{
		$lang_set_ext	= $event['lang_set_ext'];
		$lang_set_ext[]	= array(
			'ext_name' => 'david63/userranks',
			'lang_set' => 'user_ranks_common',
		);

		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	* Add the required template variables
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function page_header($event)
	{
		$this->template->assign_vars(array(
			'S_USER_RANKS'				=> $this->config ['userranks_enable'],
			'S_USER_RANKS_LINK_ENABLED'	=> $this->config ['userranks_header_link'],

			'U_USER_RANKS' 				=> $this->controller_helper->route('david63_userranks_main_controller', array('name' => 'ranks')),
			'U_USER_RANKS_MEMBERS'		=> $this->config['userranks_members'] || ($this->config['userranks_members_admin'] && $this->auth->acl_get('a_')),
		));
	}

}
