<?php 
defined('_JEXEC') or die;
?>
<div id="mod_addarticle_<?php echo $moduleid; ?>_popup" class="mod_addarticle_popup modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Article add</h4>
			</div>
			<div class="modal-body">Loading...</div>
		</div>
	</div>
</div>
<script>
jQuery&&jQuery(document).ready(function(){
	jQuery&&jQuery('.mod_addarticle_popup').bind('shown', function() {
		console.log('Window is shown!');
	//    tinyMCE.execCommand('mceAddControl', false, 'articletext');
		jQuery('.modal-content').css('height',jQuery('.item-page').height());
		jQuery('.jform_articletext_ifr').css('height',150);
		});

jQuery&&jQuery('.mod_addarticle_popup').bind('hide', function() {
    tinyMCE.execCommand('mceRemoveControl', false, 'articletext');
});

});

</script>