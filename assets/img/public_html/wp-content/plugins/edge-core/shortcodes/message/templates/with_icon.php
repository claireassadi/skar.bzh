<?php
$icon_html = adorn_edge_icon_collections()->renderIcon($icon, $icon_pack);
?>

<div class="edge-message-icon-holder">
	<div class="edge-message-icon" <?php adorn_edge_inline_style($icon_attributes); ?>>
		<div class="edge-message-icon-inner">
			<?php
				print $icon_html;
			?>			
		</div> 
	</div>	 
</div>

