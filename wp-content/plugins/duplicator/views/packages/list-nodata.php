<?php
	$notice01  = DUP_UI::GetViewStateValue('dup-notice01-chk');
?>

<div id='dup-list-alert-nodata'>
	<b><i class="fa fa-archive"></i> 
		<?php _e("No Packages Found.", 'wpduplicator'); ?><br/>
		<?php _e("Click the 'Create New' tab to build a package.", 'wpduplicator'); ?> <br/><br/>
	</b>
	<i>
		<?php
			printf("%s <a href='admin.php?page=duplicator-support'>%s</a> %s",
				__("Please visit the", 'wpduplicator'), 
				__("support section", 'wpduplicator'),
				__("for additional help topics", 'wpduplicator'));
		?>
	</i>
	
	<!-- NOTICE01: 0.5.0 and above -->
	<?php if(! $notice01)  :	?>
		<div id="dup-notice-01" class='dup-notice-msg'>
			<i class="fa fa-exclamation-triangle fa-lg"></i>
			<?php 
				_e("Older packages prior to 0.5.0 are no longer supported in this version.", 'wpduplicator'); 

				printf("  %s <a href='admin.php?page=duplicator-support'>%s</a> %s",
					__("To get an older package please visit the", 'wpduplicator'), 
					__("support page", 'wpduplicator'),
					__("and look for the Change Log link for additional instructions.", 'wpduplicator'));
			?><br/>
			<label for="dup-notice01-chk">
				<input type="checkbox" class="dup-notice-chk" id="dup-notice01-chk" name="dup-notice01-chk" onclick="Duplicator.UI.SaveViewStateByPost('dup-notice01-chk', 1); jQuery('#dup-notice-01').hide()" /> 
				<?php _e("Hide this message", 'wpduplicator'); ?>
			</label>
		</div><br/><br/>
	<?php else : ?>			
		<div style="height:75px">&nbsp;</div>
	<?php endif; ?>
</div>



