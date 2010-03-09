(function ($) {
	if(typeof $.metadata == "undefined") throw "jsTree metadata: jQuery metadata plugin not included.";

	$.extend($.tree.plugins, {
		"metadata" : {
			defaults : {
				attribute	: "data"
			},
			callbacks : {
				check : function(rule, obj, value, tree) {
					var opts = $.extend(true, {}, $.tree.plugins.metadata.defaults, this.settings.plugins.metadata);
					if(typeof $(obj).metadata({ type : "attr", name : opts.attribute })[rule] != "undefined") return $(obj).metadata()[rule];
				}
			}
		}
	});
})(jQuery);