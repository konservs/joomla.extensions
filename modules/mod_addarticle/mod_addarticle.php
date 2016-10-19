<?php 
defined('_JEXEC') or die;

require_once JPATH_BASE.'/modules/mod_login/helper.php';
require_once JPATH_BASE.'/modules/mod_slogin/helper.php';



$doc = JFactory::getDocument();
$type	= modLoginHelper::getType();
$return	= modLoginHelper::getReturnURL($params, $type);
$allow = modSLoginHelper::getalw($params);
$input = JFactory::getApplication()->input;
$task = $input->getCmd('task', '');
$option = $input->getCmd('option', '');
if(!($option == 'com_slogin' && ($task == 'auth' || $task == 'check'))){
	JFactory::getApplication()->setUserState('com_slogin.return_url', $return);
	}
$user = JFactory::getUser();
$input = new JInput;
$callbackUrl = '';
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$dispatcher	= JDispatcher::getInstance();

JPluginHelper::importPlugin('slogin_auth');
$plugins = array();
$config = JComponentHelper::getParams('com_slogin');
if($config->get('service_auth', 0)){
	modSLoginHelper::loadLinks($plugins, $callbackUrl, $params);
	}
else{
	$dispatcher->trigger('onCreateSloginLink', array(&$plugins, $callbackUrl));
	}

$jll = (!modSLoginHelper::getalw($params))? '<div style="text-align: right;">'.JText::_('MOD_SLOGIN_LINK').'</div>': '';

$profileLink = $avatar = '';
if(JPluginHelper::isEnabled('slogin_integration', 'profile') && $user->id > 0){
	require_once JPATH_BASE.'/plugins/slogin_integration/profile/helper.php';
	$profile = plgProfileHelper::getProfile($user->id);
	$avatar = isset($profile->avatar) ? $profile->avatar : '';
	$profileLink = isset($profile->social_profile_link) ? $profile->social_profile_link : '';
	}
elseif(JPluginHelper::isEnabled('slogin_integration', 'slogin_avatar') && $user->id > 0){
	require_once JPATH_BASE.'/plugins/slogin_integration/slogin_avatar/helper.php';
        $path = Slogin_avatarHelper::getavatar($user->id);
        if(!empty($path['photo_src'])){
		$avatar = $path['photo_src'];
		if(JString::strpos($avatar, '/') !== 0)
			$avatar = '/'.$avatar;
		}
	$profileLink = isset($path['profile']) ? $path['profile'] : '';
	}

require JModuleHelper::getLayoutPath('mod_addarticle', $params->get('layout', 'default'));
