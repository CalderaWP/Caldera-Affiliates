var caldera_affiliates_canvas = false,
	caldera_affiliates_get_config_object,
	caldera_affiliates_record_change,
	caldera_affiliates_canvas_init,
	caldera_affiliates_get_default_setting,
	caldera_affiliates_code_editor,
	init_magic_tags,
	caldera_affiliates_rebuild_magics,
	config_object = {},
	magic_tags = [];

jQuery( function($){

	init_magic_tags = function(){
		//init magic tags
		var magicfields = jQuery('.magic-tag-enabled');

		magicfields.each(function(k,v){
			var input = jQuery(v);
			
			if(input.hasClass('magic-tag-init-bound')){
				var currentwrapper = input.parent().find('.magic-tag-init');
				if(!input.is(':visible')){
					currentwrapper.hide();
				}else{
					currentwrapper.show();
				}
				return;			
			}
			var magictag = jQuery('<span class="dashicons dashicons-editor-code magic-tag-init"></span>'),
				wrapper = jQuery('<span style="position:relative;display:inline-block; width:100%;"></span>');

			if(input.is('input')){
				magictag.css('borderBottom', 'none');
			}

			if(input.hasClass('caldera-affiliates-conditional-value-field')){
				wrapper.width('auto');
			}

			//input.wrap(wrapper);
			magictag.insertAfter(input);
			input.addClass('magic-tag-init-bound');
			if(!input.is(':visible')){
				magictag.hide();
			}else{
				magictag.show();
			}
		});

	}

	// internal function declarationas
	caldera_affiliates_get_config_object = function(el){
		// new sync first
		$('#caldera_affiliates-id').trigger('change');
		var clicked 	= $(el),
			config 		= $('#caldera-affiliates-live-config').val(),
			required 	= $('[required]'),
			clean		= true;

		for( var input = 0; input < required.length; input++ ){
			if( required[input].value.length <= 0 && $( required[input] ).is(':visible') ){
				$( required[input] ).addClass('caldera-affiliates-input-error');
				clean = false;
			}else{
				$( required[input] ).removeClass('caldera-affiliates-input-error');
			}
		}
		if( clean ){
			caldera_affiliates_canvas = config;
		}
		clicked.data( 'config', config );
		return clean;
	}

	caldera_affiliates_record_change = function(){
		// hook and rebuild the fields list
		jQuery(document).trigger('record_change');
		jQuery('#caldera_affiliates-id').trigger('change');
		jQuery('#caldera-affiliates-field-sync').trigger('refresh');
	}
	
	caldera_affiliates_canvas_init = function(){

		if( !caldera_affiliates_canvas ){
			// bind changes
			jQuery('#caldera-affiliates-main-canvas').on('keydown keyup change','input, select, textarea', function(e) {
				config_object = jQuery('#caldera-affiliates-main-form').formJSON(); // perhaps load into memory to keep it live.
				jQuery('#caldera-affiliates-live-config').val( JSON.stringify( config_object ) ).trigger('change');
			});
			// bind editor
			caldera_affiliates_init_editor();
			caldera_affiliates_canvas = jQuery('#caldera-affiliates-live-config').val();
			config_object = JSON.parse( caldera_affiliates_canvas ); // perhaps load into memory to keep it live.
		}
		if( $('.color-field').length ){
			$('.color-field').wpColorPicker({
				change: function(obj){
					$('#caldera_affiliates-id').trigger('change');
				}
			});
		}
		if( $('.caldera-affiliates-group-wrapper').length ){
			$( ".caldera-affiliates-group-wrapper" ).sortable({
				handle: ".dashicons-sort",
				update: function(){
					jQuery('#caldera_affiliates-id').trigger('change');
				}
			});
			$( ".caldera-affiliates-fields-list" ).sortable({
				handle: ".dashicons-sort",
				update: function(){
					jQuery('#caldera_affiliates-id').trigger('change');
				}
			});
		}
		// live change init
		$('[data-init-change]').trigger('change');
		// rebuild tags
		caldera_affiliates_rebuild_magics();
		jQuery(document).trigger('canvas_init');
	}
	caldera_affiliates_get_default_setting = function(obj){

		var id = 'node_' + Math.round(Math.random() * 99887766) + '_' + Math.round(Math.random() * 99887766),
			new_object = {},
			//config_object = JSON.parse( jQuery('#caldera-affiliates-live-config').val() ), // perhaps load into memory to keep it live.
			trigger = ( obj.trigger ? obj.trigger : obj.params.trigger ),
			sub_id = ( trigger.data('group') ? trigger.data('group') : 'node_' + Math.round(Math.random() * 99887766) + '_' + Math.round(Math.random() * 99887766) ),
			nodes;

		
		// add simple node
		if( trigger.data('addNode') ){
			// new node? add one
			var newnode = { "_id" : id };

			nodes = trigger.data('addNode').split('.');
			
			for( var n = nodes.length-1; n >= 0; n--){
				if( n > 0 ){
					var newobj = newnode,
						nid = 'node_' + Math.round(Math.random() * 99887766) + '_' + Math.round(Math.random() * 99887766);

					newnode = {"_id" : n > 1 ? nid : id };
					newnode[nodes[n]] 			= {};
					newnode[nodes[n]][nid] 		= newobj;
					newnode[nodes[n]][nid]._id 	= nid;

				}else{

					if( !config_object[nodes[n]] ){
						config_object[nodes[n]] = {};
					}
					config_object[nodes[n]][id] = newnode;
				}

			}

		}
		// remove simple node (all)
		if( trigger.data('removeNode') ){
			// new node? add one
			if( config_object[trigger.data('removeNode')] ){
				delete config_object[trigger.data('removeNode')];
			}

		}



		switch( trigger.data('script') ){
			case "add-to-object":
				// add to core object
				//config_object.entry_name = obj.data.value; // ajax method

				break;
			case "add-field-node":
				// add to core object
				if( !config_object[trigger.data('slug')][trigger.data('group')].field ){
					config_object[trigger.data('slug')][trigger.data('group')].field = {};
				}
				config_object[trigger.data('slug')][trigger.data('group')].field[id] = { "_id": id, 'name': 'new field', 'slug': 'new_field' };
				config_object.open_field = id;
				break;				
		}

		jQuery('#caldera-affiliates-live-config').val( JSON.stringify( config_object ) );
		jQuery('#caldera-affiliates-field-sync').trigger('refresh');
	}
	// sutocomplete category
	$.widget( "custom.catcomplete", $.ui.autocomplete, {
		_create: function() {
			this._super();
			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
		},
		_renderMenu: function( ul, items ) {
			var that = this,
			currentCategory = "";
			$.each( items, function( index, item ) {
				var li;
				if ( item.category != currentCategory ) {
					ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
					currentCategory = item.category;
				}
				li = that._renderItemData( ul, item );
				if ( item.category ) {
					li.attr( "aria-label", item.category + " : " + item.label );
				}
			});
		}
	});
	caldera_affiliates_rebuild_magics = function(){

		function split( val ) {
			return val.split( / \s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}
		$( ".magic-tag-enabled" ).bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB && $( this ).catcomplete( "instance" ).menu.active ) {
				event.preventDefault();
			}
		}).catcomplete({
			minLength: 0,
			source: function( request, response ) {
				// delegate back to autocomplete, but extract the last term
				magic_tags = [];
				var category = '';
				// Search form fields
				if( config_object.search_form && config_object.form_fields ){
					// set internal tags
					var system_tags = [
						'autocomplete_item',
					];					
					category = $('#caldera-affiliates-label-tags').text();
					for( f = 0; f < system_tags.length; f++ ){
						magic_tags.push( { label: '{' + system_tags[f] + '}', category: category }  );
					}							
				}
				
				response( $.ui.autocomplete.filter( magic_tags, extractLast( request.term ) ) );
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				//terms.push( "" );
				this.value = terms.join( " " );
				return false;
			}
		});
	}	

	// trash 
	$(document).on('click', '.caldera-affiliates-card-actions .confirm a', function(e){
		e.preventDefault();
		var parent = $(this).closest('.caldera-affiliates-card-content');
			actions = parent.find('.row-actions');

		actions.slideToggle(300);
	});

	// bind slugs
	$(document).on('keyup change', '[data-format="slug"]', function(e){

		var input = $(this);

		if( input.data('master') && input.prop('required') && this.value.length <= 0 && e.type === "change" ){
			this.value = $(input.data('master')).val().replace(/[^a-z0-9]/gi, '_').toLowerCase();
			if( this.value.length ){
				input.trigger('change');
			}
			return;
		}

		this.value = this.value.replace(/[^a-z0-9]/gi, '_').toLowerCase();
	});
	
	// bind label update
	$(document).on('keyup change', '[data-sync]', function(){
		var input = $(this),
			syncs = $(input.data('sync'));
		
		syncs.each(function(){
			var sync = $(this);

			if( sync.is('input') ){
				sync.val( input.val() ).trigger('change');
			}else{
				sync.text(input.val());
			}
		});
	});
	// bind toggles
	$(document).on('click', '[data-toggle]', function(){
		
		var toggle = $(this).data('toggle'),
			target = $(toggle);
		
		target.each(function(){
			var tog = $(this);
			if( tog.is(':checkbox') || tog.is(':radio') ){
				if( tog.prop('checked') ){
					tog.prop('checked', false);
				}else{
					tog.prop('checked', true);
				}
				caldera_affiliates_record_change();
			}else{
				tog.toggle();
			}
		});

	});	

	// bind tabs
	$(document).on('click', '.caldera-affiliates-nav-tabs a', function(e){
		
		e.preventDefault();
		var clicked 	= $(this),
			tab_id 		= clicked.attr('href'),
			required 	= $('[required]'),
			clean		= true;

		for( var input = 0; input < required.length; input++ ){
			if( required[input].value.length <= 0 && $( required[input] ).is(':visible') ){
				$( required[input] ).addClass('caldera-affiliates-input-error');
				clean = false;
			}else{
				$( required[input] ).removeClass('caldera-affiliates-input-error');
			}
		}
		if( !clean ){
			return;
		}
		$('.caldera-affiliates-nav-tabs .current').removeClass('current');
		$('.caldera-affiliates-nav-tabs .active').removeClass('active');
		$('.caldera-affiliates-nav-tabs .nav-tab-active').removeClass('nav-tab-active');
		if( clicked.parent().is('li') ){
			clicked.parent().addClass('active');			
		}else if( clicked.parent().is('div') ){
			clicked.addClass('current');			
		}else{			
			clicked.addClass('nav-tab-active');
		}
		

		$('.caldera-affiliates-editor-panel').hide();
		$( tab_id ).show();
		
		if( caldera_affiliates_code_editor ){
			caldera_affiliates_code_editor.toTextArea();
			caldera_affiliates_code_editor = false;
		}

		if( $( tab_id ).find('.caldera-affiliates-code-editor').length ){

			caldera_affiliates_init_editor( $( tab_id ).find('.caldera-affiliates-code-editor').prop('id') );
			caldera_affiliates_code_editor.refresh();
			caldera_affiliates_code_editor.focus();
		}

		jQuery('#caldera-affiliates-active-tab').val(tab_id).trigger('change');

	});

	// row remover global neeto
	$(document).on('click', '[data-remove-parent]', function(e){
		var clicked = $(this),
			parent = clicked.closest(clicked.data('removeParent'));
		if( clicked.data('confirm') ){
			if( !confirm(clicked.data('confirm')) ){
				return;
			}
		}
		parent.remove();
		caldera_affiliates_record_change();
	});
	
	// init tags
	$('body').on('click', '.magic-tag-init', function(e){
		var clicked = $(this),
			input = clicked.prev();

		input.focus().trigger('init.magic');

	});
	
	// initialize live sync rebuild
	$(document).on('change', '[data-live-sync]', function(e){
		caldera_affiliates_record_change();
	});

	// initialise baldrick triggers
	$('.wp-baldrick').baldrick({
		request     : ajaxurl,
		method      : 'POST'
	});


	window.onbeforeunload = function(e) {

		if( caldera_affiliates_canvas && caldera_affiliates_canvas !== jQuery('#caldera-affiliates-live-config').val() ){
			return true;
		}
	};


});







function caldera_affiliates_init_editor(el){
	if( !jQuery('#' + el).length ){
		return;
	}	
	// custom modes
	var mustache = function(caldera_affiliates, state) {

		var ch;

		if (caldera_affiliates.match("{{")) {
			while ((ch = caldera_affiliates.next()) != null){
				if (ch == "}" && caldera_affiliates.next() == "}") break;
			}
			caldera_affiliates.eat("}");
			return "mustache";
		}
		/*
		if (caldera_affiliates.match("{")) {
			while ((ch = caldera_affiliates.next()) != null)
				if (ch == "}") break;
			caldera_affiliates.eat("}");
			return "mustacheinternal";
		}*/
		if (caldera_affiliates.match("%")) {
			while ((ch = caldera_affiliates.next()) != null)
				if (ch == "%") break;
			caldera_affiliates.eat("%");
			return "command";
		}

		/*
		if (caldera_affiliates.match("[[")) {
			while ((ch = caldera_affiliates.next()) != null)
				if (ch == "]" && caldera_affiliates.next() == "]") break;
			caldera_affiliates.eat("]");
			return "include";
		}*/
		while (caldera_affiliates.next() != null && 
			//!caldera_affiliates.match("{", false) && 
			!caldera_affiliates.match("{{", false) && 
			!caldera_affiliates.match("%", false) ) {}
			return null;
	};

	var options = {
		lineNumbers: true,
		matchBrackets: true,
		tabSize: 2,
		indentUnit: 2,
		indentWithTabs: true,
		enterMode: "keep",
		tabMode: "shift",
		lineWrapping: true,
		extraKeys: {"Ctrl-Space": "autocomplete"},
		};
	// base mode

	CodeMirror.defineMode("mustache", function(config, parserConfig) {
		var mustacheOverlay = {
			token: mustache
		};
		return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || 'text/html' ), mustacheOverlay);
	});
	options.mode = jQuery('#' + el).data('mode') ? jQuery('#' + el).data('mode') : "mustache";

	caldera_affiliates_code_editor = CodeMirror.fromTextArea(document.getElementById(el), options);
	caldera_affiliates_code_editor.on('keyup', tagFields);
	caldera_affiliates_code_editor.on('blur', function(cm){
		cm.save();
		jQuery( cm.getInputField() ).trigger('change');
	});

	return caldera_affiliates_code_editor;

}
(function() {
	"use strict";

	if( typeof CodeMirror === 'undefined' || caldera_affiliates_canvas === false ){
		return;
	}

	var Pos         = CodeMirror.Pos;

	function getFields(cm, options) {

		var cur = cm.getCursor(), token = cm.getTokenAt(cur),
		result = [],
		fields = options.fields;

		if( cm.getMode().name === 'sqlmustache' ){
			options.mode = 'sqlmustache';
		}
		switch (options.mode){
			case 'mustache':
			var wrap = {start: "{{", end: "}}"},
			prefix = token.string.slice(2);
			break;
			case 'command':
			var wrap = {start: "%", end: "%"},
			prefix = token.string.slice(1);
			break;
			default:
			var wrap = {start: "", end: "}}"},
			prefix = token.string;
			break;
		}
		for( var field in fields){			
			if (field.indexOf(prefix) == 0 || prefix === '{' || fields[field].indexOf(prefix) == 0){
				if(prefix === '{'){
					wrap.start = '{';
				}
				result.push({text: wrap.start + field + wrap.end, displayText: fields[field]});
			}
		};

		return {
			list: result,
			from: Pos(cur.line, token.start),
			to: Pos(cur.line, token.end)
		};
	}
	CodeMirror.registerHelper("hint", "elementfield", getFields);
})();

function find_if_in_wrapper( open_entry, close_entry, cm ){
	in_entry = false;
	if( open_entry.findPrevious() ){
		


		// is entry. check if closed
		var open_pos  = open_entry.from();

		if( close_entry.findPrevious() ){
			// if closed after open then not in			
			var close_pos = close_entry.from();
			if( open_pos.line > close_pos.line ){
				// open is after close - on entry				
				in_entry = open_pos
			}else if( open_pos.line === close_pos.line ){
				// smame line - what point?
				if( open_pos.ch > close_pos.ch ){
					//after close - in entry
					in_entry = open_pos;
				}
			}else{
				
				open_entry 	= cm.getSearchCursor('{{#each ', open_pos);

				return find_if_in_wrapper( open_entry, close_entry, cm )
			}

		}else{
			
			in_entry = open_pos;
		}

	}

	// set the parent
	if( in_entry ){
		// find what tag is open
		var close_tag 	= cm.getSearchCursor( '}}', in_entry );
		if( close_tag.findNext() ){
			var close_pos	= close_tag.from();
				start_tag	= open_entry.to();
			
			in_entry = cm.getRange( start_tag, close_pos );

		}

	}

	return in_entry;
}

function tagFields(cm, e) {
	if( e.keyCode === 8 ){
		return; // no backspace.
	}
	//console.log( cm );
	var cur = cm.getCursor();

	// test search 
	var open_entry 	= cm.getSearchCursor('{{#each ', cur);
	var close_entry = cm.getSearchCursor('{{/each}}', cur);
	var open_if 	= cm.getSearchCursor('{{#if ', cur);
	var close_if 	= cm.getSearchCursor('{{/if', cur);	

	var in_entry 	= find_if_in_wrapper( open_entry, close_entry, cm );
	var in_if 		= false;





	if( open_if.findPrevious() ){
		// is if. check if closed
		var open_pos  = open_if.from();

		if( close_if.findPrevious() ){
			// if closed after open then not in			
			var close_pos = close_if.from();
			if( open_pos.line > close_pos.line ){
				// open is after close - on if
				in_if = true
			}else if( open_pos.line === close_pos.line ){
				// smame line - what point?
				if( open_pos.ch > close_pos.ch ){
					//after close - in if
					in_if = true;
				}
			}

		}else{
			in_if = true;
		}
	}

	if( in_if ){
		// find what tag is open
		var close_tag 	= cm.getSearchCursor( '}}', open_pos );
		if( close_tag.findNext() ){
			var close_pos	= close_tag.from();
				start_tag	= open_entry.to();
			
			in_if = cm.getRange( start_tag, close_pos );

		}

	}

	if (!cm.state.completionActive || e.keyCode === 18){
		var token = cm.getTokenAt(cur), prefix,
		prefix = token.string.slice(0);
		if(prefix){
			if(token.type){
				var fields = {};
				//console.log( token );
				if( token.type ){
					// only show fields within the entry
					if( in_entry ){
						
						if( !in_if ){
							// dont allow closing #each if in if
							fields = {
								"/each"			:	"/each"
							};
						}

						// ADD INDEX KEY
						fields['@key'] = "@key";

						jQuery('.caldera-affiliates-autocomplete-in-entry-' + token.type).each(function(){
							var field = jQuery(this);

							if( !field.hasClass('parent-' + in_entry) && !field.hasClass('parant-all') ){
								return;
							}

							fields[field.data('slug')] = field.data('label');
							//fields["#each " + field.data('slug')] = "#each " + field.data('label');
							//if( !in_if ){
								if( field.data('label').indexOf('#') < 0 ){
									fields["#if " + field.data('slug')] = "#if " + field.data('label');
								}
							//}
							//fields["#unless " + field.data('slug')] = "#unless " + field.data('label');
						});
					}else{

						jQuery('.caldera-affiliates-autocomplete-out-entry-' + token.type).each(function(){
							var field = jQuery(this);
							fields[field.data('slug')] = field.data('label');
							//fields["#each " + field.data('slug')] = "#each " + field.data('label');
							//if( !in_if ){
								if( field.data('label').indexOf('#') < 0 ){
									fields["#if " + field.data('slug')] = "#if " + field.data('label');
								}
							//}
							//fields["#unless " + field.data('slug')] = "#unless " + field.data('label');
						});

					}

					if( in_if ){
						fields['else'] = 'else';
						fields['/if'] = '/if';
					}
				}
				// sort hack
				var keys = [];
				var commands = [];
				var sorted_obj = {};

				for(var key in fields){
				    if(fields.hasOwnProperty(key)){
				    	if( key.indexOf('#') < 0 && key.indexOf('/') < 0 ){
				        	keys.push(key);
				    	}else{
				    		commands.push(key);
				    	}
				    }
				}

				// sort keys
				keys.sort();
				commands.sort();
				keys = keys.concat(commands);
				// create new array based on Sorted Keys
				jQuery.each(keys, function(i, key){
				    sorted_obj[key] = fields[key];
				});
				CodeMirror.showHint(cm, CodeMirror.hint.elementfield, {fields: sorted_obj, mode: token.type});

			}
		}
	}
	return;
}