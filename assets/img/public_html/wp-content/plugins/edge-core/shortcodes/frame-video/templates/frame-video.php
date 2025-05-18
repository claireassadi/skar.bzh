<div class="edge-frame-video-holder">

	<?php $videoHolder = get_template_directory_uri() . "/assets/img/laptop_video.png"; ?>

	<div class="edge-frame-image-holder">
		<img src="<?php echo $videoHolder; ?>" alt="" />
	</div>

	<video width="725" autoplay loop>
	  <source src="<?php echo esc_url($video_link); ?>" type="video/mp4">
	</video>
</div>