<?php
/**
 * 
 *
 * @author Alexandre Hermann
 * @version 1.4
 * @copyright AHWEBDEV, 6 June, 2012
 * @package Facebook AWD
 **/
class AWD_facebook_options
{
	/**
	 * Protected Option var
	 */
	protected $options = array();
	
	/**
	 * Protected Wpdb instance WP database
	 */
	protected $wpdb;
	
	/**
	 * Protected prefix
	 */
	protected $prefix;
	
	/**
	 * Protected filterName
	 */
	protected $filterName = "AWD_facebook_options";
	
	/**
	 * Construct
	 * @param   string   Prefix (recommend use $AWD_facebook->plugin_option_pref)
	 * @param   object   wpdb instance
	 * @return  void
	 */
	public function __construct($prefix,$wpdb){
		$this->wpdb = $wpdb;
		$this->prefix = $prefix;
		//call filter for undefined vars
		add_filter($this->filterName, array($this,'defaultOptions'), 10, 1);
		//ban hosts
		$this->ban_hosts = array('Y2l0eXZpbGxlY2hhdC5jb20=');
	}
	
	
	
	/**
	 * Set the default option if empty
	 * @param string $options_name
	 * @param array|string $default_value
	 * @return array
	 */
	public function setDefaultValue($options_name, $default_value)
	{
		if(empty($this->options[$options_name])){
			$this->options[$options_name] = $default_value;
		}
	}
	
	/**
	 * defaultOptions Options
	 * @param   Array   list of Options
	 * @param   object   wpdb instance
	 * @return  void
	 */
    public function defaultOptions($options)
    {
		global $AWD_facebook;
		$this->options = $options;
		
		//Languages
		if($this->options['locale']==''){
		    if(defined('WPLANG')){
		        if(WPLANG==''){
		            $this->setDefaultValue('locale', 'en_US');
		        }else{
		            $this->setDefaultValue('locale', WPLANG);
		        }
		    }
		}
		
		//Permissions
		$perms = '';
		$perms_admin = '';
		$array_perms = explode(",",$this->options['perms']);
		if(!in_array('email',$array_perms))
			$perms = 'email,'.$this->options['perms'];  
		if(current_user_can('manage_options'))
			if(!in_array('manage_pages',$array_perms))
				$perms_admin = 'manage_pages,';
		if($AWD_facebook->current_facebook_user_can('publish_stream'))
        	if(!in_array('publish_stream',$array_perms))
				$perms_admin .= 'publish_stream,';
		$perms = str_replace(' ','',rtrim($this->options['perms'],','));
		$perms_admin = str_replace(' ','',rtrim($this->options['perms_admin'],','));
		$this->setDefaultValue('perms', $perms);
		$this->setDefaultValue('perms_admin', $perms.' '.$perms_admin);
		
		//Plugin and options
		$this->setDefaultValue('connect_enable', 0);
		$this->setDefaultValue('open_graph_enable', 1);
		$this->setDefaultValue('connect_fbavatar', 0);
		$this->setDefaultValue('debug_enable', 0);
		$this->setDefaultValue('publish_to_profile', 0);
		$this->setDefaultValue('publish_to_pages', 0);
		$this->setDefaultValue('publish_message_text', '');
		$this->setDefaultValue('publish_read_more_text', __('Read More',$AWD_facebook->plugin_text_domain));
		
		//API
		$this->setDefaultValue('app_id', '');
		$this->setDefaultValue('app_secret_key', '');
		$this->setDefaultValue('admins', '');
		$this->setDefaultValue('timeout', 10);
		$this->setDefaultValue('app_infos', array());

		//OPENGRAPH
		$this->setDefaultValue('opengraph_objects', array());
		$this->setDefaultValue('opengraph_object_links', array());

		//Plugins options	
		$like_button = array
		(
			'href' 							=> home_url(),
			'send' 							=> 0,
			'width' 						=> 300,
			'height' 						=> 35,
			'colorscheme'					=> 'light',
			'show_faces' 					=> 0,
			'font' 							=> 'arial',
			'action' 						=> 'like',
			'layout' 						=> 'standard',
			'type' 							=> 'html5',
			'ref' 							=> '',
			'on_pages' 						=> 0,
			'place_on_pages'				=> 'top',
			'on_posts' 						=> 0,
			'place_on_posts' 				=> 'top',
			'on_custom_post_types'			=> 0,
			'place_on_custom_post_types'	=> 'top',
			'exclude_post_type'				=> '',
			'exclude_terms_slug'			=> '',
			'exclude_post_id'				=> ''
		);
		$this->setDefaultValue('like_button', $like_button);
		
		$like_box = array
		(
			'href' 							=> home_url(),
			'width' 						=> 292,
			'height' 						=> 300,
			'colorscheme'					=> 'light',
			'show_faces' 					=> 0,
			'stream' 						=> 0,
			'type' 							=> 'html5',
			'border_color' 					=> '',
			'force_wall' 					=> '',
			'header' 						=> 0,
		);
		$this->setDefaultValue('like_box', $like_box);
		
		$url = parse_url(home_url());
		$activity_box = array
		(
			'domain' 						=> $url['host'],
			'width' 						=> 292,
			'height' 						=> 300,
			'colorscheme'					=> 'light',
			'font' 							=> 'arial',
			'show_faces' 					=> 0,
			'type' 							=> 'html5',
			'border_color' 					=> '',
			'recommendations' 				=> 0,
			'header' 						=> 0,
			'filter' 						=> '',
			'linktarget' 					=> '_blank',
			'ref' 							=> '',
			'max_age' 						=> '',
		);
		$this->setDefaultValue('activity_box', $activity_box);
		
		$login_button = array
		(
			'display_on_login_page' 		=> 0,
			'login_redirect_url' 			=> '',
			'logout_redirect_url' 			=> '',
			'logout_label' 					=> __('Logout', $AWD_facebook->plugin_text_domain),
			'show_profile_picture'			=> 1,
			'show_faces'					=> 0,
			'maxrow'						=> 1,
			'width'							=> 200,
			'image'							=> $AWD_facebook->plugin_url_images.'f-connect.png'
		);
		$this->setDefaultValue('login_button', $login_button);
		
		$comments_box = array
		(
			'href' 							=> home_url(),
			'colorscheme' 					=> 'light',
			'width' 						=> 500,
			'num_posts' 							=> 10,
			'type'							=> 'html5',
			'mobile'						=> 0,
			'on_pages'						=> 0,
			'on_posts'						=> 0,
			'exclude_post_id'				=> ''
		);
		$this->setDefaultValue('comments_box', $comments_box);
		
        return $this->options;
    }
	
	/**
	 * Getter
	 * Options
	 * @return Array list of Options
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Setter
	 * Options
	 * @param Array list of Options
	 * @return void
	 */
	public function setOptions($options)
	{
		$this->options = $options;
	}
	
	/**
	 * Load
	 * Load Options From database and apply filter : apply_filters($this->filterName, get_option($this->filterName));
	 * @return void
	 */
	public function load()
	{
		$this->options = apply_filters($this->filterName, get_option($this->filterName));
	}
	
	/**
	 * Save
	 * Save options in options table wp
	 */
	public function save()
	{
		$old_options = get_option($this->filterName);
		//create new options
		$this->options = is_array($old_options) ? array_merge($old_options, $this->options) : $this->options;
		
		//verify default value
		$this->defaultOptions($this->options);
		update_option($this->filterName, $this->options);
		$this->load();
	}
	
	/**
	 * Add option
	 * Save options in options table wp
	 */
	public function update_option($name,$value,$flush=false)
	{
		$this->options[$name] = $value;
		if($flush === true)
			$this->save();
	}
	
	/**
	 * reset
	 * reset all Options with a new empty array.
	 */
	public function reset()
	{
		update_option($this->filterName, array());
	}
	
	/**
	 * Debug
	 */
	public function debug()
	{
		echo '<pre>';
		var_dump($this->options);
		echo '</pre>';
	}
	
}
?>