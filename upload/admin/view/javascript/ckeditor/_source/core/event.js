/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.event} class, which serves as the
 *		base for classes and objects that require event handling features.
 */

if ( !CKEDITOR.event )
{
	/**
	 * This is a base class for classes and objects that require event handling
	 * features.
	 * @constructor
	 * @example
	 */
	CKEDITOR.event = function()
	{};

	/**
	 * Implements the {@link CKEDITOR.event} features in an object.
	 * @param {Object} targetObject The object in which implement the features.
	 * @example
	 * var myObject = { message : 'Example' };
	 * <b>CKEDITOR.event.implementOn( myObject }</b>;
	 * myObject.on( 'testEvent', function()
	 *     {
	 *         alert( this.message );  // "Example"
	 *     });
	 * myObject.fire( 'testEvent' );
	 */
	CKEDITOR.event.implementOn = function( targetObject, isTargetPrototype )
	{
		var eventProto = CKEDITOR.event.prototype;

		for ( var prop in eventProto )
		{
			if ( targetObject[ prop ] == undefined )
				targetObject[ prop ] = eventProto[ prop ];
		}
	};

	CKEDITOR.event.prototype = (function()
	{
		// Returns the private events object for a given object.
		var getPrivate = function( obj )
		{
			var _ = ( obj.getPrivate && obj.getPrivate() ) || obj._ || ( obj._ = {} );
			return _.events || ( _.events = {} );
		};

		var eventEntry = function( eventName )
		{
			this.name = eventName;
			this.listeners = [];
		};

		eventEntry.prototype =
		{
			// Get the listener index for a specified function.
			// Returns -1 if not found.
			getListenerIndex : function( listenerFunction )
			{
				for ( var i = 0, listeners = this.listeners ; i < listeners.length ; i++ )
				{
					if ( listeners[i].fn == listenerFunction )
						return i;
				}
				return -1;
			}
		};

		return /** @lends CKEDITOR.event.prototype */ {
			/**
			 * Registers a listener to a specific event in the current object.
			 * @param {String} eventName The event name to which listen.
			 * @param {Function} listenerFunction The function listening to the
			 *		event.
			 * @param {Object} [scopeObj] The object used to scope the listener
			 *		call (the this object. If omitted, the current object is used.
			 * @param {Object} [listenerData] Data to be sent as the
			 *		{@link CKEDITOR.eventInfo#listenerData} when calling the
			 *		listener.
			 * @param {Number} [priority] The listener priority. Lower priority
			 *		listeners are called first. Listeners with the same priority
			 *		value are called in registration order. Defaults to 10.
			 * @example
			 * someObject.on( 'someEvent', function()
			 *     {
			 *         alert( this == someObject );  // "true"
			 *     });
			 * @example
			 * someObject.on( 'someEvent', function()
			 *     {
			 *         alert( this == anotherObject );  // "true"
			 *     }
			 *     , anotherObject );
			 * @example
			 * someObject.on( 'someEvent', function( event )
			 *     {
			 *         alert( event.listenerData );  // "Example"
			 *     }
			 *     , null, 'Example' );
			 * @example
			 * someObject.on( 'someEvent', function() { ... } );                   // 2nd called
			 * someObject.on( 'someEvent', function() { ... }, null, null, 100 );  // 3rd called
			 * someObject.on( 'someEvent', function() { ... }, null, null, 1 );    // 1st called
			 */
			on : function( eventName, listenerFunction, scopeObj, listenerData, priority )
			{
				// Get the event entry (create it if needed).
				var events = getPrivate( this ),
					event = events[ eventName ] || ( events[ eventName ] = new eventEntry( eventName ) );

				if ( event.getListenerIndex( listenerFunction ) < 0 )
				{
					// Get the listeners.
					var listeners = event.listeners;

					// Fill the scope.
					if ( !scopeObj )
						scopeObj = this;

					// Default the priority, if needed.
					if ( isNaN( priority ) )
						priority = 10;

					var me = this;

					// Create the function to be fired for this listener.
					var listenerFirer = function( editor, publisherData, stopFn, cancelFn )
					{
						var ev =
						{
							name : eventName,
							sender : this,
							editor : editor,
							data : publisherData,
							listenerData : listenerData,
							stop : stopFn,
							cancel : cancelFn,
							removeListener : function()
							{
								me.removeListener( eventName, listenerFunction );
							}
						};

						listenerFunction.call( scopeObj, ev );

						return ev.data;
					};
					listenerFirer.fn = listenerFunction;
					listenerFirer.priority = priority;

					// Search for the right position for this new listener, based on its
					// priority.
					for ( var i = listeners.length - 1 ; i >= 0 ; i-- )
					{
						// Find the item which should be before the new one.
						if ( listeners[ i ].priority <= priority )
						{
							// Insert the listener in the array.
							listeners.splice( i + 1, 0, listenerFirer );
							return;
						}
					}

					// If no position has been found (or zero length), put it in
					// the front of list.
					listeners.unshift( listenerFirer );
				}
			},

			/**
			 * Fires an specific event in the object. All registered listeners are
			 * called at this point.
			 * @function
			 * @param {String} eventName The event name to fire.
			 * @param {Object} [data] Data to be sent as the
			 *		{@link CKEDITOR.eventInfo#data} when calling the
			 *		listeners.
			 * @param {CKEDITOR.editor} [editor] The editor instance to send as the
			 *		{@link CKEDITOR.eventInfo#editor} when calling the
			 *		listener.
			 * @returns {Boolean|Object} A booloan indicating that the event is to be
			 *		canceled, or data returned by one of the listeners.
			 * @example
			 * someObject.on( 'someEvent', function() { ... } );
			 * someObject.on( 'someEvent', function() { ... } );
			 * <b>someObject.fire( 'someEvent' )</b>;  // both listeners are called
			 * @example
			 * someObject.on( 'someEvent', function( event )
			 *     {
			 *         alert( event.data );  // "Example"
			 *     });
			 * <b>someObject.fire( 'someEvent', 'Example' )</b>;
			 */
			fire : (function()
			{
				// Create the function that marks the event as stopped.
				var stopped = false;
				var stopEvent = function()
				{
					stopped = true;
				};

				// Create the function that marks the event as canceled.
				var canceled = false;
				var cancelEvent = function()
				{
					canceled = true;
				};

				return function( eventName, data, editor )
				{
					// Get the event entry.
					var event = getPrivate( this )[ eventName ];

					// Save the previous stopped and cancelled states. We may
					// be nesting fire() calls.
					var previousStopped = stopped,
						previousCancelled = canceled;

					// Reset the stopped and canceled flags.
					stopped = canceled = false;

					if ( event )
					{
						var listeners = event.listeners;

						if ( listeners.length )
						{
							// As some listeners may remove themselves from the
							// event, the original array length is dinamic. So,
							// let's make a copy of all listeners, so we are
							// sure we'll call all of them.
							listeners = listeners.slice( 0 );

							// Loop through all listeners.
							for ( var i = 0 ; i < listeners.length ; i++ )
							{
								// Call the listener, passing the event data.
								var retData = listeners[i].call( this, editor, data, stopEvent, cancelEvent );

								if ( typeof retData != 'undefined' )
									data = retData;

								// No further calls is stopped or canceled.
								if ( stopped || canceled )
									break;
							}
						}
					}

					var ret = canceled || ( typeof data == 'undefined' ? false : data );

					// Restore the previous stopped and canceled states.
					stopped = previousStopped;
					canceled = previousCancelled;

					return ret;
				};
			})(),

			/**
			 * Fires an specific event in the object, releasing all listeners
			 * registered to that event. The same listeners are not called again on
			 * successive calls of it or of {@link #fire}.
			 * @param {String} eventName The event name to fire.
			 * @param {Object} [data] Data to be sent as the
			 *		{@link CKEDITOR.eventInfo#data} when calling the
			 *		listeners.
			 * @param {CKEDITOR.editor} [editor] The editor instance to send as the
			 *		{@link CKEDITOR.eventInfo#editor} when calling the
			 *		listener.
			 * @returns {Boolean|Object} A booloan indicating that the event is to be
			 *		canceled, or data returned by one of the listeners.
			 * @example
			 * someObject.on( 'someEvent', function() { ... } );
			 * someObject.fire( 'someEvent' );  // above listener called
			 * <b>someObject.fireOnce( 'someEvent' )</b>;  // above listener called
			 * someObject.fire( 'someEvent' );  // no listeners called
			 */
			fireOnce : function( eventName, data, editor )
			{
				var ret = this.fire( eventName, data, editor );
				delete getPrivate( this )[ eventName ];
				return ret;
			},

			/**
			 * Unregisters a listener function from being called at the specified
			 *		event. No errors are thrown if the listener has not been
			 *		registered previously.
			 * @param {String} eventName The event name.
			 * @param {Function} listenerFunction The listener function to unregister.
			 * @example
			 * var myListener = function() { ... };
			 * someObject.on( 'someEvent', myListener );
			 * someObject.fire( 'someEvent' );  // myListener called
			 * <b>someObject.removeListener( 'someEvent', myListener )</b>;
			 * someObject.fire( 'someEvent' );  // myListener not called
			 */
			removeListener : function( eventName, listenerFunction )
			{
				// Get the event entry.
				var event = getPrivate( this )[ eventName ];

				if ( event )
				{
					var index = event.getListenerIndex( listenerFunction );
					if ( index >= 0 )
						event.listeners.splice( index, 1 );
				}
			},

			/**
			 * Checks if there is any listener registered to a given event.
			 * @param {String} eventName The event name.
			 * @example
			 * var myListener = function() { ... };
			 * someObject.on( 'someEvent', myListener );
			 * alert( someObject.<b>hasListeners( 'someEvent' )</b> );  // "true"
			 * alert( someObject.<b>hasListeners( 'noEvent' )</b> );    // "false"
			 */
			hasListeners : function( eventName )
			{
				var event = getPrivate( this )[ eventName ];
				return ( event && event.listeners.length > 0 ) ;
			}
		};
	})();
}
