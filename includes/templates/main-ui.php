<div class="caldera-affiliates-main-headercaldera">
		<h2>
		<span id="caldera_affiliates-name-title">{{name}}</span> <span class="caldera-affiliates-subline">{{slug}}</span>
		<span class="add-new-h2 wp-baldrick" data-action="caldera_affiliates_save_config" data-load-element="#caldera-affiliates-save-indicator" data-before="caldera_affiliates_get_config_object" ><?php _e('Save Changes', 'caldera-affiliates') ; ?></span>
		<span style="position: absolute; margin-left: -18px;" id="caldera-affiliates-save-indicator"><span style="float: none; margin: 16px 0px -5px 10px;" class="spinner"></span></span>
	</h2>
		<ul class="caldera-affiliates-header-tabs caldera-affiliates-nav-tabs">
				
		
				
	</ul>
	<span class="wp-baldrick" id="caldera-affiliates-field-sync" data-event="refresh" data-target="#caldera-affiliates-main-canvas" data-callback="caldera_affiliates_canvas_init" data-type="json" data-request="#caldera-affiliates-live-config" data-template="#main-ui-template"></span>
</div>
<div class="caldera-affiliates-sub-headercaldera">
	<ul class="caldera-affiliates-sub-tabs caldera-affiliates-nav-tabs">
				<li class="{{#is _current_tab value="#caldera-affiliates-panel-general"}}active {{/is}}caldera-affiliates-nav-tab"><a href="#caldera-affiliates-panel-general"><?php _e('Affiliate Group', 'caldera-affiliates') ; ?></a></li>
		<li class="{{#is _current_tab value="#caldera-affiliates-panel-links"}}active {{/is}}caldera-affiliates-nav-tab"><a href="#caldera-affiliates-panel-links"><?php _e('Links', 'caldera-affiliates') ; ?></a></li>

	</ul>
</div>

<form id="caldera-affiliates-main-form" action="?page=caldera_affiliates" method="POST">
	<?php wp_nonce_field( 'caldera-affiliates', 'caldera-affiliates-setup' ); ?>
	<input type="hidden" value="{{id}}" name="id" id="caldera_affiliates-id">
	<input type="hidden" value="{{_current_tab}}" name="_current_tab" id="caldera-affiliates-active-tab">

		<div id="caldera-affiliates-panel-general" class="caldera-affiliates-editor-panel" {{#if _current_tab}}{{#is _current_tab value="#caldera-affiliates-panel-general"}}{{else}} style="display:none;" {{/is}}{{/if}}>
		<h4><?php _e( 'Add a new group of links', 'caldera-affiliates' ); ?> <small class="description"><?php _e( 'Affiliate Group', 'caldera-affiliates' ); ?></small></h4>
		<?php
		// pull in the general settings template
		include CALDERA_AFFILIATES_PATH . 'includes/templates/general-settings.php';
		?>
	</div>	<div id="caldera-affiliates-panel-links" class="caldera-affiliates-editor-panel" {{#is _current_tab value="#caldera-affiliates-panel-links"}}{{else}} style="display:none;" {{/is}}>		
		<h4><?php _e('Set up the names and links for your affiliates ', 'caldera-affiliates') ; ?> <small class="description"><?php _e('Links', 'caldera-affiliates') ; ?></small></h4>
		<?php
		// pull in the general settings template
		include CALDERA_AFFILIATES_PATH . 'includes/templates/links-panel.php';
		?>
	</div>

		

</form>

{{#unless _current_tab}}
	{{#script}}
		jQuery(function($){
			$('.caldera-affiliates-nav-tab').first().find('a').trigger('click');
		});
	{{/script}}
{{/unless}}