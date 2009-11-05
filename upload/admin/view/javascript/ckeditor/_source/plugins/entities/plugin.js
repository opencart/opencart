/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	var entities =

		// Base HTML entities.
		'nbsp,gt,lt,quot,' +

		// Latin-1 Entities
		'iexcl,cent,pound,curren,yen,brvbar,sect,uml,copy,ordf,laquo,' +
		'not,shy,reg,macr,deg,plusmn,sup2,sup3,acute,micro,para,middot,' +
		'cedil,sup1,ordm,raquo,frac14,frac12,frac34,iquest,times,divide,' +

		// Symbols
		'fnof,bull,hellip,prime,Prime,oline,frasl,weierp,image,real,trade,' +
		'alefsym,larr,uarr,rarr,darr,harr,crarr,lArr,uArr,rArr,dArr,hArr,' +
		'forall,part,exist,empty,nabla,isin,notin,ni,prod,sum,minus,lowast,' +
		'radic,prop,infin,ang,and,or,cap,cup,int,there4,sim,cong,asymp,ne,' +
		'equiv,le,ge,sub,sup,nsub,sube,supe,oplus,otimes,perp,sdot,lceil,' +
		'rceil,lfloor,rfloor,lang,rang,loz,spades,clubs,hearts,diams,' +

		// Other Special Characters
		'circ,tilde,ensp,emsp,thinsp,zwnj,zwj,lrm,rlm,ndash,mdash,lsquo,' +
		'rsquo,sbquo,ldquo,rdquo,bdquo,dagger,Dagger,permil,lsaquo,rsaquo,' +
		'euro';

	// Latin Letters Entities
	var latin =
		'Agrave,Aacute,Acirc,Atilde,Auml,Aring,AElig,Ccedil,Egrave,Eacute,' +
		'Ecirc,Euml,Igrave,Iacute,Icirc,Iuml,ETH,Ntilde,Ograve,Oacute,Ocirc,' +
		'Otilde,Ouml,Oslash,Ugrave,Uacute,Ucirc,Uuml,Yacute,THORN,szlig,' +
		'agrave,aacute,acirc,atilde,auml,aring,aelig,ccedil,egrave,eacute,' +
		'ecirc,euml,igrave,iacute,icirc,iuml,eth,ntilde,ograve,oacute,ocirc,' +
		'otilde,ouml,oslash,ugrave,uacute,ucirc,uuml,yacute,thorn,yuml,' +
		'OElig,oelig,Scaron,scaron,Yuml';

	// Greek Letters Entities.
	var greek =
		'Alpha,Beta,Gamma,Delta,Epsilon,Zeta,Eta,Theta,Iota,Kappa,Lambda,Mu,' +
		'Nu,Xi,Omicron,Pi,Rho,Sigma,Tau,Upsilon,Phi,Chi,Psi,Omega,alpha,' +
		'beta,gamma,delta,epsilon,zeta,eta,theta,iota,kappa,lambda,mu,nu,xi,' +
		'omicron,pi,rho,sigmaf,sigma,tau,upsilon,phi,chi,psi,omega,thetasym,' +
		'upsih,piv';

	function buildTable( entities )
	{
		var table = {},
			regex = [];

		// Entities that the browsers DOM don't transform to the final char
		// automatically.
		var specialTable =
			{
				nbsp	: '\u00A0',		// IE | FF
				shy		: '\u00AD',		// IE
				gt		: '\u003E',		// IE | FF |   --   | Opera
				lt		: '\u003C'		// IE | FF | Safari | Opera
			};

		entities = entities.replace( /\b(nbsp|shy|gt|lt|amp)(?:,|$)/g, function( match, entity )
			{
				table[ specialTable[ entity ] ] = '&' + entity + ';';
				regex.push( specialTable[ entity ] );
				return '';
			});

		// Transforms the entities string into an array.
		entities = entities.split( ',' );

		// Put all entities inside a DOM element, transforming them to their
		// final chars.
		var div = document.createElement( 'div' ),
			chars;
		div.innerHTML = '&' + entities.join( ';&' ) + ';';
		chars = div.innerHTML;
		div = null;

		// Add all chars to the table.
		for ( var i = 0 ; i < chars.length ; i++ )
		{
			var charAt = chars.charAt( i );
			table[ charAt ] = '&' + entities[ i ] + ';';
			regex.push( charAt );
		}

		table.regex = regex.join( '' );

		return table;
	}

	CKEDITOR.plugins.add( 'entities',
	{
		afterInit : function( editor )
		{
			var config = editor.config;

			if ( !config.entities )
				return;

			var dataProcessor = editor.dataProcessor,
				htmlFilter = dataProcessor && dataProcessor.htmlFilter;

			if ( htmlFilter )
			{
				var selectedEntities = entities;

				if ( config.entities_latin )
					selectedEntities += ',' + latin;

				if ( config.entities_greek )
					selectedEntities += ',' + greek;

				if ( config.entities_additional )
					selectedEntities += ',' + config.entities_additional;

				var entitiesTable = buildTable( selectedEntities );

				// Create the Regex used to find entities in the text.
				var entitiesRegex = '[' + entitiesTable.regex + ']';
				delete entitiesTable.regex;

				if ( config.entities_processNumerical )
					entitiesRegex = '[^ -~]|' + entitiesRegex ;

				entitiesRegex = new RegExp( entitiesRegex, 'g' );

				function getChar( character )
				{
					return entitiesTable[ character ] || ( '&#' + character.charCodeAt(0) + ';' );
				}

				htmlFilter.addRules(
					{
						text : function( text )
						{
							return text.replace( entitiesRegex, getChar );
						}
					});
			}
		}
	});
})();

/**
 * Whether to use HTML entities in the output.
 * @type Boolean
 * @default true
 * @example
 * config.entities = false;
 */
CKEDITOR.config.entities = true;

/**
 * Whether to convert some Latin characters (Latin alphabet No&#46; 1, ISO 8859-1)
 * to HTML entities. The list of entities can be found at the
 * <a href="http://www.w3.org/TR/html4/sgml/entities.html#h-24.2.1">W3C HTML 4.01 Specification, section 24.2.1</a>.
 * @type Boolean
 * @default true
 * @example
 * config.entities_latin = false;
 */
CKEDITOR.config.entities_latin = true;

/**
 * Whether to convert some symbols, mathematical symbols, and Greek letters to
 * HTML entities. This may be more relevant for users typing text written in Greek.
 * The list of entities can be found at the
 * <a href="http://www.w3.org/TR/html4/sgml/entities.html#h-24.3.1">W3C HTML 4.01 Specification, section 24.3.1</a>.
 * @type Boolean
 * @default true
 * @example
 * config.entities_greek = false;
 */
CKEDITOR.config.entities_greek = true;

/**
 * Whether to convert all remaining characters, not comprised in the ASCII
 * character table, to their relative numeric representation of HTML entity.
 * For example, the phrase "This is Chinese: &#27721;&#35821;." is outputted
 * as "This is Chinese: &amp;#27721;&amp;#35821;."
 * @type Boolean
 * @default false
 * @example
 * config.entities_processNumerical = true;
 */
CKEDITOR.config.entities_processNumerical = false;

/**
 * An additional list of entities to be used. It's a string containing each
 * entry separated by a comma. Entities names or number must be used, exclusing
 * the "&amp;" preffix and the ";" termination.
 * @default '#39'  // The single quote (') character.
 * @type String
 * @example
 */
CKEDITOR.config.entities_additional = '#39';
