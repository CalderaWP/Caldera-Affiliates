<?php

$caldera_affiliates = get_option( $_GET['edit'] );

?>
<div class="wrap" id="caldera-affiliates-main-canvas">
	<span class="wp-baldrick spinner" style="float: none; display: block;" data-target="#caldera-affiliates-main-canvas" data-callback="caldera_affiliates_canvas_init" data-type="json" data-request="#caldera-affiliates-live-config" data-event="click" data-template="#main-ui-template" data-autoload="true"></span>
</div>

<div class="clear"></div>

<input type="hidden" class="clear" autocomplete="off" id="caldera-affiliates-live-config" style="width:100%;" value="<?php echo esc_attr( json_encode($caldera_affiliates) ); ?>">

<script type="text/html" id="main-ui-template">
	<?php
	// pull in the join table card template
	include CALDERA_AFFILIATES_PATH . 'includes/templates/main-ui.php';
	?>	
</script>





