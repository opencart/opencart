<!---
	image.cfc v2.19, written by Rick Root (rick@webworksllc.com)
	Derivative of work originally done originally by James Dew.

	Related Web Sites:
	- http://www.opensourcecf.com/imagecfc (home page)
	- http://www.cfopen.org/projects/imagecfc (project page)

	LICENSE
	-------
	Copyright (c) 2007, Rick Root <rick@webworksllc.com>
	All rights reserved.

	Redistribution and use in source and binary forms, with or
	without modification, are permitted provided that the
	following conditions are met:

	- Redistributions of source code must retain the above
	  copyright notice, this list of conditions and the
	  following disclaimer.
	- Redistributions in binary form must reproduce the above
	  copyright notice, this list of conditions and the
	  following disclaimer in the documentation and/or other
	  materials provided with the distribution.
	- Neither the name of the Webworks, LLC. nor the names of
	  its contributors may be used to endorse or promote products
	  derived from this software without specific prior written
	  permission.

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND
	CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
	MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
	CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
	SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
	BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
	HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
	OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

	============================================================
	This is a derivative work.  Following is the original
	Copyright notice.
	============================================================

	Copyright (c) 2004 James F. Dew <jdew@yggdrasil.ca>

	Permission to use, copy, modify, and distribute this software for any
	purpose with or without fee is hereby granted, provided that the above
	copyright notice and this permission notice appear in all copies.

	THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
	WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
	MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
	ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
	WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
	ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
	OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
--->
<!---
	SPECIAL NOTE FOR HEADLESS SYSTEMS
	---------------------------------
	If you get a "cannot connect to X11 server" when running certain
	parts of this component under Bluedragon (Linux), you must
	add "-Djava.awt.headless=true" to the java startup line in
	<bluedragon>/bin/StartBluedragon.sh.  This isssue is discussed
	in the Bluedragon Installation Guide section 3.8.1 for
	Bluedragon 6.2.1.

	Bluedragon may also report a ClassNotFound exception when trying
	to instantiate the java.awt.image.BufferedImage class.  This is
	most likely the same issue.

	If you get "This graphics environment can be used only in the
	software emulation mode" when calling certain parts of this
	component under Coldfusion MX, you should refer to Technote
	ID #18747:  http://www.macromedia.com/go/tn_18747
--->

<cfcomponent displayname="Image">

<cfset variables.throwOnError = "Yes">
<cfset variables.defaultJpegCompression = "90">
<cfset variables.interpolation = "bicubic">
<cfset variables.textAntiAliasing = "Yes">
<cfset variables.tempDirectory = "#expandPath(".")#">

<cfset variables.javanulls = "no">
<cftry>
	<cfset nullvalue = javacast("null","")>
	<cfset variables.javanulls = "yes">
	<cfcatch type="any">
		<cfset variables.javanulls = "no">
		<!--- javacast null not supported, so filters won't work --->
	</cfcatch>
</cftry>
<!---
<cfif javanulls>
	<cfset variables.blurFilter = createObject("component","blurFilter")>
	<cfset variables.sharpenFilter = createObject("component","sharpenFilter")>
	<cfset variables.posterizeFilter = createObject("component","posterizeFilter")>
</cfif>
--->

<cfset variables.Math = createobject("java", "java.lang.Math")>
<cfset variables.arrObj = createobject("java", "java.lang.reflect.Array")>
<cfset variables.floatClass = createobject("java", "java.lang.Float").TYPE>
<cfset variables.intClass = createobject("java", "java.lang.Integer").TYPE>
<cfset variables.shortClass = createobject("java", "java.lang.Short").TYPE>

<cffunction name="getImageInfo" access="public" output="true" returntype="struct" hint="Rotate an image (+/-)90, (+/-)180, or (+/-)270 degrees.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var img = "">

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset img = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
		<cfset retVal.metaData = getImageMetadata(loadImage.inFile)>
	<cfelse>
		<cfset img = objImage>
		<cfset retVal.metadata = getImageMetadata("")>
	</cfif>
	<cftry>
		<cfset retVal.width = img.getWidth()>
		<cfset retVal.height = img.getHeight()>
		<cfset retVal.colorModel = img.getColorModel().toString()>
		<cfset retVal.colorspace = img.getColorModel().getColorSpace().toString()>
		<cfset retVal.objColorModel = img.getColorModel()>
		<cfset retVal.objColorspace = img.getColorModel().getColorSpace()>
		<cfset retVal.sampleModel = img.getSampleModel().toString()>
		<cfset retVal.imageType = img.getType()>
		<cfset retVal.misc = img.toString()>
		<cfset retVal.canModify = true>
		<cfreturn retVal>
		<cfcatch type="any">
			<cfset retVal = throw( "#cfcatch.message#: #cfcatch.detail#")>
			<cfreturn retVal>
		</cfcatch>
	</cftry>
</cffunction>

<cffunction name="getImageMetadata" access="private" output="false" returntype="query">
	<cfargument name="inFile" required="yes" type="Any"><!--- java.io.File --->

	<cfset var retQry = queryNew("dirName,tagName,tagValue")>
	<cfset var paths = arrayNew(1)>
	<cfset var loader = "">
	<cfset var JpegMetadatareader = "">
	<cfset var myMetadata = "">
	<cfset var directories = "">
	<cfset var currentDirectory = "">
	<cfset var tags = "">
	<cfset var currentTag = "">
	<cfset var tagName = "">

	<cftry>
	<cfscript>
		paths = arrayNew(1);
		paths[1] = expandPath("metadata-extractor-2.3.1.jar");
		loader = createObject("component", "javaloader.JavaLoader").init(paths);

		//at this stage we only have access to the class, but we don't have an instance
		JpegMetadataReader = loader.create("com.drew.imaging.jpeg.JpegMetadataReader");

		myMetaData = JpegMetadataReader.readMetadata(inFile);
		directories = myMetaData.getDirectoryIterator();
		while (directories.hasNext()) {
			currentDirectory = directories.next();
			tags = currentDirectory.getTagIterator();
			while (tags.hasNext()) {
				currentTag = tags.next();
				if (currentTag.getTagName() DOES NOT CONTAIN "Unknown") { //leave out the junk data
					queryAddRow(retQry);
					querySetCell(retQry,"dirName",replace(currentTag.getDirectoryName(),' ','_','ALL'));
					tagName = replace(currentTag.getTagName(),' ','','ALL');
					tagName = replace(tagName,'/','','ALL');
					querySetCell(retQry,"tagName",tagName);
					querySetCell(retQry,"tagValue",currentTag.getDescription());
				}
			}
		}
		return retQry;
		</cfscript>
		<cfcatch type="any">
			<cfreturn retQry />
		</cfcatch>
	</cftry>
</cffunction>

<cffunction name="flipHorizontal" access="public" output="true" returntype="struct" hint="Flip an image horizontally.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfreturn flipflop(objImage, inputFile, outputFile, "horizontal", jpegCompression)>
</cffunction>

<cffunction name="flipVertical" access="public" output="true" returntype="struct" hint="Flop an image vertically.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfreturn flipflop(objImage, inputFile, outputFile, "vertical", jpegCompression)>
</cffunction>

<cffunction name="scaleWidth" access="public" output="true" returntype="struct" hint="Scale an image to a specific width.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="newWidth" required="yes" type="numeric">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfreturn resize(objImage, inputFile, outputFile, newWidth, 0, "false", "false", jpegCompression)>
</cffunction>

<cffunction name="scaleHeight" access="public" output="true" returntype="struct" hint="Scale an image to a specific height.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="newHeight" required="yes" type="numeric">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfreturn resize(objImage, inputFile, outputFile, 0, newHeight, "false", "false", jpegCompression)>
</cffunction>

<cffunction name="resize" access="public" output="true" returntype="struct" hint="Resize an image to a specific width and height.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="newWidth" required="yes" type="numeric">
	<cfargument name="newHeight" required="yes" type="numeric">
	<cfargument name="preserveAspect" required="no" type="boolean" default="FALSE">
	<cfargument name="cropToExact" required="no" type="boolean" default="FALSE">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var saveImage = StructNew()>
	<cfset var at = "">
	<cfset var op = "">
	<cfset var w = "">
	<cfset var h = "">
	<cfset var scale = 1>
	<cfset var scaleX = 1>
	<cfset var scaleY = 1>
	<cfset var resizedImage = "">
	<cfset var rh = getRenderingHints()>
	<cfset var specifiedWidth = arguments.newWidth>
	<cfset var specifiedHeight = arguments.newHeight>
	<cfset var imgInfo = "">
	<cfset var img = "">
	<cfset var cropImageResult = "">
	<cfset var cropOffsetX = "">
	<cfset var cropOffsetY = "">

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset img = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset img = objImage>
	</cfif>
	<cfif img.getType() eq 0>
		<cfset img = convertImageObject(img,img.TYPE_3BYTE_BGR)>
	</cfif>
	<cfscript>
		resizedImage = CreateObject("java", "java.awt.image.BufferedImage");
		at = CreateObject("java", "java.awt.geom.AffineTransform");
		op = CreateObject("java", "java.awt.image.AffineTransformOp");

		w = img.getWidth();
		h = img.getHeight();

		if (preserveAspect and cropToExact and newHeight gt 0 and newWidth gt 0)
		{
			if (w / h gt newWidth / newHeight){
				newWidth = 0;
			} else if (w / h lt newWidth / newHeight){
				newHeight = 0;
		    }
		} else if (preserveAspect and newHeight gt 0 and newWidth gt 0) {
			if (w / h gt newWidth / newHeight){
				newHeight = 0;
			} else if (w / h lt newWidth / newHeight){
				newWidth = 0;
		    }
		}
		if (newWidth gt 0 and newHeight eq 0) {
			scale = newWidth / w;
			w = newWidth;
			h = round(h*scale);
		} else if (newHeight gt 0 and newWidth eq 0) {
			scale = newHeight / h;
			h = newHeight;
			w = round(w*scale);
		} else if (newHeight gt 0 and newWidth gt 0) {
			w = newWidth;
			h = newHeight;
		} else {
			retVal = throw( retVal.errorMessage);
			return retVal;
		}
		resizedImage.init(javacast("int",w),javacast("int",h),img.getType());

		w = w / img.getWidth();
		h = h / img.getHeight();



		op.init(at.getScaleInstance(javacast("double",w),javacast("double",h)), rh);
		// resizedImage = op.createCompatibleDestImage(img, img.getColorModel());
		op.filter(img, resizedImage);

		imgInfo = getimageinfo(resizedImage, "");
		if (imgInfo.errorCode gt 0)
		{
			return imgInfo;
		}

		cropOffsetX = max( Int( (imgInfo.width/2) - (newWidth/2) ), 0 );
		cropOffsetY = max( Int( (imgInfo.height/2) - (newHeight/2) ), 0 );
		// There is a chance that the image is exactly the correct
		// width and height and don't need to be cropped
		if
			(
			preserveAspect and cropToExact
			and
			(imgInfo.width IS NOT specifiedWidth OR imgInfo.height IS NOT specifiedHeight)
			)
		{
			// Get the correct offset to get the center of the image
			cropOffsetX = max( Int( (imgInfo.width/2) - (specifiedWidth/2) ), 0 );
			cropOffsetY = max( Int( (imgInfo.height/2) - (specifiedHeight/2) ), 0 );

			cropImageResult = crop( resizedImage, "", "", cropOffsetX, cropOffsetY, specifiedWidth, specifiedHeight );
			if ( cropImageResult.errorCode GT 0)
			{
				return cropImageResult;
			} else {
				resizedImage = cropImageResult.img;
			}
		}
		if (outputFile eq "")
		{
			retVal.img = resizedImage;
			return retVal;
		} else {
			saveImage = writeImage(outputFile, resizedImage, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>

<cffunction name="crop" access="public" output="true" returntype="struct" hint="Crop an image.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="fromX" required="yes" type="numeric">
	<cfargument name="fromY" required="yes" type="numeric">
	<cfargument name="newWidth" required="yes" type="numeric">
	<cfargument name="newHeight" required="yes" type="numeric">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var saveImage = StructNew()>
	<cfset var croppedImage = "">
	<cfset var rh = getRenderingHints()>
	<cfset var img = "">

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset img = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset img = objImage>
	</cfif>
	<cfif img.getType() eq 0>
		<cfset img = convertImageObject(img,img.TYPE_3BYTE_BGR)>
	</cfif>
	<cfscript>
		if (fromX + newWidth gt img.getWidth()
			OR
			fromY + newHeight gt img.getHeight()
			)
		{
			retval = throw( "The cropped image dimensions go beyond the original image dimensions.");
			return retVal;
		}
		croppedImage = img.getSubimage(javaCast("int", fromX), javaCast("int", fromY), javaCast("int", newWidth), javaCast("int", newHeight) );
		if (outputFile eq "")
		{
			retVal.img = croppedImage;
			return retVal;
		} else {
			saveImage = writeImage(outputFile, croppedImage, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>

<cffunction name="rotate" access="public" output="true" returntype="struct" hint="Rotate an image (+/-)90, (+/-)180, or (+/-)270 degrees.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="degrees" required="yes" type="numeric">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var img = "">
	<cfset var loadImage = StructNew()>
	<cfset var saveImage = StructNew()>
	<cfset var at = "">
	<cfset var op = "">
	<cfset var w = 0>
	<cfset var h = 0>
	<cfset var iw = 0>
	<cfset var ih = 0>
	<cfset var x = 0>
	<cfset var y = 0>
	<cfset var rotatedImage = "">
	<cfset var rh = getRenderingHints()>

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset img = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset img = objImage>
	</cfif>
	<cfif img.getType() eq 0>
		<cfset img = convertImageObject(img,img.TYPE_3BYTE_BGR)>
	</cfif>
	<cfif ListFind("-270,-180,-90,90,180,270",degrees) is 0>
		<cfset retVal = throw( "At this time, image.cfc only supports rotating images in 90 degree increments.")>
		<cfreturn retVal>
	</cfif>

	<cfscript>
		rotatedImage = CreateObject("java", "java.awt.image.BufferedImage");
		at = CreateObject("java", "java.awt.geom.AffineTransform");
		op = CreateObject("java", "java.awt.image.AffineTransformOp");

		iw = img.getWidth(); h = iw;
		ih = img.getHeight(); w = ih;

		if(arguments.degrees eq 180) { w = iw; h = ih; }

		x = (w/2)-(iw/2);
		y = (h/2)-(ih/2);

		rotatedImage.init(javacast("int",w),javacast("int",h),img.getType());

		at.rotate(arguments.degrees * 0.0174532925,w/2,h/2);
		at.translate(x,y);
		op.init(at, rh);

		op.filter(img, rotatedImage);

		if (outputFile eq "")
		{
			retVal.img = rotatedImage;
			return retVal;
		} else {
			saveImage = writeImage(outputFile, rotatedImage, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>

<cffunction name="convert" access="public" output="true" returntype="struct" hint="Convert an image from one format to another.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var saveImage = StructNew()>
	<cfset var img = "">

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset img = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset img = objImage>
	</cfif>

	<cfscript>
		if (outputFile eq "")
		{
			retVal = throw( "The convert method requires a valid output filename.");
			return retVal;
		} else {
			saveImage = writeImage(outputFile, img, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>

<cffunction name="setOption" access="public" output="true" returnType="void" hint="Sets values for allowed CFC options.">
	<cfargument name="key" type="string" required="yes">
	<cfargument name="val" type="string" required="yes">

	<cfset var validKeys = "interpolation,textantialiasing,throwonerror,defaultJpegCompression">
	<cfset arguments.key = lcase(trim(arguments.key))>
	<cfset arguments.val = lcase(trim(arguments.val))>
	<cfif listFind(validKeys, arguments.key) gt 0>
		<cfset variables[arguments.key] = arguments.val>
	</cfif>
</cffunction>

<cffunction name="getOption" access="public" output="true" returnType="any" hint="Returns the current value for the specified CFC option.">
	<cfargument name="key" type="string" required="yes">

	<cfset var validKeys = "interpolation,textantialiasing,throwonerror,defaultJpegCompression">
	<cfset arguments.key = lcase(trim(arguments.key))>
	<cfif listFindNoCase(validKeys, arguments.key) gt 0>
		<cfreturn variables[arguments.key]>
	<cfelse>
		<cfreturn "">
	</cfif>
</cffunction>

<cffunction name="getRenderingHints" access="private" output="true" returnType="any" hint="Internal method controls various aspects of rendering quality.">
	<cfset var rh = CreateObject("java","java.awt.RenderingHints")>
	<cfset var initMap = CreateObject("java","java.util.HashMap")>
	<cfset initMap.init()>
	<cfset rh.init(initMap)>
	<cfset rh.put(rh.KEY_ALPHA_INTERPOLATION, rh.VALUE_ALPHA_INTERPOLATION_QUALITY)> <!--- QUALITY, SPEED, DEFAULT --->
	<cfset rh.put(rh.KEY_ANTIALIASING, rh.VALUE_ANTIALIAS_ON)> <!--- ON, OFF, DEFAULT --->
	<cfset rh.put(rh.KEY_COLOR_RENDERING, rh.VALUE_COLOR_RENDER_QUALITY)>  <!--- QUALITY, SPEED, DEFAULT --->
	<cfset rh.put(rh.KEY_DITHERING, rh.VALUE_DITHER_DEFAULT)> <!--- DISABLE, ENABLE, DEFAULT --->
	<cfset rh.put(rh.KEY_RENDERING, rh.VALUE_RENDER_QUALITY)> <!--- QUALITY, SPEED, DEFAULT --->
	<cfset rh.put(rh.KEY_FRACTIONALMETRICS, rh.VALUE_FRACTIONALMETRICS_DEFAULT)> <!--- DISABLE, ENABLE, DEFAULT --->
	<cfset rh.put(rh.KEY_STROKE_CONTROL, rh.VALUE_STROKE_DEFAULT)>

	<cfif variables.textAntiAliasing>
		<cfset rh.put(rh.KEY_TEXT_ANTIALIASING, rh.VALUE_TEXT_ANTIALIAS_ON)>
	<cfelse>
		<cfset rh.put(rh.KEY_TEXT_ANTIALIASING, rh.VALUE_TEXT_ANTIALIAS_OFF)>
	</cfif>

	<cfif variables.interpolation eq "nearest_neighbor">
		<cfset rh.put(rh.KEY_INTERPOLATION, rh.VALUE_INTERPOLATION_NEAREST_NEIGHBOR)>
	<cfelseif variables.interpolation eq "bilinear">
		<cfset rh.put(rh.KEY_INTERPOLATION, rh.VALUE_INTERPOLATION_BILINEAR)>
	<cfelse>
		<cfset rh.put(rh.KEY_INTERPOLATION, rh.VALUE_INTERPOLATION_BICUBIC)>
	</cfif>

	<cfreturn rh>
</cffunction>

<cffunction name="readImage" access="public" output="true" returntype="struct" hint="Reads an image from a local file.  Requires an absolute path.">
	<cfargument name="source" required="yes" type="string">
	<cfargument name="forModification" required="no" type="boolean" default="yes">

	<cfif isURL(source)>
		<cfreturn readImageFromURL(source, forModification)>
	<cfelse>
		<cfreturn readImageFromFile(source, forModification)>
	</cfif>
</cffunction>

<cffunction name="readImageFromFile" access="private" output="true" returntype="struct" hint="Reads an image from a local file.  Requires an absolute path.">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="forModification" required="no" type="boolean" default="yes">

	<cfset var retVal = StructNew()>
	<cfset var img = "">
	<cfset var inFile = "">
	<cfset var filename = getFileFromPath(inputFile)>
	<cfset var extension = lcase(listLast(inputFile,"."))>
	<cfset var imageIO = CreateObject("java", "javax.imageio.ImageIO")>
	<cfset var validExtensionsToRead = ArrayToList(imageIO.getReaderFormatNames())>

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif not fileExists(arguments.inputFile)>
		<cfset retVal = throw("The specified file #Chr(34)##arguments.inputFile##Chr(34)# could not be found.")>
		<cfreturn retVal>
	<cfelseif listLen(filename,".") lt 2>
		<cfset retVal = throw("Sorry, image files without extensions cannot be manipulated.")>
		<cfreturn retVal>
	<cfelseif listFindNoCase(validExtensionsToRead, extension) is 0>
		<cfset retVal = throw("Java is unable to read #extension# files.")>
		<cfreturn retVal>
	<cfelseif NOT fileExists(arguments.inputFile)>
		<cfset retVal = throw("The specified input file does not exist.")>
		<cfreturn retVal>
	<cfelse>
		<cfset img = CreateObject("java", "java.awt.image.BufferedImage")>
		<cfset inFile = CreateObject("java", "java.io.File")>
		<cfset inFile.init(arguments.inputFile)>
		<cfif NOT inFile.canRead()>
			<cfset retVal = throw("Unable to open source file #Chr(34)##arguments.inputFile##Chr(34)#.")>
			<cfreturn retVal>
		<cfelse>
			<cftry>
				<cfset img = imageIO.read(inFile)>
				<cfcatch type="any">
					<cfset retval = throw("An error occurred attempting to read the specified image.  #cfcatch.message# - #cfcatch.detail#")>
					<cfreturn retVal>
				</cfcatch>
			</cftry>
			<cfset retVal.img = img>
			<cfset retVal.inFile = inFile>
			<cfreturn retVal>
		</cfif>
	</cfif>
</cffunction>

<cffunction name="readImageFromURL" access="private" output="true" returntype="struct" hint="Read an image from a URL.  Requires an absolute URL.">
	<cfargument name="inputURL" required="yes" type="string">
	<cfargument name="forModification" required="no" type="boolean" default="yes">

	<cfset var retVal = StructNew()>
	<cfset var img = CreateObject("java", "java.awt.image.BufferedImage")>
	<cfset var inURL	= CreateObject("java", "java.net.URL")>
	<cfset var imageIO = CreateObject("java", "javax.imageio.ImageIO")>

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">


	<cfset inURL.init(arguments.inputURL)>
	<cftry>
		<cfset img = imageIO.read(inURL)>
		<cfcatch type="any">
			<cfset retval = throw("An error occurred attempting to read the specified image.  #cfcatch.message# - #cfcatch.detail#")>
			<cfreturn retVal>
		</cfcatch>
	</cftry>
	<cfset retVal.img = img>
	<cfreturn retVal>
</cffunction>

<cffunction name="writeImage" access="public" output="true" returntype="struct" hint="Write an image to disk.">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="img" required="yes" type="any">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var outFile = "">
	<cfset var filename = getFileFromPath(outputFile)>
	<cfset var extension = lcase(listLast(filename,"."))>
	<cfset var imageIO = CreateObject("java", "javax.imageio.ImageIO")>
	<cfset var validExtensionsToWrite = ArrayToList(imageIO.getWriterFormatNames())>
	<!--- used for jpeg output method --->
	<cfset var out = "">
	<cfset var fos = "">
	<cfset var JPEGCodec = "">
	<cfset var encoder = "">
	<cfset var param = "">
	<cfset var quality = javacast("float", jpegCompression/100)>
	<cfset var tempOutputFile = "#variables.tempDirectory#\#createUUID()#.#extension#">

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif listFindNoCase(validExtensionsToWrite, extension) eq 0>
		<cfset throw("Java is unable to write #extension# files.  Valid formats include: #validExtensionsToWrite#")>
	</cfif>

	<cfif extension neq "jpg" and extension neq "jpeg">
		<!---
			Simple output method for non jpeg images
		--->
		<cfset outFile = CreateObject("java", "java.io.File")>
		<cfset outFile.init(tempOutputFile)>
		<cfset imageIO.write(img, extension, outFile)>
	<cfelse>
		<cfscript>
			/*
				JPEG output method handles compression
			*/
			out = createObject("java", "java.io.BufferedOutputStream");
			fos = createObject("java", "java.io.FileOutputStream");
			fos.init(tempOutputFile);
			out.init(fos);
			JPEGCodec = createObject("java", "com.sun.image.codec.jpeg.JPEGCodec");
			encoder = JPEGCodec.createJPEGEncoder(out);
		    param = encoder.getDefaultJPEGEncodeParam(img);
		    param.setQuality(quality, false);
		    encoder.setJPEGEncodeParam(param);
		    encoder.encode(img);
		    out.close();
		</cfscript>
	</cfif>
	<!--- move file to its final destination --->
	<cffile action="MOVE" source="#tempOutputFile#" destination="#arguments.outputFile#">
	<cfreturn retVal>
</cffunction>

<cffunction name="flipflop" access="private" output="true" returntype="struct" hint="Internal method used for flipping and flopping images.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="direction" required="yes" type="string"><!--- horizontal or vertical --->
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var saveImage = StructNew()>
	<cfset var flippedImage = "">
	<cfset var rh = getRenderingHints()>
	<cfset var img = "">

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset img = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset img = objImage>
	</cfif>
	<cfif img.getType() eq 0>
		<cfset img = convertImageObject(img,img.TYPE_3BYTE_BGR)>
	</cfif>
	<cfscript>
		flippedImage = CreateObject("java", "java.awt.image.BufferedImage");
		at = CreateObject("java", "java.awt.geom.AffineTransform");
		op = CreateObject("java", "java.awt.image.AffineTransformOp");

		flippedImage.init(img.getWidth(), img.getHeight(), img.getType());

		if (direction eq "horizontal") {
			at = at.getScaleInstance(-1, 1);
			at.translate(-img.getWidth(), 0);
		} else {
			at = at.getScaleInstance(1,-1);
			at.translate(0, -img.getHeight());
		}
		op.init(at, rh);
		op.filter(img, flippedImage);

		if (outputFile eq "")
		{
			retVal.img = flippedImage;
			return retVal;
		} else {
			saveImage = writeImage(outputFile, flippedImage, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>



<cffunction name="filterFastBlur" access="public" output="true" returntype="struct" hint="Internal method used for flipping and flopping images.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="blurAmount" required="yes" type="numeric">
	<cfargument name="iterations" required="yes" type="numeric">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var saveImage = StructNew()>
	<cfset var srcImage = "">
	<cfset var destImage = "">
	<cfset var rh = getRenderingHints()>

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif NOT variables.javanulls>
		<cfset throw("Sorry, but the blur filter is not supported on this platform.")>
	</cfif>
	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset srcImage = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset srcImage = objImage>
	</cfif>
	<cfif srcImage.getType() eq 0>
		<cfset srcImage = convertImageObject(srcImage,srcImage.TYPE_3BYTE_BGR)>
	</cfif>

	<cfscript>

		// initialize the blur filter
		variables.blurFilter.init(arguments.blurAmount);
		// move the source image into the destination image
		// so we can repeatedly blur it.
		destImage = srcImage;

		for (i=1; i lte iterations; i=i+1)
		{
			// do the blur i times
			destImage = variables.blurFilter.filter(destImage);
		}


		if (outputFile eq "")
		{
			// return the image object
			retVal.img = destImage;
			return retVal;
		} else {
			// write the image object to the specified file.
			saveImage = writeImage(outputFile, destImage, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>

<cffunction name="filterSharpen" access="public" output="true" returntype="struct" hint="Internal method used for flipping and flopping images.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var saveImage = StructNew()>
	<cfset var srcImage = "">
	<cfset var destImage = "">
	<cfset var rh = getRenderingHints()>

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif NOT variables.javanulls>
		<cfset throw("Sorry, but the blur filter is not supported on this platform.")>
	</cfif>

	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset srcImage = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset srcImage = objImage>
	</cfif>
	<cfif srcImage.getType() eq 0>
		<cfset srcImage = convertImageObject(srcImage,srcImage.TYPE_3BYTE_BGR)>
	</cfif>

	<cfscript>
		// initialize the sharpen filter
		variables.sharpenFilter.init();

		destImage = variables.sharpenFilter.filter(srcImage);


		if (outputFile eq "")
		{
			// return the image object
			retVal.img = destImage;
			return retVal;
		} else {
			// write the image object to the specified file.
			saveImage = writeImage(outputFile, destImage, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>


<cffunction name="filterPosterize" access="public" output="true" returntype="struct" hint="Internal method used for flipping and flopping images.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="amount" required="yes" type="string">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var saveImage = StructNew()>
	<cfset var srcImage = "">
	<cfset var destImage = "">
	<cfset var rh = getRenderingHints()>

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif NOT variables.javanulls>
		<cfset throw("Sorry, but the blur filter is not supported on this platform.")>
	</cfif>

	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset srcImage = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset srcImage = objImage>
	</cfif>
	<cfif srcImage.getType() eq 0>
		<cfset srcImage = convertImageObject(srcImage,srcImage.TYPE_3BYTE_BGR)>
	</cfif>
	<cfif srcImage.getType() neq 5>
		<cfset throw("ImageCFC cannot posterize this image type (#srcImage.getType()#)")>
	</cfif>
	<cfscript>
		// initialize the posterize filter
		variables.posterizeFilter.init(arguments.amount);

		destImage = variables.posterizeFilter.filter(srcImage);


		if (outputFile eq "")
		{
			// return the image object
			retVal.img = destImage;
			return retVal;
		} else {
			// write the image object to the specified file.
			saveImage = writeImage(outputFile, destImage, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>


<cffunction name="addText" access="public" output="true" returntype="struct" hint="Add text to an image.">
	<cfargument name="objImage" required="yes" type="Any">
	<cfargument name="inputFile" required="yes" type="string">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="x" required="yes" type="numeric">
	<cfargument name="y" required="yes" type="numeric">
	<cfargument name="fontDetails" required="yes" type="struct">
	<cfargument name="content" required="yes" type="String">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var img = "">
	<cfset var saveImage = StructNew()>
	<cfset var g2d = "">
	<cfset var bgImage = "">
	<cfset var fontImage = "">
	<cfset var overlayImage = "">
	<cfset var Color = "">
	<cfset var font = "">
	<cfset var font_stream = "">
	<cfset var ac = "">
	<cfset var rgb = "">

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfparam name="arguments.fontDetails.size" default="12">
	<cfparam name="arguments.fontDetails.color" default="black">
	<cfparam name="arguments.fontDetails.fontFile" default="">
	<cfparam name="arguments.fontDetails.fontName" default="serif">

	<cfif arguments.fontDetails.fontFile neq "" and not fileExists(arguments.fontDetails.fontFile)>
		<cfset retVal = throw("The specified font file #Chr(34)##arguments.inputFile##Chr(34)# could not be found on the server.")>
		<cfreturn retVal>
	</cfif>
	<cftry>
		<cfset rgb = getRGB(arguments.fontDetails.color)>
		<cfcatch type="any">
			<cfset retVal = throw("Invalid color #Chr(34)##arguments.fontDetails.color##Chr(34)#")>
			<cfreturn retVal>
		</cfcatch>
	</cftry>
	<cfif inputFile neq "">
		<cfset loadImage = readImage(inputFile, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset img = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset img = objImage>
	</cfif>
	<cfif img.getType() eq 0>
		<cfset img = convertImageObject(img,img.TYPE_3BYTE_BGR)>
	</cfif>
	<cfscript>
		// load objects
		bgImage = CreateObject("java", "java.awt.image.BufferedImage");
		fontImage = CreateObject("java", "java.awt.image.BufferedImage");
		overlayImage = CreateObject("java", "java.awt.image.BufferedImage");
		Color = CreateObject("java","java.awt.Color");
		font = createObject("java","java.awt.Font");
		font_stream = createObject("java","java.io.FileInputStream");
		ac = CreateObject("Java", "java.awt.AlphaComposite");

		// set up basic needs
		fontColor = Color.init(javacast("int", rgb.red), javacast("int", rgb.green), javacast("int", rgb.blue));

		if (fontDetails.fontFile neq "")
		{
			font_stream.init(arguments.fontDetails.fontFile);
			font = font.createFont(font.TRUETYPE_FONT, font_stream);
			font = font.deriveFont(javacast("float",arguments.fontDetails.size));
		} else {
			font.init(fontDetails.fontName, evaluate(fontDetails.style), fontDetails.size);
		}
		// get font metrics using a 1x1 bufferedImage
		fontImage.init(1,1,img.getType());
		g2 = fontImage.createGraphics();
		g2.setRenderingHints(getRenderingHints());
		fc = g2.getFontRenderContext();
		bounds = font.getStringBounds(content,fc);

		g2 = img.createGraphics();
		g2.setRenderingHints(getRenderingHints());
		g2.setFont(font);
		g2.setColor(fontColor);
		// in case you want to change the alpha
		// g2.setComposite(ac.getInstance(ac.SRC_OVER, 0.50));

		// the location (arguments.fontDetails.size+y) doesn't really work
		// the way I want it to.
		g2.drawString(content,javacast("int",x),javacast("int",arguments.fontDetails.size+y));

		if (outputFile eq "")
		{
			retVal.img = img;
			return retVal;
		} else {
			saveImage = writeImage(outputFile, img, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>

<cffunction name="watermark" access="public" output="false">
	<cfargument name="objImage1" required="yes" type="Any">
	<cfargument name="objImage2" required="yes" type="Any">
	<cfargument name="inputFile1" required="yes" type="string">
	<cfargument name="inputFile2" required="yes" type="string">
	<cfargument name="alpha" required="yes" type="numeric">
	<cfargument name="placeAtX" required="yes" type="numeric">
	<cfargument name="placeAtY" required="yes" type="numeric">
	<cfargument name="outputFile" required="yes" type="string">
	<cfargument name="jpegCompression" required="no" type="numeric" default="#variables.defaultJpegCompression#">

	<cfset var retVal = StructNew()>
	<cfset var loadImage = StructNew()>
	<cfset var originalImage = "">
	<cfset var wmImage = "">
	<cfset var saveImage = StructNew()>
	<cfset var ac = "">
	<cfset var rh = getRenderingHints()>

	<cfset retVal.errorCode = 0>
	<cfset retVal.errorMessage = "">

	<cfif inputFile1 neq "">
		<cfset loadImage = readImage(inputFile1, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset originalImage = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset originalImage = objImage1>
	</cfif>
	<cfif originalImage.getType() eq 0>
		<cfset originalImage = convertImageObject(originalImage,originalImage.TYPE_3BYTE_BGR)>
	</cfif>

	<cfif inputFile2 neq "">
		<cfset loadImage = readImage(inputFile2, "NO")>
		<cfif loadImage.errorCode is 0>
			<cfset wmImage = loadImage.img>
		<cfelse>
			<cfset retVal = throw(loadImage.errorMessage)>
			<cfreturn retVal>
		</cfif>
	<cfelse>
		<cfset wmImage = objImage2>
	</cfif>
	<cfif wmImage.getType() eq 0>
		<cfset wmImage = convertImageObject(wmImage,wmImage.TYPE_3BYTE_BGR)>
	</cfif>


	<cfscript>
		at = CreateObject("java", "java.awt.geom.AffineTransform");
		op = CreateObject("java", "java.awt.image.AffineTransformOp");
		ac = CreateObject("Java", "java.awt.AlphaComposite");
		gfx = originalImage.getGraphics();
		gfx.setComposite(ac.getInstance(ac.SRC_OVER, alpha));

		at.init();
		// op.init(at,op.TYPE_BILINEAR);
		op.init(at, rh);

		gfx.drawImage(wmImage, op, javaCast("int",arguments.placeAtX), javacast("int", arguments.placeAtY));

		gfx.dispose();

		if (outputFile eq "")
		{
			retVal.img = originalImage;
			return retVal;
		} else {
			saveImage = writeImage(outputFile, originalImage, jpegCompression);
			if (saveImage.errorCode gt 0)
			{
				return saveImage;
			} else {
				return retVal;
			}
		}
	</cfscript>
</cffunction>

<cffunction name="isURL" access="private" output="false" returnType="boolean">
	<cfargument name="stringToCheck" required="yes" type="string">
	<cfif REFindNoCase("^(((https?:)\/\/))[-[:alnum:]\?%,\.\/&##!@:=\+~_]+[A-Za-z0-9\/]$",stringToCheck) NEQ 0>
		<cfreturn true>
	<cfelse>
		<cfreturn false>
	</cfif>
</cffunction>

<!--- function returns RGB values in a structure for hex or the 16
	HTML named colors --->
<cffunction name="getRGB" access="private" output="true" returnType="struct">
	<cfargument name="color" type="string" required="yes">

	<cfset var retVal = structNew()>
	<cfset var cnt = 0>
	<cfset var namedColors = "aqua,black,blue,fuchsia,gray,green,lime,maroon,navy,olive,purple,red,silver,teal,white,yellow">
	<cfset var namedColorsHexValues = "00ffff,000000,0000ff,ff00ff,808080,008000,00ff00,800000,000080,808080,ff0000,c0c0c0,008080,ffffff,ffff00">

	<cfset retVal.red = 0>
	<cfset retVal.green = 0>
	<cfset retVal.blue = 0>

	<cfset arguments.color = trim(arguments.color)>
	<cfif len(arguments.color) is 0>
		<cfreturn retVal>
	<cfelseif listFind(namedColors, arguments.color) gt 0>
		<cfset arguments.color = listGetAt(namedColorsHexValues, listFind(namedColors, arguments.color))>
	</cfif>
	<cfif left(arguments.color,1) eq "##">
		<cfset arguments.color = right(arguments.color,len(arguments.color)-1)>
	</cfif>
	<cfif len(arguments.color) neq 6>
		<cfreturn retVal>
	<cfelse>
		<cftry>
			<cfset retVal.red = InputBaseN(mid(arguments.color,1,2),16)>
			<cfset retVal.green = InputBaseN(mid(arguments.color,3,2),16)>
			<cfset retVal.blue = InputBaseN(mid(arguments.color,5,2),16)>
			<cfcatch type="any">
				<cfset retVal.red = 0>
				<cfset retVal.green = 0>
				<cfset retVal.blue = 0>
				<cfreturn retVal>
			</cfcatch>
		</cftry>
	</cfif>
	<cfreturn retVal>
</cffunction>

<cffunction name="throw" access="private" output="false" returnType="struct">
	<cfargument name="detail" type="string" required="yes">
	<cfargument name="force" type="boolean" required="no" default="no">

	<cfset var retVal = StructNew()>

	<cfif variables.throwOnError or arguments.force>
		<cfthrow detail="#arguments.detail#" message="#arguments.detail#">
	<cfelse>
		<cfset retVal.errorCode = 1>
		<cfset retVal.errorMessage = arguments.detail>
		<cfreturn retVal>
	</cfif>
</cffunction>

<cffunction name="debugDump" access="private">
	<cfdump var="#arguments#"><cfabort>
</cffunction>

<cffunction name="convertImageObject" access="private" output="false" returnType="any">
	<cfargument name="bImage" type="Any" required="yes">
	<cfargument name="type" type="numeric" required="yes">

	<cfscript>
	// convert the image to a specified BufferedImage type and return it

	var width = bImage.getWidth();
	var height = bImage.getHeight();
	var newImage = createObject("java","java.awt.image.BufferedImage").init(javacast("int",width), javacast("int",height), javacast("int",type));
	// int[] rgbArray = new int[width*height];
	var rgbArray = variables.arrObj.newInstance(variables.intClass, javacast("int",width*height));

	bImage.getRGB(
		javacast("int",0),
		javacast("int",0),
		javacast("int",width),
		javacast("int",height),
		rgbArray,
		javacast("int",0),
		javacast("int",width)
		);
	newImage.setRGB(
		javacast("int",0),
		javacast("int",0),
		javacast("int",width),
		javacast("int",height),
		rgbArray,
		javacast("int",0),
		javacast("int",width)
		);
	return newImage;
	</cfscript>

</cffunction>

</cfcomponent>
