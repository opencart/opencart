(function ($) {
	if(typeof Sarissa == "undefined") throw "jsTree xml_nested: Sarissa is not included.";

	$.extend($.tree.datastores, {
		"xml_nested" : function () {
			return {
				get		: function(obj, tree, opts) {
					var str = "";
					if(!obj || $(obj).size() == 0) {
						obj = tree.container.children("ul").children("li");
					}
					else obj = $(obj);

					if(obj.size() > 1) {
						var _this = this;
						var str	 = '<root>';
						obj.each(function () {
							opts.callback = true;
							str += _this.get(this, tree, opts);
						});
						str		+= '</root>';
						return str;
					}

					if(!opts) var opts = {};
					if(!opts.outer_attrib) opts.outer_attrib = [ "id", "rel", "class" ];
					if(!opts.inner_attrib) opts.inner_attrib = [ ];
					if(!opts.callback) str += '<root>';

					str += '<item ';
					for(var i in opts.outer_attrib) {
						if(!opts.outer_attrib.hasOwnProperty(i)) continue;
						var val = (opts.outer_attrib[i] == "class") ? obj.attr(opts.outer_attrib[i]).replace(/(^| )last( |$)/ig," ").replace(/(^| )(leaf|closed|open)( |$)/ig," ") : obj.attr(opts.outer_attrib[i]);
						if(typeof val != "undefined" && val.toString().replace(" ","").length > 0) str += ' ' + opts.outer_attrib[i] + '="' + val.toString() + '" ';
						delete val;
					}
					str += '>';

					str += '<content>';
					if(tree.settings.languages.length) {
						for(var i in tree.settings.languages) {
							if(!tree.settings.languages.hasOwnProperty(i)) continue;
							str += this.process_inner(obj.children("a." + tree.settings.languages[i]), tree, opts, tree.settings.languages[i]);
						}
					}
					else {
						str += this.process_inner(obj.children("a"), tree, opts);
					}
					str += '</content>';

					if(obj.children("ul").size() > 0) {
						var _this = this;
						opts.callback = true;
						obj.children("ul").children("li").each(function () {
							str += _this.get(this, tree, opts);
						});
						opts.callback = false;
					}
					str += '</item>';

					if(!opts.callback) str += '</root>';
					return str;
				},
				process_inner : function(obj, tree, opts, lang) {
					var str = '<name ';
					if(lang) str += ' lang="' + lang + '" ';
					if(opts.inner_attrib.length || obj.children("ins").get(0).style.backgroundImage.toString().length || obj.children("ins").get(0).className.length) {
						if(obj.children("ins").get(0).style.className.length) {
							str += ' icon="' + obj.children("ins").get(0).style.className + '" ';
						}
						if(obj.children("ins").get(0).style.backgroundImage.length) {
							str += ' icon="' + obj.children("ins").get(0).style.backgroundImage.replace("url(","").replace(")","") + '" ';
						}
						if(opts.inner_attrib.length) {
							for(var j in opts.inner_attrib) {
								if(!opts.inner_attrib.hasOwnProperty(j)) continue;
								var val = obj.attr(opts.inner_attrib[j]);
								if(typeof val != "undefined" && val.toString().replace(" ","").length > 0) str += ' ' + opts.inner_attrib[j] + '="' + val.toString() + '" ';
								delete val;
							}
						}
					}
					str += '><![CDATA[' + tree.get_text(obj,lang) + ']]></name>';
					return str;
				},
				parse	: function(data, tree, opts, callback) { 
					var processor = new XSLTProcessor();
					processor.importStylesheet($.tree.datastores.xml_nested.xsl);

					var result = $((new XMLSerializer()).serializeToString(processor.transformToDocument(data)).replace('<?xml version="1.0"?>',''));
					// Opera bug
					if(result.size() > 1)	result = result.eq(1);
					if(result.is("ul"))		result = result.html();
					else					result = result.find("ul:eq(0)").html();
					if(callback) callback.call(null,result);

					// Disabled because of Chrome issues
					// if(callback) callback.call(null,(new XMLSerializer()).serializeToString(processor.transformToDocument(data)).replace(/^<ul[^>]*>/i,"").replace(/<\/ul>$/i,""));
				},
				load	: function(data, tree, opts, callback) {
					if(opts.static) {
						callback.call(null, (new DOMParser()).parseFromString(opts.static,'text/xml'));
					}
					else {
						$.ajax({
							'type'		: opts.method,
							'url'		: opts.url, 
							'data'		: data, 
							'dataType'	: "xml",
							'success'	: function (d, textStatus) {
								callback.call(null, d);
							},
							'error'		: function (xhttp, textStatus, errorThrown) { 
								callback.call(null, false);
								tree.error(errorThrown + " " + textStatus); 
							}
						});
					}
				}
			}
		}
	});
	$.tree.datastores.xml_nested.xsl = (new DOMParser()).parseFromString('<?xml version="1.0" encoding="utf-8" ?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" ><xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" standalone="no" indent="no" media-type="text/html" /><xsl:template match="/"><xsl:call-template name="nodes"><xsl:with-param name="node" select="/root" /></xsl:call-template></xsl:template><xsl:template name="nodes"><xsl:param name="node" /><ul><xsl:for-each select="$node/item"><xsl:variable name="children" select="count(./item) &gt; 0" /><li><xsl:attribute name="class"><xsl:if test="position() = last()"> last </xsl:if><xsl:choose><xsl:when test="@state = \'open\'"> open </xsl:when><xsl:when test="$children or @hasChildren"> closed </xsl:when><xsl:otherwise> leaf </xsl:otherwise></xsl:choose><xsl:value-of select="@class" /></xsl:attribute><xsl:for-each select="@*"><xsl:if test="name() != \'class\' and name() != \'state\' and name() != \'hasChildren\'"><xsl:attribute name="{name()}"><xsl:value-of select="." /></xsl:attribute></xsl:if></xsl:for-each><xsl:for-each select="content/name"><a href=""><xsl:attribute name="class"><xsl:value-of select="@lang" /><xsl:value-of select="@class" /></xsl:attribute><xsl:attribute name="style"><xsl:value-of select="@style" /></xsl:attribute><xsl:for-each select="@*"><xsl:if test="name() != \'style\' and name() != \'class\'"><xsl:attribute name="{name()}"><xsl:value-of select="." /></xsl:attribute></xsl:if></xsl:for-each><ins><xsl:if test="string-length(attribute::icon) > 0"><xsl:choose><xsl:when test="not(contains(@icon,\'/\'))"><xsl:attribute name="class"><xsl:value-of select="@icon" /></xsl:attribute></xsl:when><xsl:otherwise><xsl:attribute name="style">background-image:url(<xsl:value-of select="@icon" />);</xsl:attribute></xsl:otherwise></xsl:choose></xsl:if><xsl:text>&#xa0;</xsl:text></ins><xsl:value-of select="current()" /></a></xsl:for-each><xsl:if test="$children or @hasChildren"><xsl:call-template name="nodes"><xsl:with-param name="node" select="current()" /></xsl:call-template></xsl:if></li></xsl:for-each></ul></xsl:template></xsl:stylesheet>','text/xml');
})(jQuery);