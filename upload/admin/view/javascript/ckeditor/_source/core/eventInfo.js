/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the "virtual" {@link CKEDITOR.eventInfo} class, which
 *		contains the defintions of the event object passed to event listeners.
 *		This file is for documentation purposes only.
 */

/**
 * This class is not really part of the API. It just illustrates the features
 * of the event object passed to event listeners by a {@link CKEDITOR.event}
 * based object.
 * @name CKEDITOR.eventInfo
 * @constructor
 * @example
 * // Do not do this.
 * var myEvent = new CKEDITOR.eventInfo();  // Error: CKEDITOR.eventInfo is undefined
 */

/**
 * The event name.
 * @name CKEDITOR.eventInfo.prototype.name
 * @field
 * @type String
 * @example
 * someObject.on( 'someEvent', function( event )
 *     {
 *         alert( <b>event.name</b> );  // "someEvent"
 *     });
 * someObject.fire( 'someEvent' );
 */

/**
 * The object that publishes (sends) the event.
 * @name CKEDITOR.eventInfo.prototype.sender
 * @field
 * @type Object
 * @example
 * someObject.on( 'someEvent', function( event )
 *     {
 *         alert( <b>event.sender</b> == someObject );  // "true"
 *     });
 * someObject.fire( 'someEvent' );
 */

/**
 * The editor instance that holds the sender. May be the same as sender. May be
 * null if the sender is not part of an editor instance, like a component
 * running in standalone mode.
 * @name CKEDITOR.eventInfo.prototype.editor
 * @field
 * @type CKEDITOR.editor
 * @example
 * myButton.on( 'someEvent', function( event )
 *     {
 *         alert( <b>event.editor</b> == myEditor );  // "true"
 *     });
 * myButton.fire( 'someEvent', null, <b>myEditor</b> );
 */

/**
 * Any kind of additional data. Its format and usage is event dependent.
 * @name CKEDITOR.eventInfo.prototype.data
 * @field
 * @type Object
 * @example
 * someObject.on( 'someEvent', function( event )
 *     {
 *         alert( <b>event.data</b> );  // "Example"
 *     });
 * someObject.fire( 'someEvent', <b>'Example'</b> );
 */

/**
 * Any extra data appended during the listener registration.
 * @name CKEDITOR.eventInfo.prototype.listenerData
 * @field
 * @type Object
 * @example
 * someObject.on( 'someEvent', function( event )
 *     {
 *         alert( <b>event.listenerData</b> );  // "Example"
 *     }
 *     , null, <b>'Example'</b> );
 */

/**
 * Indicates that no further listeners are to be called.
 * @name CKEDITOR.eventInfo.prototype.stop
 * @function
 * @example
 * someObject.on( 'someEvent', function( event )
 *     {
 *         <b>event.stop()</b>;
 *     });
 * someObject.on( 'someEvent', function( event )
 *     {
 *         // This one will not be called.
 *     });
 * alert( someObject.fire( 'someEvent' ) );  // "false"
 */

/**
 * Indicates that the event is to be cancelled (if cancelable).
 * @name CKEDITOR.eventInfo.prototype.cancel
 * @function
 * @example
 * someObject.on( 'someEvent', function( event )
 *     {
 *         <b>event.cancel()</b>;
 *     });
 * someObject.on( 'someEvent', function( event )
 *     {
 *         // This one will not be called.
 *     });
 * alert( someObject.fire( 'someEvent' ) );  // "true"
 */
