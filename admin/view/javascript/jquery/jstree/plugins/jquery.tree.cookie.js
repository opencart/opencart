(function ($) {
	if(typeof $.cookie == "undefined") throw "jsTree cookie: jQuery cookie plugin not included.";

	$.extend($.tree.plugins, {
		"cookie" : {
			defaults : {
				prefix		: "",	// a prefix that will be used for all cookies for this tree
				options		: { 
					expires: false, 
					path: false, 
					domain: false, 
					secure: false 
				},
				types : {
					selected	: true,		// should we set the selected cookie
					open		: true		// should we set the open cookie
				},
				keep_selected	: false,	// should we merge with the selected option or overwrite it
				keep_opened		: false		// should we merge with the opened option or overwrite it
			},
			set_cookie : function (type) {
				var opts = $.extend(true, {}, $.tree.plugins.cookie.defaults, this.settings.plugins.cookie);
				if(opts.types[type] !== true) return false;
				switch(type) {
					case "selected":
						if(this.settings.rules.multiple != false && this.selected_arr.length > 1) {
							var val = Array();
							$.each(this.selected_arr, function () {
								if(this.attr("id")) { val.push(this.attr("id")); }
							});
							val = val.join(",");
						}
						else var val = this.selected ? this.selected.attr("id") : false;
						$.cookie(opts.prefix + 'selected', val, opts.options);
						break;
					case "open":
						var str = "";
						this.container.find("li.open").each(function (i) { if(this.id) { str += this.id + ","; } });
						$.cookie(opts.prefix + 'open', str.replace(/,$/ig,""), opts.options);
						break;
				}
			},
			callbacks : {
				oninit : function (t) {
					var opts = $.extend(true, {}, $.tree.plugins.cookie.defaults, this.settings.plugins.cookie);
					var tmp = false;
					tmp = $.cookie(opts.prefix + 'open');
					if(tmp) {
						tmp = tmp.split(",");
						if(opts.keep_opened)	this.settings.opened = $.unique($.merge(tmp, this.settings.opened));
						else					this.settings.opened = tmp;
					}
					tmp = $.cookie(opts.prefix + 'selected');
					if(tmp) {
						tmp = tmp.split(",");
						if(opts.keep_selected)	this.settings.selected = $.unique($.merge(tmp, this.settings.opened));
						else					this.settings.selected = tmp;
					}
				},
				onchange	: function() { $.tree.plugins.cookie.set_cookie.apply(this, ["selected"]); },
				onopen		: function() { $.tree.plugins.cookie.set_cookie.apply(this, ["open"]); },
				onclose		: function() { $.tree.plugins.cookie.set_cookie.apply(this, ["open"]); },
				ondelete	: function() { $.tree.plugins.cookie.set_cookie.apply(this, ["open"]); },
				oncopy		: function() { $.tree.plugins.cookie.set_cookie.apply(this, ["open"]); },
				oncreate	: function() { $.tree.plugins.cookie.set_cookie.apply(this, ["open"]); },
				onmoved		: function() { $.tree.plugins.cookie.set_cookie.apply(this, ["open"]); }
			}
		}
	});
})(jQuery);