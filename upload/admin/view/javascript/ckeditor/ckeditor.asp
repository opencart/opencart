<%
 '
 ' Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 ' For licensing, see LICENSE.html or http://ckeditor.com/license

' Shared variable for all instances ("static")
dim CKEDITOR_initComplete
dim CKEDITOR_returnedEvents

 ''
 ' \brief CKEditor class that can be used to create editor
 ' instances in ASP pages on server side.
 ' @see http://ckeditor.com
 '
 ' Sample usage:
 ' @code
 ' editor = new CKEditor
 ' editor.editor "editor1", "<p>Initial value.</p>", empty, empty
 ' @endcode

Class CKEditor

	''
	' The version of %CKEditor.
	private version

	''
	' A constant string unique for each release of %CKEditor.
	private mTimeStamp

	''
	' URL to the %CKEditor installation directory (absolute or relative to document root).
	' If not set, CKEditor will try to guess it's path.
	'
	' Example usage:
	' @code
	' editor.basePath = "/ckeditor/"
	' @endcode
	Public basePath

	''
	' A boolean variable indicating whether CKEditor has been initialized.
	' Set it to true only if you have already included
	' &lt;script&gt; tag loading ckeditor.js in your website.
	Public initialized

	''
	' Boolean variable indicating whether created code should be printed out or returned by a function.
	'
	' Example 1: get the code creating %CKEditor instance and print it on a page with the "echo" function.
	' @code
	' editor = new CKEditor
	' editor.returnOutput = true
	' code = editor.editor("editor1", "<p>Initial value.</p>", empty, empty)
	' response.write "<p>Editor 1:</p>"
	' response.write code
	' @endcode
	Public returnOutput

	''
	' A Dictionary with textarea attributes.
	'
	' When %CKEditor is created with the editor() method, a HTML &lt;textarea&gt; element is created,
	' it will be displayed to anyone with JavaScript disabled or with incompatible browser.
	public textareaAttributes

	''
	' A string indicating the creation date of %CKEditor.
	' Do not change it unless you want to force browsers to not use previously cached version of %CKEditor.
	public timestamp

	''
	' A dictionary that holds the instance configuration.
	private oInstanceConfig

	''
	' A dictionary that holds the configuration for all the instances.
	private oAllInstancesConfig

	''
	' A dictionary that holds event listeners for the instance.
	private oInstanceEvents

	''
	' A dictionary that holds event listeners for all the instances.
	private oAllInstancesEvents

	''
	' A Dictionary that holds global event listeners (CKEDITOR object)
	private oGlobalEvents


	Private Sub Class_Initialize()
		version = "3.6.3"
		timeStamp = "C3HA5RM"
		mTimeStamp = "C3HA5RM"

		Set oInstanceConfig = CreateObject("Scripting.Dictionary")
		Set oAllInstancesConfig = CreateObject("Scripting.Dictionary")

		Set oInstanceEvents = CreateObject("Scripting.Dictionary")
		Set oAllInstancesEvents = CreateObject("Scripting.Dictionary")
		Set oGlobalEvents = CreateObject("Scripting.Dictionary")

		Set textareaAttributes = CreateObject("Scripting.Dictionary")
		textareaAttributes.Add "rows", 8
		textareaAttributes.Add "cols", 60
	End Sub

	''
	 ' Creates a %CKEditor instance.
	 ' In incompatible browsers %CKEditor will downgrade to plain HTML &lt;textarea&gt; element.
	 '
	 ' @param name (string) Name of the %CKEditor instance (this will be also the "name" attribute of textarea element).
	 ' @param value (string) Initial value.
	 '
	 ' Example usage:
	 ' @code
	 ' set editor = New CKEditor
	 ' editor.editor "field1", "<p>Initial value.</p>"
	 ' @endcode
	 '
	 ' Advanced example:
	 ' @code
	 ' set editor = new CKEditor
	 ' set config = CreateObject("Scripting.Dictionary")
	 ' config.Add "toolbar", Array( _
	 '	Array( "Source", "-", "Bold", "Italic", "Underline", "Strike" ), _
	 '	Array( "Image", "Link", "Unlink", "Anchor" ) _
	 ' )
	 ' set events = CreateObject("Scripting.Dictionary")
	 ' events.Add "instanceReady", "function (evt) { alert('Loaded second editor: ' + evt.editor.name );}"

	 ' editor.editor "field1", "<p>Initial value.</p>", config, events
	 ' @endcode
	 '
	public function editor(name, value)
		dim attr, out, js, customConfig, extraConfig
		dim attribute

		attr = ""

		for each attribute in textareaAttributes
			attr = attr & " " &  attribute & "=""" & replace( textareaAttributes( attribute ), """", "&quot" ) & """"
		next

		out = "<textarea name=""" & name & """" & attr & ">" & Server.HtmlEncode(value) & "</textarea>" & vbcrlf

		if not(initialized) then
			out = out & init()
		end if

		set customConfig = configSettings()
		js = returnGlobalEvents()

		extraConfig = (new JSON)( empty, customConfig, false )
		if extraConfig<>"" then extraConfig = ", " & extraConfig
		js = js & "CKEDITOR.replace('" & name & "'" & extraConfig & ");"

		out = out & script(js)

		if not(returnOutput) then
			response.write out
			out = ""
		end if

		editor = out

		oInstanceConfig.RemoveAll
		oInstanceEvents.RemoveAll
	end function

	''
	 ' Replaces a &lt;textarea&gt; with a %CKEditor instance.
	 '
	 ' @param id (string) The id or name of textarea element.
	 '
	 ' Example 1: adding %CKEditor to &lt;textarea name="article"&gt;&lt;/textarea&gt; element:
	 ' @code
	 ' set editor = New CKEditor
	 ' editor.replace "article"
	 ' @endcode
	 '
	public function replaceInstance(id)
		dim out, js, customConfig, extraConfig

		out = ""
		if not(initialized) then
			out = out & init()
		end if

		set customConfig = configSettings()
		js = returnGlobalEvents()

		extraConfig = (new JSON)( empty, customConfig, false )
		if extraConfig<>"" then extraConfig = ", " & extraConfig
		js = js & "CKEDITOR.replace('" & id & "'" & extraConfig & ");"

		out = out & script(js)

		if not(returnOutput) then
			response.write out
			out = ""
		end if

		replaceInstance = out

		oInstanceConfig.RemoveAll
		oInstanceEvents.RemoveAll
	end function

	''
	 ' Replace all &lt;textarea&gt; elements available in the document with editor instances.
	 '
	 ' @param className (string) If set, replace all textareas with class className in the page.
	 '
	 ' Example 1: replace all &lt;textarea&gt; elements in the page.
	 ' @code
	 ' editor = new CKEditor
	 ' editor.replaceAll empty
	 ' @endcode
	 '
	 ' Example 2: replace all &lt;textarea class="myClassName"&gt; elements in the page.
	 ' @code
	 ' editor = new CKEditor
	 ' editor.replaceAll 'myClassName'
	 ' @endcode
	 '
	function replaceAll(className)
		dim out, js, customConfig

		out = ""
		if not(initialized) then
			out = out & init()
		end if

		set customConfig = configSettings()
		js = returnGlobalEvents()

		if (customConfig.Count=0) then
			if (isEmpty(className)) then
				js = js & "CKEDITOR.replaceAll();"
			else
				js = js & "CKEDITOR.replaceAll('" & className & "');"
			end if
		else
			js = js & "CKEDITOR.replaceAll( function(textarea, config) {\n"
			if not(isEmpty(className)) then
				js = js & "	var classRegex = new RegExp('(?:^| )' + '" & className & "' + '(?:$| )');\n"
				js = js & "	if (!classRegex.test(textarea.className))\n"
				js = js & "		return false;\n"
			end if
			js = js & "	CKEDITOR.tools.extend(config, " & (new JSON)( empty, customConfig, false ) & ", true);"
			js = js & "} );"
		end if

		out = out & script(js)

		if not(returnOutput) then
			response.write out
			out = ""
		end if

		replaceAll = out

		oInstanceConfig.RemoveAll
		oInstanceEvents.RemoveAll
	end function


	''
	' A Dictionary that holds the %CKEditor configuration for all instances
	' For the list of available options, see http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
	'
	' Example usage:
	' @code
	' editor.config("height") = 400
	' // Use @@ at the beggining of a string to ouput it without surrounding quotes.
	' editor.config("width") = "@@screen.width * 0.8"
	' @endcode
	Public Property Let Config( configKey, configValue )
		oAllInstancesConfig.Add configKey, configValue
	End Property

	''
	' Configuration options for the next instance
	'
	Public Property Let instanceConfig( configKey, configValue )
		oInstanceConfig.Add configKey, configValue
	End Property

	''
	 ' Adds event listener.
	 ' Events are fired by %CKEditor in various situations.
	 '
	 ' @param eventName (string) Event name.
	 ' @param javascriptCode (string) Javascript anonymous function or function name.
	 '
	 ' Example usage:
	 ' @code
	 ' editor.addEventHandler  "instanceReady", "function (ev) { " & _
	 '    " alert('Loaded: ' + ev.editor.name); " & _
	 ' "}"
	 ' @endcode
	 '
	public sub addEventHandler(eventName, javascriptCode)
		if not(oAllInstancesEvents.Exists( eventName ) ) then
			oAllInstancesEvents.Add eventName, Array()
		end if

		dim listeners, size
		listeners = oAllInstancesEvents( eventName )
		size = ubound(listeners) + 1
		redim preserve listeners(size)
		listeners(size) = javascriptCode

		oAllInstancesEvents( eventName ) = listeners
'		'' Avoid duplicates. fixme...
'		if (!in_array($javascriptCode, $this->_events[$event])) {
'			$this->_events[$event][] = $javascriptCode;
'		}
	end sub

	''
	 ' Clear registered event handlers.
	 ' Note: this function will have no effect on already created editor instances.
	 '
	 ' @param eventName (string) Event name, if set to 'empty' all event handlers will be removed.
	 '
	public sub clearEventHandlers( eventName )
		if not(isEmpty( eventName )) then
			oAllInstancesEvents.Remove eventName
		else
			oAllInstancesEvents.RemoveAll
		end if
	end sub


	''
	 ' Adds event listener only for the next instance.
	 ' Events are fired by %CKEditor in various situations.
	 '
	 ' @param eventName (string) Event name.
	 ' @param javascriptCode (string) Javascript anonymous function or function name.
	 '
	 ' Example usage:
	 ' @code
	 ' editor.addInstanceEventHandler  "instanceReady", "function (ev) { " & _
	 '    " alert('Loaded: ' + ev.editor.name); " & _
	 ' "}"
	 ' @endcode
	 '
	public sub addInstanceEventHandler(eventName, javascriptCode)
		if not(oInstanceEvents.Exists( eventName ) ) then
			oInstanceEvents.Add eventName, Array()
		end if

		dim listeners, size
		listeners = oInstanceEvents( eventName )
		size = ubound(listeners) + 1
		redim preserve listeners(size)
		listeners(size) = javascriptCode

		oInstanceEvents( eventName ) = listeners
'		'' Avoid duplicates. fixme...
'		if (!in_array($javascriptCode, $this->_events[$event])) {
'			$this->_events[$event][] = $javascriptCode;
'		}
	end sub

	''
	 ' Clear registered event handlers.
	 ' Note: this function will have no effect on already created editor instances.
	 '
	 ' @param eventName (string) Event name, if set to 'empty' all event handlers will be removed.
	 '
	public sub clearInstanceEventHandlers( eventName )
		if not(isEmpty( eventName )) then
			oInstanceEvents.Remove eventName
		else
			oInstanceEvents.RemoveAll
		end if
	end sub

	''
	 ' Adds global event listener.
	 '
	 ' @param event (string) Event name.
	 ' @param javascriptCode (string) Javascript anonymous function or function name.
	 '
	 ' Example usage:
	 ' @code
	 ' editor.addGlobalEventHandler "dialogDefinition", "function (ev) { " & _
	 '   "  alert('Loading dialog: ' + ev.data.name); " & _
	 ' "}"
	 ' @endcode
	 '
	public sub addGlobalEventHandler( eventName, javascriptCode)
		if not(oGlobalEvents.Exists( eventName ) ) then
			oGlobalEvents.Add eventName, Array()
		end if

		dim listeners, size
		listeners = oGlobalEvents( eventName )
		size = ubound(listeners) + 1
		redim preserve listeners(size)
		listeners(size) = javascriptCode

		oGlobalEvents( eventName ) = listeners

'		// Avoid duplicates.
'		if (!in_array($javascriptCode, $this->_globalEvents[$event])) {
'			$this->_globalEvents[$event][] = $javascriptCode;
'		}
	end sub

	''
	 ' Clear registered global event handlers.
	 ' Note: this function will have no effect if the event handler has been already printed/returned.
	 '
	 ' @param eventName (string) Event name, if set to 'empty' all event handlers will be removed .
	 '
	public sub clearGlobalEventHandlers( eventName )
		if not(isEmpty( eventName )) then
			oGlobalEvents.Remove eventName
		else
			oGlobalEvents.RemoveAll
		end if
	end sub

	''
	 ' Prints javascript code.
	 '
	 ' @param string js
	 '
	private function script(js)
		script = "<script type=""text/javascript"">" & _
			"//<![CDATA[" & vbcrlf & _
			js & vbcrlf & _
			"//]]>" & _
			"</script>" & vbcrlf
	end function

	''
	 ' Returns the configuration array (global and instance specific settings are merged into one array).
	 '
	 ' @param instanceConfig (Dictionary) The specific configurations to apply to editor instance.
	 ' @param instanceEvents (Dictionary) Event listeners for editor instance.
	 '
	private function configSettings()
		dim mergedConfig, mergedEvents
		set mergedConfig = cloneDictionary(oAllInstancesConfig)
		set mergedEvents = cloneDictionary(oAllInstancesEvents)

		if not(isEmpty(oInstanceConfig)) then
			set mergedConfig = mergeDictionary(mergedConfig, oInstanceConfig)
		end if

		if not(isEmpty(oInstanceEvents)) then
			for each eventName in oInstanceEvents
				code = oInstanceEvents( eventName )

				if not(mergedEvents.Exists( eventName)) then
					mergedEvents.Add eventName, code
				else

					dim listeners, size
					listeners = mergedEvents( eventName )
					size = ubound(listeners)
					if isArray( code ) then
						addedCount = ubound(code)
						redim preserve listeners( size + addedCount + 1 )
						for i = 0 to addedCount
							listeners(size + i + 1) = code (i)
						next
					else
						size = size + 1
						redim preserve listeners(size)
						listeners(size) = code
					end if

					mergedEvents( eventName ) = listeners
				end if
			next

		end if

		dim i, eventName, handlers, configON, ub, code

		if mergedEvents.Count>0 then
			if mergedConfig.Exists( "on" ) then
				set configON = mergedConfig.items( "on" )
			else
				set configON = CreateObject("Scripting.Dictionary")
				mergedConfig.Add "on", configOn
			end if

			for each eventName in mergedEvents
				handlers = mergedEvents( eventName )
				code = ""

				if isArray(handlers) then
					uB = ubound(handlers)
					if (uB = 0) then
						code = handlers(0)
					else
						code = "function (ev) {"
						for i=0 to uB
							code = code & "(" & handlers(i) & ")(ev);"
						next
						code = code & "}"
					end if
				else
					code = handlers
				end if
				' Using @@ at the beggining to signal JSON that we don't want this quoted.
				configON.Add eventName, "@@" & code
			next

'			set mergedConfig.Item("on") = configOn
		end if

		set configSettings = mergedConfig
	end function

	 ''
		' Returns a copy of a scripting.dictionary object
		'
	private function cloneDictionary( base )
		dim newOne, tmpKey

		Set newOne = CreateObject("Scripting.Dictionary")
		for each tmpKey in base
			newOne.Add tmpKey , base( tmpKey )
		next

		set cloneDictionary = newOne
	end function

	 ''
		' Combines two scripting.dictionary objects
		' The base object isn't modified, and extra gets all the properties in base
		'
	private function mergeDictionary(base, extra)
		dim newOne, tmpKey

		for each tmpKey in base
			if not(extra.Exists( tmpKey )) then
				extra.Add tmpKey, base( tmpKey )
			end if
		next

		set mergeDictionary = extra
	end function

	''
	 ' Return global event handlers.
	 '
	private function returnGlobalEvents()
		dim out, eventName, handlers
		dim handlersForEvent, handler, code, i
		out = ""

		if (isempty(CKEDITOR_returnedEvents)) then
			set CKEDITOR_returnedEvents = CreateObject("Scripting.Dictionary")
		end if

		for each eventName in oGlobalEvents
			handlers = oGlobalEvents( eventName )

			if not(CKEDITOR_returnedEvents.Exists(eventName)) then
				CKEDITOR_returnedEvents.Add eventName, CreateObject("Scripting.Dictionary")
			end if

				set handlersForEvent = CKEDITOR_returnedEvents.Item( eventName )

				' handlersForEvent is another dictionary
				' and handlers is an array

				for i = 0 to ubound(handlers)
					code = handlers( i )

					' Return only new events
					if not(handlersForEvent.Exists( code )) then
						if (out <> "") then out = out & vbcrlf
						out = out & "CKEDITOR.on('" &  eventName & "', " & code & ");"
						handlersForEvent.Add code, code
					end if
				next
		next

		returnGlobalEvents = out
	end function

	''
	 ' Initializes CKEditor (executed only once).
	 '
	private function init()
		dim out, args, path, extraCode, file
		out = ""

		if (CKEDITOR_initComplete) then
			init = ""
			exit function
		end if

		if (initialized) then
			CKEDITOR_initComplete = true
			init = ""
			exit function
		end if

		args = ""
		path = ckeditorPath()

		if (timestamp <> "") and (timestamp <> "%" & "TIMESTAMP%") then
			args = "?t=" & timestamp
		end if

		' Skip relative paths...
		if (instr(path, "..") <> 0) then
			out = out & script("window.CKEDITOR_BASEPATH='" &  path  & "';")
		end if

		out = out & "<scr" & "ipt type=""text/javascript"" src=""" & path & ckeditorFileName() & args & """></scr" & "ipt>" & vbcrlf

		extraCode = ""
		if (timestamp <> mTimeStamp) then
			extraCode = extraCode & "CKEDITOR.timestamp = '" & timestamp & "';"
		end if
		if (extraCode <> "") then
			out = out & script(extraCode)
		end if

		CKEDITOR_initComplete = true
		initialized = true

		init = out
	end function

	private function ckeditorFileName()
		ckeditorFileName = "ckeditor.js"
	end function

	''
	 ' Return path to ckeditor.js.
	 '
	private function ckeditorPath()
		if (basePath <> "") then
			ckeditorPath = basePath
		else
			' In classic ASP we can't get the location of this included script
			ckeditorPath = "/ckeditor/"
		end if

		' Try to check if that folder contains the CKEditor files:
		' If it's a full URL avoid checking it as it might point to an external server.
		if (instr(ckeditorPath, "://") <> 0) then exit function

		dim filename, oFSO, exists
		filename = server.mapPath(basePath & ckeditorFileName())
		set oFSO = Server.CreateObject("Scripting.FileSystemObject")
		exists = oFSO.FileExists(filename)
		set oFSO = nothing

		if not(exists) then
			response.clear
			response.write "<h1>CKEditor path validation failed</h1>"
			response.write "<p>The path &quot;" & ckeditorPath & "&quot; doesn't include the CKEditor main file (" & ckeditorFileName() & ")</p>"
			response.write "<p>Please, verify that you have set it correctly and/or adjust the 'basePath' property</p>"
			response.write "<p>Checked for physical file: &quot;" & filename & "&quot;</p>"
			response.end
		end if
	end function

End Class



' URL: http://www.webdevbros.net/2007/04/26/generate-json-from-asp-datatypes/
'**************************************************************************************************************
'' @CLASSTITLE:		JSON
'' @CREATOR:		Michal Gabrukiewicz (gabru at grafix.at), Michael Rebec
'' @CONTRIBUTORS:	- Cliff Pruitt (opensource at crayoncowboy.com)
''					- Sylvain Lafontaine
''					- Jef Housein
''					- Jeremy Brown
'' @CREATEDON:		2007-04-26 12:46
'' @CDESCRIPTION:	Comes up with functionality for JSON (http://json.org) to use within ASP.
''					Correct escaping of characters, generating JSON Grammer out of ASP datatypes and structures
''					Some examples (all use the <em>toJSON()</em> method but as it is the class' default method it can be left out):
''					<code>
''					<%
''					'simple number
''					output = (new JSON)("myNum", 2, false)
''					'generates {"myNum": 2}
''
''					'array with different datatypes
''					output = (new JSON)("anArray", array(2, "x", null), true)
''					'generates "anArray": [2, "x", null]
''					'(note: the last parameter was true, thus no surrounding brackets in the result)
''					% >
''					</code>
'' @REQUIRES:		-
'' @OPTIONEXPLICIT:	yes
'' @VERSION:		1.5.1

'**************************************************************************************************************
class JSON

	'private members
	private output, innerCall

	'**********************************************************************************************************
	'* constructor
	'**********************************************************************************************************
	public sub class_initialize()
		newGeneration()
	end sub

	'******************************************************************************************
	'' @SDESCRIPTION:	STATIC! takes a given string and makes it JSON valid
	'' @DESCRIPTION:	all characters which needs to be escaped are beeing replaced by their
	''					unicode representation according to the
	''					RFC4627#2.5 - http://www.ietf.org/rfc/rfc4627.txt?number=4627
	'' @PARAM:			val [string]: value which should be escaped
	'' @RETURN:			[string] JSON valid string
	'******************************************************************************************
	public function escape(val)
		dim cDoubleQuote, cRevSolidus, cSolidus
		cDoubleQuote = &h22
		cRevSolidus = &h5C
		cSolidus = &h2F
		dim i, currentDigit
		for i = 1 to (len(val))
			currentDigit = mid(val, i, 1)
			if ascw(currentDigit) > &h00 and ascw(currentDigit) < &h1F then
				currentDigit = escapequence(currentDigit)
			elseif ascw(currentDigit) >= &hC280 and ascw(currentDigit) <= &hC2BF then
				currentDigit = "\u00" + right(padLeft(hex(ascw(currentDigit) - &hC200), 2, 0), 2)
			elseif ascw(currentDigit) >= &hC380 and ascw(currentDigit) <= &hC3BF then
				currentDigit = "\u00" + right(padLeft(hex(ascw(currentDigit) - &hC2C0), 2, 0), 2)
			else
				select case ascw(currentDigit)
					case cDoubleQuote: currentDigit = escapequence(currentDigit)
					case cRevSolidus: currentDigit = escapequence(currentDigit)
					case cSolidus: currentDigit = escapequence(currentDigit)
				end select
			end if
			escape = escape & currentDigit
		next
	end function

	'******************************************************************************************************************
	'' @SDESCRIPTION:	generates a representation of a name value pair in JSON grammer
	'' @DESCRIPTION:	It generates a name value pair which is represented as <em>{"name": value}</em> in JSON.
	''					the generation is fully recursive. Thus the value can also be a complex datatype (array in dictionary, etc.) e.g.
	''					<code>
	''					<%
	''					set j = new JSON
	''					j.toJSON "n", array(RS, dict, false), false
	''					j.toJSON "n", array(array(), 2, true), false
	''					% >
	''					</code>
	'' @PARAM:			name [string]: name of the value (accessible with javascript afterwards). leave empty to get just the value
	'' @PARAM:			val [variant], [int], [float], [array], [object], [dictionary]: value which needs
	''					to be generated. Conversation of the data types is as follows:<br>
	''					- <strong>ASP datatype -> JavaScript datatype</strong>
	''					- NOTHING, NULL -> null
	''					- INT, DOUBLE -> number
	''					- STRING -> string
	''					- BOOLEAN -> bool
	''					- ARRAY -> array
	''					- DICTIONARY -> Represents it as name value pairs. Each key is accessible as property afterwards. json will look like <code>"name": {"key1": "some value", "key2": "other value"}</code>
	''					- <em>multidimensional array</em> -> Generates a 1-dimensional array (flat) with all values of the multidimensional array
	''					- <em>request</em> object -> every property and collection (cookies, form, querystring, etc) of the asp request object is exposed as an item of a dictionary. Property names are <strong>lowercase</strong>. e.g. <em>servervariables</em>.
	''					- OBJECT -> name of the type (if unknown type) or all its properties (if class implements <em>reflect()</em> method)
	''					Implement a <strong>reflect()</strong> function if you want your custom classes to be recognized. The function must return
	''					a dictionary where the key holds the property name and the value its value. Example of a reflect function within a User class which has firstname and lastname properties
	''					<code>
	''					<%
	''					function reflect()
	''					.	set reflect = server.createObject("scripting.dictionary")
	''					.	reflect.add "firstname", firstname
	''					.	reflect.add "lastname", lastname
	''					end function
	''					% >
	''					</code>
	''					Example of how to generate a JSON representation of the asp request object and access the <em>HTTP_HOST</em> server variable in JavaScript:
	''					<code>
	''					<script>alert(<%= (new JSON)(empty, request, false) % >.servervariables.HTTP_HOST);</script>
	''					</code>
	'' @PARAM:			nested [bool]: indicates if the name value pair is already nested within another? if yes then the <em>{}</em> are left out.
	'' @RETURN:			[string] returns a JSON representation of the given name value pair
	'******************************************************************************************************************
	public default function toJSON(name, val, nested)
		if not nested and not isEmpty(name) then write("{")
		if not isEmpty(name) then write("""" & escape(name) & """: ")
		generateValue(val)
		if not nested and not isEmpty(name) then write("}")
		toJSON = output

		if innerCall = 0 then newGeneration()
	end function

	'******************************************************************************************************************
	'* generate
	'******************************************************************************************************************
	private function generateValue(val)
		if isNull(val) then
			write("null")
		elseif isArray(val) then
			generateArray(val)
		elseif isObject(val) then
			dim tName : tName = typename(val)
			if val is nothing then
				write("null")
			elseif tName = "Dictionary" or tName = "IRequestDictionary" then
				generateDictionary(val)
			elseif tName = "IRequest" then
				set req = server.createObject("scripting.dictionary")
				req.add "clientcertificate", val.ClientCertificate
				req.add "cookies", val.cookies
				req.add "form", val.form
				req.add "querystring", val.queryString
				req.add "servervariables", val.serverVariables
				req.add "totalbytes", val.totalBytes
				generateDictionary(req)
			elseif tName = "IStringList" then
				if val.count = 1 then
					toJSON empty, val(1), true
				else
					generateArray(val)
				end if
			else
				generateObject(val)
			end if
		else
			'bool
			dim varTyp
			varTyp = varType(val)
			if varTyp = 11 then
				if val then write("true") else write("false")
			'int, long, byte
			elseif varTyp = 2 or varTyp = 3 or varTyp = 17 or varTyp = 19 then
				write(cLng(val))
			'single, double, currency
			elseif varTyp = 4 or varTyp = 5 or varTyp = 6 or varTyp = 14 then
				write(replace(cDbl(val), ",", "."))
			else
				' Using @@ at the beggining to signal JSON that we don't want this quoted.
				if left(val, 2) = "@@" then
					write( mid( val, 3 ) )
				else
					write("""" & escape(val & "") & """")
				end if
			end if
		end if
		generateValue = output
	end function

	'******************************************************************************************************************
	'* generateArray
	'******************************************************************************************************************
	private sub generateArray(val)
		dim item, i
		write("[")
		i = 0
		'the for each allows us to support also multi dimensional arrays
		for each item in val
			if i > 0 then write(",")
			generateValue(item)
			i = i + 1
		next
		write("]")
	end sub

	'******************************************************************************************************************
	'* generateDictionary
	'******************************************************************************************************************
	private sub generateDictionary(val)
		innerCall = innerCall + 1
		if val.count = 0 then
			toJSON empty, null, true
			exit sub
		end if
		dim key, i
		write("{")
		i = 0
		for each key in val
			if i > 0 then write(",")
			toJSON key, val(key), true
			i = i + 1
		next
		write("}")
		innerCall = innerCall - 1
	end sub

	'******************************************************************************************************************
	'* generateObject
	'******************************************************************************************************************
	private sub generateObject(val)
		dim props
		on error resume next
		set props = val.reflect()
		if err = 0 then
			on error goto 0
			innerCall = innerCall + 1
			toJSON empty, props, true
			innerCall = innerCall - 1
		else
			on error goto 0
			write("""" & escape(typename(val)) & """")
		end if
	end sub

	'******************************************************************************************************************
	'* newGeneration
	'******************************************************************************************************************
	private sub newGeneration()
		output = empty
		innerCall = 0
	end sub

	'******************************************************************************************
	'* JsonEscapeSquence
	'******************************************************************************************
	private function escapequence(digit)
		escapequence = "\u00" + right(padLeft(hex(ascw(digit)), 2, 0), 2)
	end function

	'******************************************************************************************
	'* padLeft
	'******************************************************************************************
	private function padLeft(value, totalLength, paddingChar)
		padLeft = right(clone(paddingChar, totalLength) & value, totalLength)
	end function

	'******************************************************************************************
	'* clone
	'******************************************************************************************
	private function clone(byVal str, n)
		dim i
		for i = 1 to n : clone = clone & str : next
	end function

	'******************************************************************************************
	'* write
	'******************************************************************************************
	private sub write(val)
		output = output & val
	end sub

end class
%>
