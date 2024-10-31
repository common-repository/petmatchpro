	<?php
	$modalVideoCSS = get_home_url() . '/wp-content/plugins/' . constant('PLUGIN_DIR') . '/' . constant('PUBLIC_DIR') . '/' . constant('VIDEO_DIR') . '/' . constant('CSS_DIR') . '/modal-video.min.css';
	//echo 'Modal CSS = ' . $modalVideoCSS . '<br>';
	$modalVideoJS = get_home_url() . '/wp-content/plugins/' . constant('PLUGIN_DIR') . '/' . constant('PUBLIC_DIR') . '/' . constant('VIDEO_DIR') . '/' . constant('SCRIPT_DIR') . '/jquery-modal-video.min.js';
	//echo 'Modal JS = ' . $modalVideoJS . '<br>';
	?>

	<link rel="stylesheet" href="<?php echo $modalVideoCSS?>">
	<script src="<?php echo $modalVideoJS?>"></script>

    <?php echo '<script>
		jQuery(document).one("ready",function(){
		jQuery(".pmp-detail-video-play-icon").modalVideo({
			youtube:{
			controls:0,
			nocookie: true
			},
     	 url:"'.$clickURL.'?rel=1&autoplay=1"
		 });});
	</script>';?>	