<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<style type="text/css">
  @font-face {
    font-family:<?php echo $custom_data["font_family"];?>;
    src:url(<?php echo Button_URL . "/css/wd_font_awesome/fonts/fontawesome-webfont.woff" ?>) format("woff");
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.10/webfont.js"></script>
<script type="text/javascript">
WebFont.load({
	google: {families:['<?php echo $custom_data["font_family"];?>']} 
})
</script>



<?php 


 ?>