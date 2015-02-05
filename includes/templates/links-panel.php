	<button type="button" class="button button-small wp-baldrick" data-request="caldera_affiliates_get_default_setting" data-add-node="links"><?php _e( 'Add New', 'caldera-affiliates' ); ?></button>
	<br>
	<br>
	{{#unless links}}
		<p class="description"><?php _e( "No Items", 'caldera-affiliates' ); ?></p>
	{{/unless}}
	{{#each links}}
	<div class="node-wrapper caldera-affiliates-card-item" style="display: block; width: 550px;">
		<span style="color:#a1a1a1;" class="dashicons dashicons-admin-links caldera-affiliates-card-icon"></span>
		<div class="caldera-affiliates-card-content">
			<input id="caldera-affiliates-links-name-{{_id}}" type="hidden" name="links[{{_id}}][_id]" value="{{_id}}">
			
		<div class="caldera-affiliates-config-group">
			<label for="caldera-affiliates-links-name-{{_id}}"><?php _e( 'Name', 'caldera-affiliates' ); ?></label>
			<input id="caldera-affiliates-links-name-{{_id}}" type="text" class="regular-text" name="links[{{_id}}][name]" value="{{name}}" required="required">
			<p class="description" style="margin-left: 190px;"> This is the text, that if it appears in a post, will be wrapped in a link</p>
		</div>
		<div class="caldera-affiliates-config-group">
			<label for="caldera-affiliates-links-url-{{_id}}"><?php _e( 'URL - The Link', 'caldera-affiliates' ); ?></label>
			<input id="caldera-affiliates-links-url-{{_id}}" type="text" class="regular-text" name="links[{{_id}}][url]" value="{{url}}" required="required">
			
		</div>
		<div class="caldera-affiliates-config-group">
			<label for="caldera-affiliates-links-title_text-{{_id}}"><?php _e( 'Title Text', 'caldera-affiliates' ); ?></label>
			<input id="caldera-affiliates-links-title_text-{{_id}}" type="text" class="regular-text" name="links[{{_id}}][title_text]" value="{{title_text}}" >
			<p class="description" style="margin-left: 190px;"> Will be used in the title attribute for the link.</p>
		</div>
		</div>
		<button type="button" class="button button-small" style="padding: 0px; margin: 3px 0px; position: absolute; left: 14px; top: 6px;" data-remove-parent=".node-wrapper"><span class="dashicons dashicons-no-alt" style="padding: 0px; margin: 0px; line-height: 23px;"></span></button>
	</div>
	{{/each}}