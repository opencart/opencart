/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.themes} object, which is used to
 *		manage themes registration and loading.
 */

/**
 * Manages themes registration and loading.
 * @namespace
 * @augments CKEDITOR.resourceManager
 * @example
 */
CKEDITOR.themes = new CKEDITOR.resourceManager(
	'_source/'+		// @Packager.RemoveLine
	'themes/', 'theme' );
