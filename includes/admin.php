<div class="wrap">
	<div class="caldera-affiliates-main-headercaldera">
		<h2>
			<?php _e( 'Caldera Affiliates', 'caldera-affiliates' ); ?> <span class="caldera-affiliates-version"><?php echo CALDERA_AFFILIATES_VER; ?></span>
			<span class="add-new-h2 wp-baldrick" data-modal="new-caldera_affiliates" data-modal-height="192" data-modal-width="402" data-modal-buttons='<?php _e( 'Create Caldera Affliates', 'caldera-affiliates' ); ?>|{"data-action":"caldera_affiliates_create_caldera_affiliates","data-before":"caldera_affiliates_create_new_caldera_affiliates", "data-callback": "bds_redirect_to_caldera_affiliates"}' data-modal-title="<?php _e('New Caldera Affliates', 'caldera-affiliates') ; ?>" data-request="#new-caldera_affiliates-form"><?php _e('Add New', 'caldera-affiliates') ; ?></span>
		</h2>
	</div>

<?php

	$caldera_affiliatess = get_option('_caldera_affliates_registry');
	if( empty( $caldera_affiliatess ) ){
		$caldera_affiliatess = array();
	}
	global $wpdb;
	
	foreach( $caldera_affiliatess as $caldera_affiliates_id => $caldera_affiliates ){

?>

	<div class="caldera-affiliates-card-item" id="caldera_affiliates-<?php echo $caldera_affiliates['id']; ?>">
		<span class="dashicons dashicons-smiley caldera-affiliates-card-icon"></span>
		<div class="caldera-affiliates-card-content">
			<h4><?php echo $caldera_affiliates['name']; ?></h4>
			<div class="description"><?php echo $caldera_affiliates['slug']; ?></div>
			<div class="description">&nbsp;</div>
			<div class="caldera-affiliates-card-actions">
				<div class="row-actions">
					<span class="edit"><a href="?page=caldera_affiliates&amp;edit=<?php echo $caldera_affiliates['id']; ?>">Edit</a> | </span>
					<span class="trash confirm"><a href="?page=caldera_affiliates&amp;delete=<?php echo $caldera_affiliates['id']; ?>" data-block="<?php echo $caldera_affiliates['id']; ?>" class="submitdelete">Delete</a></span>
				</div>
				<div class="row-actions" style="display:none;">
					<span class="trash"><a class="wp-baldrick" style="cursor:pointer;" data-action="caldera_affiliates_delete_caldera_affiliates" data-callback="caldera_affiliates_remove_deleted" data-block="<?php echo $caldera_affiliates['id']; ?>" class="submitdelete">Confirm Delete</a> | </span>
					<span class="edit confirm"><a href="?page=caldera_affiliates&amp;edit=<?php echo $caldera_affiliates['id']; ?>">Cancel</a></span>
				</div>
			</div>
		</div>
	</div>

	<?php } ?>

</div>
<div class="clear"></div>
<script type="text/javascript">
	
	function caldera_affiliates_create_new_caldera_affiliates(el){
		var caldera_affiliates 	= jQuery(el),
			name 	= jQuery("#new-caldera_affiliates-name"),
			slug 	= jQuery('#new-caldera_affiliates-slug');

		if( slug.val().length === 0 ){
			name.focus();
			return false;
		}
		if( slug.val().length === 0 ){
			slug.focus();
			return false;
		}

		caldera_affiliates.data('name', name.val() ).data('slug', slug.val() ); 

	}

	function bds_redirect_to_caldera_affiliates(obj){
		
		if( obj.data.success ){

			obj.params.trigger.prop('disabled', true).html('<?php _e('Loading Caldera Affliates', 'caldera-affiliates'); ?>');
			window.location = '?page=caldera_affiliates&edit=' + obj.data.data.id;

		}else{

			jQuery('#new-block-slug').focus().select();
			
		}
	}
	function caldera_affiliates_remove_deleted(obj){

		if( obj.data.success ){
			jQuery( '#caldera_affiliates-' + obj.data.data.block ).fadeOut(function(){
				jQuery(this).remove();
			});
		}else{
			alert('<?php echo __('Sorry, something went wrong. Try again.', 'caldera-affiliates'); ?>');
		}


	}
</script>
<script type="text/html" id="new-caldera_affiliates-form">
	<div class="caldera-affiliates-config-group">
		<label><?php _e('Caldera Affliates Name', 'caldera-affiliates'); ?></label>
		<input type="text" name="name" id="new-caldera_affiliates-name" data-sync="#new-caldera_affiliates-slug" autocomplete="off">
	</div>
	<div class="caldera-affiliates-config-group">
		<label><?php _e('Caldera Affliates Slug', 'caldera-affiliates'); ?></label>
		<input type="text" name="slug" id="new-caldera_affiliates-slug" data-format="slug" autocomplete="off">
	</div>

</script>