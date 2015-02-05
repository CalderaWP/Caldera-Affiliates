		<div class="caldera-affiliates-config-group">
			<label for="caldera_affiliates-name"><?php _e( 'Caldera Affliates Name', 'caldera-affiliates' ); ?></label>
			<input type="text" name="name" value="{{name}}" data-sync="#caldera_affiliates-name-title" id="caldera_affiliates-name" required>
		</div>
		<div class="caldera-affiliates-config-group">
			<label for="caldera_affiliates-slug"><?php _e( 'Caldera Affliates Slug', 'caldera-affiliates' ); ?></label>
			<input type="text" name="slug" value="{{slug}}" data-format="slug" data-sync=".caldera-affiliates-subline" data-master="#caldera_affiliates-name" id="caldera_affiliates-slug" required>
		</div>