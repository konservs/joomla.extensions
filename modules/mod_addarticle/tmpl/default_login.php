<?php 
defined('_JEXEC') or die;
?>
<div id="mod_addarticle_<?php echo $moduleid; ?>_popup" class="mod_addarticle_popup modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">To add article, at first You need to login</h4>
			</div>
			<div class="modal-body">
				<div id="slogin-buttons" class="slogin-buttons slogin-default<?php echo $moduleclass_sfx?>">
					<?php if (count($plugins)): ?>
						<?php foreach($plugins as $link): ?>
							<?php $linkParams = ''; ?>
							<?php if(isset($link['params'])): ?>
								<?php foreach($link['params'] as $k => $v): ?>
									<?php $linkParams .= ' ' . $k . '="' . $v . '"'; ?>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php $title = (!empty($link['plugin_title'])) ? ' title="'.$link['plugin_title'].'"' : ''; ?>
							<a rel="nofollow" class="btn btn-primary btn-block btn-lg link<?php echo $link['class'];?>" <?php echo $linkParams.$title;?> href="<?php echo JRoute::_($link['link']);?>">
									<span class="<?php echo $link['class'];?> slogin-ico">&nbsp;</span>
									<span class="text-socbtn"><?php echo $link['plugin_title'];?></span>
							</a>
						<?php endforeach; ?>
						<br>
						<b style="text-align: center;display: block;">OR</b>
						<hr>
					<?php endif; ?>
				</div>
				<div class="slogin-clear"></div>


<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
	<fieldset class="userdata">
	<p id="form-login-username">
		<label for="modlgn-username"><?php echo JText::_('MOD_ADDARTICLE_VALUE_USERNAME') ?></label>
		<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
	</p>
	<p id="form-login-password">
		<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
		<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
	</p>
	<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
            <p id="form-login-remember">
				 <label for="modlgn-remember">
				  	<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
				  	<?php echo JText::_('MOD_ADDARTICLE_REMEMBER_ME') ?>
				 </label>
			</p>
		<div class="slogin-clear"></div>
	<?php endif; ?>
	<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	</fieldset>
	<ul class="ul-jlslogin">
		<li>
			<a  rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('MOD_ADDARTICLE_FORGOT_YOUR_PASSWORD'); ?></a>
		</li>
		<li>
			<a  rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('MOD_ADDARTICLE_FORGOT_YOUR_USERNAME'); ?></a>
		</li>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a  rel="nofollow" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
				<?php echo JText::_('MOD_ADDARTICLE_REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>



			</div>
		</div>
	</div>
</div>