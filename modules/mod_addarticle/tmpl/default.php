<?php 
defined('_JEXEC') or die;
$doc=JFactory::getDocument();
//http://akinbo.konservs.com/create?tmpl=component

$url=JRoute::_('index.php?option=com_content&view=form&layout=edit');
$url_tmpl=JRoute::_('index.php?option=com_content&view=form&layout=edit&tmpl=component');

$jscontent='window.url_addarticle_tmpl="'.$url_tmpl.'";'.PHP_EOL;
$doc->addScriptDeclaration($jscontent);

$doc->addStylesheet('/media/mod_addarticle/css/addarticle.css');
$doc->addScript('/media/mod_addarticle/js/addarticle.js');
?>
<div class="mod_addarticle">
	<a class="brn btn-default" href="<?php echo $url; ?>">Add Article</a>
</div>