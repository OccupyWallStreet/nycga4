<?php function get_pro(){ 

	if( defined('is_pro') && current_user_can('edit_theme_options')): 	
		return; 

	else: ?>
		 <div id="cap_getpro">
			<div class="getpro_content">
				<br>
		    	<br>
		    	<h1>Custom Community 2.0 Premium Pack - Pre-order Special</h1>
		    	<h3 style="font-weight: normal;">Get 50% discount when you pre-order!</h3>
		    	<a href="http://themekraft.com/store/custom-community-2-premium-pack/" style="font-size: 18px; padding: 10px 25px; line-height: 100%; height: auto;" class="button button-primary" target="_new">Check the special deal here</a>
		    	<br><br><br><br>
			</div>
		</div>
	    <div class="spacer"></div><?php 
	endif; 
} ?>