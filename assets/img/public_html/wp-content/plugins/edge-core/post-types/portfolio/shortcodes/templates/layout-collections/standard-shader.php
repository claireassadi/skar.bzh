<?php echo edge_core_get_cpt_shortcode_module_template_part('portfolio', 'parts/image', $item_style, $params); ?>

<?php if ($enable_title === 'yes' || $enable_category === 'yes' || $enable_excerpt === 'yes') { ?>
<div class="edge-pli-text-holder">
	<div class="edge-pli-text-wrapper">
		<div class="edge-pli-text">
            <?php echo edge_core_get_cpt_shortcode_module_template_part('portfolio', 'parts/category', 'slider',$item_style, $params); ?>

            <?php echo edge_core_get_cpt_shortcode_module_template_part('portfolio', 'parts/title','slider', $item_style, $params); ?>
			
			<?php echo edge_core_get_cpt_shortcode_module_template_part('portfolio', 'parts/excerpt', $item_style, $params); ?>
		</div>
	</div>
</div>
<?php } ?>