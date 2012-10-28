(function ($) {
	$.extend($.tree.plugins, {
		"checkbox" : {
			defaults : {
				three_state : true
			},
			get_checked : function (t) {
				if(!t) t = $.tree.focused();
				return t.container.find("a.checked").parent();
			},
			get_undeterminded : function (t) { 
				if(!t) t = $.tree.focused();
				return t.container.find("a.undetermined").parent();
			},
			get_unchecked : function (t) {
				if(!t) t = $.tree.focused();
				return t.container.find("a:not(.checked, .undetermined)").parent();
			},

			check : function (n) {
				if(!n) return false;
				var t = $.tree.reference(n);
				n = t.get_node(n);
				if(n.children("a").hasClass("checked")) return true;

				var opts = $.extend(true, {}, $.tree.plugins.checkbox.defaults, t.settings.plugins.checkbox);
				if(opts.three_state) {
					n.find("li").andSelf().children("a").removeClass("unchecked undetermined").addClass("checked");
					n.parents("li").each(function () { 
						if($(this).children("ul").find("a:not(.checked):eq(0)").size() > 0) {
							$(this).parents("li").andSelf().children("a").removeClass("unchecked checked").addClass("undetermined");
							return false;
						}
						else $(this).children("a").removeClass("unchecked undetermined").addClass("checked");
					});
				}
				else n.children("a").removeClass("unchecked").addClass("checked");
				return true;
			},
			uncheck : function (n) {
				if(!n) return false;
				var t = $.tree.reference(n);
				n = t.get_node(n);
				if(n.children("a").hasClass("unchecked")) return true;

				var opts = $.extend(true, {}, $.tree.plugins.checkbox.defaults, t.settings.plugins.checkbox);
				if(opts.three_state) {
					n.find("li").andSelf().children("a").removeClass("checked undetermined").addClass("unchecked");
					n.parents("li").each(function () { 
						if($(this).find("a.checked, a.undetermined").size() - 1 > 0) {
							$(this).parents("li").andSelf().children("a").removeClass("unchecked checked").addClass("undetermined");
							return false;
						}
						else $(this).children("a").removeClass("checked undetermined").addClass("unchecked");
					});
				}
				else n.children("a").removeClass("checked").addClass("unchecked"); 
				return true;
			},
			toggle : function (n) {
				if(!n) return false;
				var t = $.tree.reference(n);
				n = t.get_node(n);
				if(n.children("a").hasClass("checked")) $.tree.plugins.checkbox.uncheck(n);
				else $.tree.plugins.checkbox.check(n);
			},

			callbacks : {
				onchange : function(n, t) {
					$.tree.plugins.checkbox.toggle(n);
				}
			}
		}
	});
})(jQuery);