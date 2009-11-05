/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	var loadedLangs = {};

	CKEDITOR.lang =
	{
		/**
		 * The list of languages available in the editor core.
		 * @type Object
		 * @example
		 * alert( CKEDITOR.lang.en );  // "true"
		 */
		languages :
		{
			'af'	: 1,
			'ar'	: 1,
			'bg'	: 1,
			'bn'	: 1,
			'bs'	: 1,
			'ca'	: 1,
			'cs'	: 1,
			'da'	: 1,
			'de'	: 1,
			'el'	: 1,
			'en-au'	: 1,
			'en-ca'	: 1,
			'en-uk'	: 1,
			'en'	: 1,
			'eo'	: 1,
			'es'	: 1,
			'et'	: 1,
			'eu'	: 1,
			'fa'	: 1,
			'fi'	: 1,
			'fo'	: 1,
			'fr-ca'	: 1,
			'fr'	: 1,
			'gl'	: 1,
			'gu'	: 1,
			'he'	: 1,
			'hi'	: 1,
			'hr'	: 1,
			'hu'	: 1,
			'is'	: 1,
			'it'	: 1,
			'ja'	: 1,
			'km'	: 1,
			'ko'	: 1,
			'lt'	: 1,
			'lv'	: 1,
			'mn'	: 1,
			'ms'	: 1,
			'nb'	: 1,
			'nl'	: 1,
			'no'	: 1,
			'pl'	: 1,
			'pt-br'	: 1,
			'pt'	: 1,
			'ro'	: 1,
			'ru'	: 1,
			'sk'	: 1,
			'sl'	: 1,
			'sr-latn'	: 1,
			'sr'	: 1,
			'sv'	: 1,
			'th'	: 1,
			'tr'	: 1,
			'uk'	: 1,
			'vi'	: 1,
			'zh-cn'	: 1,
			'zh'	: 1
		},

		/**
		 * Loads a specific language file, or auto detect it. A callback is
		 * then called when the file gets loaded.
		 * @param {String} languageCode The code of the language file to be
		 *		loaded. If "autoDetect" is set to true, this language will be
		 *		used as the default one, if the detect language is not
		 *		available in the core.
		 * @param {Boolean} autoDetect Indicates that the function must try to
		 *		detect the user language and load it instead.
		 * @param {Function} callback The function to be called once the
		 *		language file is loaded. Two parameters are passed to this
		 *		function: the language code and the loaded language entries.
		 * @example
		 */
		load : function( languageCode, defaultLanguage, callback )
		{
			// If no languageCode - fallback to browser or default.
			// If languageCode - fallback to no-localized version or default.
			if ( !languageCode || !CKEDITOR.lang.languages[ languageCode ] )
				languageCode = this.detect( defaultLanguage, languageCode );

			if ( !this[ languageCode ] )
			{
				CKEDITOR.scriptLoader.load( CKEDITOR.getUrl(
					'_source/' +	// @Packager.RemoveLine
					'lang/' + languageCode + '.js' ),
					function()
						{
							callback( languageCode, this[ languageCode ] );
						}
						, this );
			}
			else
				callback( languageCode, this[ languageCode ] );
		},

		/**
		 * Returns the language that best fit the user language. For example,
		 * suppose that the user language is "pt-br". If this language is
		 * supported by the editor, it is returned. Otherwise, if only "pt" is
		 * supported, it is returned instead. If none of the previous are
		 * supported, a default language is then returned.
		 * @param {String} defaultLanguage The default language to be returned
		 *		if the user language is not supported.
		 * @returns {String} The detected language code.
		 * @example
		 * alert( CKEDITOR.lang.detect( 'en' ) );  // e.g., in a German browser: "de"
		 */
		detect : function( defaultLanguage, probeLanguage )
		{
			var languages = this.languages;
			probeLanguage = probeLanguage || navigator.userLanguage || navigator.language;

			var parts = probeLanguage
					.toLowerCase()
					.match( /([a-z]+)(?:-([a-z]+))?/ ),
				lang = parts[1],
				locale = parts[2];

			if ( languages[ lang + '-' + locale ] )
				lang = lang + '-' + locale;
			else if ( !languages[ lang ] )
				lang = null;

			CKEDITOR.lang.detect = lang ?
				function() { return lang; } :
				function( defaultLanguage ) { return defaultLanguage; };

			return lang || defaultLanguage;
		}
	};

})();
