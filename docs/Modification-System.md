OCMOD is a system that allows store owners to be able to modify their store by uploading a compressed file that contains XML, SQL and PHP files.

If a OCMOD is developed correctly it can modify how a users OpenCart store works without changing any of the core files. This means that if a modification is removed none of the original OpenCart files need to be restored or fixed.

OCMOD is based upon Qphoria's VQMOD system.

### Differences between OCMOD and VQMOD

OCMOD was created as a simplified cut down version of VQMOD.

Main Feature Differences:

**Has** 

Replace, Before, After, Regex, Offset, Limit, ignoreif, error

**Missing**

Attributes top, bottom, ibefore, iafter

You should check the XML section of the documentation to see OCMODS full feature list.

VQMOD project home page can be found here:

https://github.com/vqmod/vqmod/wiki/Scripting

## OCMOD Files

OCMOD files can be uploaded via the opencart admin under:

Extensions / Installer

For a OCMOD file to be uploaded the file extension must be .ocmod.zip. This is to avoid none OCMOD files from being uploaded to a store users admin.

### File Structure

Example file structure for OCMOD compressed files.
 
* upload
* install.xml

#### upload

All files under this directory will be uploaded to the to directory of your OpenCart installation.

#### install.xml

The XML modification file.

You can view the documentation for this system below.

## XML

This is the file that creates a virtual copy of any files that require changes. Use this system instead of overwriting any default installation files. Multiple modifications can be applied to the same file.
 
Example OCMOD file:

```
<?xml version="1.0" encoding="utf-8"?>
<modification>
    <name>Modification Default</name>
    <version>1.0</version>
    <author>OpenCart Ltd</author>
    <link>http://www.opencart.com</link>
    <file path="catalog/controller/common/home.php">
        <operation>
            <search><![CDATA[
            $data['column_left'] = $this->load->controller('common/column_left');
            ]]></search>
            <add position="replace"><![CDATA[
            test123
            ]]></add>
        </operation>
    </file>  
</modification>
```

### Tags

#### File

You can set multiple file locations by spiting up the file locations using commas. The modification system uses PHP function glob.

http://hk1.php.net/manual/en/function.glob.php

Example:  

Direct file path.

```
<file path="catalog/controller/common/home.php">
```

Using braces allows for selecting multiple files and not having to repeat the code operation multiple times.

```
<file path="system/engine/action.php|system/engine/loader.php|system/library/config.php|system/library/language.php">
```

also allows braces

```
<file path="system/{engine,library}/{action,loader,config,language}*.php">
```

Please note that all file paths must start with either admin, catalog or system. You can also use wildcard `*` to search multiple directories and files.

#### Search

Search code

**Allowed Attributes**

* trim="(true|false)"
* regex="(true|false)"
* index="(number)"

Example:

```
<?xml version="1.0" encoding="utf-8"?>
<modification>
    <name>Modification Default</name>
    <version>1.0</version>
    <author>OpenCart Ltd</author>
    <link>http://www.opencart.com</link>
    <file path="catalog/controller/common/home.php">
        <operation>
            <search trim="true|false" index="1"><![CDATA[
            $data['column_left'] = $this->load->controller('common/column_left');
            ]]></search>
            <add position="replace" offset="1"><![CDATA[
            test123
            ]]></add>
        </operation>
    </file>  
</modification>
```

#### Add

The code to replace the search with. 

**Allowed Attributes**

* trim="(true|false)"
* position="(replace|before|after)" 
* offset="(number)"

(note position can not be used if regex is set to true in the search attribute).

Example
```
<?xml version="1.0" encoding="utf-8"?>
<modification>
    <name>Modification Default</name>
    <version>1.0</version>
    <author>OpenCart Ltd</author>
    <link>http://www.opencart.com</link>
    <file path="catalog/controller/common/home.php">
        <operation>
            <search trim="true|false"><![CDATA[
            $data['column_left'] = $this->load->controller('common/column_left');
            ]]></search>
            <add position="replace|before|after" trim="true|false" offset="2"><![CDATA[
            test123
            ]]></add>
        </operation>
    </file>  
</modification>
```

##### Regex

**Allowed Attributes**

* limit="(number)"

Example:

```
<?xml version="1.0" encoding="utf-8"?>
<modification>
    <name>Regex Example</name>
    <version>1.0</version>
    <author>OpenCart Ltd</author>
    <link>http://www.opencart.com</link>
    <file path="system/{engine,library}/{action,loader,config,language}*.php">
        <operation>
            <search regex="true" limit="1"><![CDATA[
            ~(require|include)(_once)?\(([^)]+)~
            ]]></search>
            <add><![CDATA[
            $1$2(modification($3)
            ]]></add>
        </operation>
    </file>
</modification>
```

If you use regex you can not use attributes position, trim or offset as these are handled by the regular expression code you write. The limit attribute is still available.

If, for example, you want to change the 3rd 'foo' to 'bar' on the following line:

```
lorem ifoopsum foo lor foor ipsum foo dolor foo
^1      ^2      ^3         ^4        ^5
```
	
Run: 

```
s/\(.\{-}\zsfoo\)\{3}/bar/
```
	
Result:

```	
lorem ifoopsum foo lor barr ipsum foo dolor foo
^1      ^2      ^3=bar     ^4        ^5
```

You can find more information about the regular expression PHP function OCMOD uses can be found here:

http://hk2.php.net/manual/en/function.preg-replace.php

More information about regular expression can be found here:

http://www.regular-expressions.info