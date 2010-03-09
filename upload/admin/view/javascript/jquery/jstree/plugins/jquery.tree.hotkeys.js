(function ($) {
	if(typeof window.hotkeys == "undefined") throw "jsTree hotkeys: jQuery hotkeys plugin not included.";

	$.extend($.tree.plugins, {
		"hotkeys" : {
			bound : [],
			disabled : false,
			defaults : {
				hover_mode : false,
				functions : {
					"up"	: function () { $.tree.plugins.hotkeys.get_prev.apply(this); return false; },
					"down"	: function () { $.tree.plugins.hotkeys.get_next.apply(this); return false; },
					"left"	: function () { $.tree.plugins.hotkeys.get_left.apply(this); return false; },
					"right"	: function () { $.tree.plugins.hotkeys.get_right.apply(this); return false; },
					"f2"	: function () { if(this.selected) this.rename(); return false; },
					"del"	: function () { if(this.selected) this.remove(); return false; },
					"ctrl+c": function () { if(this.selected) this.copy(); return false; },
					"ctrl+x": function () { if(this.selected) this.cut(); return false; },
					"ctrl+v": function () { if(this.selected) this.paste(); return false; }
				}
			},
			exec : function(key) {
				if($.tree.plugins.hotkeys.disabled) return false;

				var t = $.tree.focused();
				if(typeof t.settings.plugins.hotkeys == "undefined") return;
				var opts = $.extend(true, {}, $.tree.plugins.hotkeys.defaults, t.settings.plugins.hotkeys);
				if(typeof opts.functions[key] == "function") return opts.functions[key].apply(t);
			},
			get_next : function() {
				var opts = $.extend(true, {}, $.tree.plugins.hotkeys.defaults, this.settings.plugins.hotkeys);
				var obj = this.hovered || this.selected;
				return opts.hover_mode ? this.hover_branch(this.next(obj)) : this.select_branch(this.next(obj));
			},
			get_prev : function() {
				var opts = $.extend(true, {}, $.tree.plugins.hotkeys.defaults, this.settings.plugins.hotkeys);
				var obj = this.hovered || this.selected;
				return opts.hover_mode ? this.hover_branch(this.prev(obj)) : this.select_branch(this.prev(obj));
			},
			get_left : function() {
				var opts = $.extend(true, {}, $.tree.plugins.hotkeys.defaults, this.settings.plugins.hotkeys);
				var obj = this.hovered || this.selected;
				if(obj) {
					if(obj.hasClass("open"))	this.close_branch(obj);
					else {
						return opts.hover_mode ? this.hover_branch(this.parent(obj)) : this.select_branch(this.parent(obj));
					}
				}
			},
			get_right : function() {
				var opts = $.extend(true, {}, $.tree.plugins.hotkeys.defaults, this.settings.plugins.hotkeys);
				var obj = this.hovered || this.selected;
				if(obj) {
					if(obj.hasClass("closed"))	this.open_branch(obj);
					else {
						return opts.hover_mode ? this.hover_branch(obj.find("li:eq(0)")) : this.select_branch(obj.find("li:eq(0)"));
					}
				}
			},

			callbacks : {
				oninit : function (t) {
					var opts = $.extend(true, {}, $.tree.plugins.hotkeys.defaults, this.settings.plugins.hotkeys);
					for(var i in opts.functions) {
						if(opts.functions.hasOwnProperty(i) && $.inArray(i, $.tree.plugins.hotkeys.bound) == -1) {
							(function (k) {
								$(document).bind("keydown", { combi : k, disableInInput: true }, function (event) {
									return $.tree.plugins.hotkeys.exec(k);
								});
							})(i);
							$.tree.plugins.hotkeys.bound.push(i);
						}
					}
				}
			}
		}
	});
})(jQuery);