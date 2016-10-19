<?php 
defined('_JEXEC') or die;
$doc=JFactory::getDocument();
//http://akinbo.konservs.com/create?tmpl=component
$moduleid='111';
$url=JRoute::_('index.php?option=com_content&view=form&layout=edit');
$url_tmpl=JRoute::_('index.php?option=com_content&view=form&layout=edit&tmpl=component');

$jscontent='window.url_addarticle_tmpl="'.$url_tmpl.'";'.PHP_EOL;
$doc->addScriptDeclaration($jscontent);

$doc->addStylesheet('/media/mod_addarticle/css/addarticle.css');
$doc->addScript('/media/mod_addarticle/js/addarticle.js');
?>
<div class="mod_addarticle" id="mod_addarticle_<?php echo $moduleid; ?>">
	<a class="btn btn-default mod_addarticle_btn" href="<?php echo $url; ?>" data-remote="<?php echo $url_tmpl; ?>" data-toggle="modal" data-target="#mod_addarticle_<?php echo $moduleid; ?>_popup"><span class="icon-new"></span>&nbsp;Add Article</a>
	<?php if ($type == 'login') : ?>
		<?php require JModuleHelper::getLayoutPath('mod_addarticle', 'default_login'); ?>
	<?php else: ?>
		<?php require JModuleHelper::getLayoutPath('mod_addarticle', 'default_edit'); ?>
	<?php endif; ?>
</div>
