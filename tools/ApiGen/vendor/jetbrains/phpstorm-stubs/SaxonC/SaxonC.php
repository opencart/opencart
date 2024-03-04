<?php

namespace Saxon;

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api
 */
class SaxonProcessor
{
    /**
     * Constructor
     *
     * @param bool $license Indicates whether the Processor requires features of Saxon that need a license file. If false, the method will creates a Configuration appropriate for Saxon HE (Home edition). If true, the method will create a Configuration appropriate to the version of the software that is running Saxon-PE or Saxon-EE.
     * @param string $cwd The cwd argument is used to manually set the current working directory used for executions of source files
     */
    public function __construct($license = false, $cwd = '') {}

    /**
     * Create an Xdm Atomic value from any of the main primitive types (i.e. bool, int, float, string)
     *
     * @param bool|int|float|string $primitive_type_val
     * @return XdmValue
     */
    public function createAtomicValue($primitive_type_val) {}

    /**
     * Create an {@link XdmNode} object.
     *
     * @param string $value The $value is a lexical representation of the XML document.
     * @return XdmNode
     */
    public function parseXmlFromString($value) {}

    /**
     * Create an {@link XdmNode} object.
     *
     * @param string $fileName Value is a string type and the file name to the XML document. File name can be relative or absolute. IF relative the cwd is used to resolve the file.
     * @return XdmNode
     */
    public function parseXmlFromFile($fileName) {}

    /**
     * Set the current working directory used to resolve against files
     *
     * @param string $cwd
     * @return void
     */
    public function setcwd($cwd) {}

    /**
     * Set the resources directory of where Saxon can locate data folder
     *
     * @param string $dir
     * @return void
     */
    public function setResourceDirectory($dir) {}

    /**
     * Set a configuration property specific to the processor in use. Properties specified here are common across all the processors.
     *
     * @param string $name
     * @param string $value
     * @return void
     * @link https://saxonica.com/documentation/index.html#!configuration/config-features
     */
    public function setConfigurationProperty($name, $value) {}

    /**
     * Create an {@link XsltProcessor} in the PHP environment. An {@link XsltProcessor} is used to compile and execute XSLT sytlesheets
     *
     * @return XsltProcessor
     */
    public function newXsltProcessor() {}

    /**
     * Create an {@link Xslt30Processor} in the PHP environment. An {@link Xslt30Processor} is used to compile and execute XSLT 3.0 stylesheets, but can also be used for XSLT 2.0 or 1.0 stylesheets. Use an {@link Xslt30Processor} instead of {@link XsltProcessor} for XSLT 3.0 processing.
     *
     * @return Xslt30Processor
     */
    public function newXslt30Processor() {}

    /**
     * Create an {@link XQueryProcessor} in the PHP environment. An {@link XQueryProcessor} is used to compile and execute XQuery queries
     *
     * @return XQueryProcessor
     */
    public function newXQueryProcessor() {}

    /**
     * Create an {@link XPathProcessor} in the PHP environment. An {@link XPathProcessor} is used to compile and execute XPath queries
     *
     * @return XPathProcessor
     */
    public function newXPathProcessor() {}

    /**
     * Create a {@link SchemaValidator} in the PHP environment. A {@link SchemaValidator} provides capabilities to load and cache XML schema definitions. You can also validate source documents with registered XML schema definitions
     *
     * @return SchemaValidator
     */
    public function newSchemaValidator() {}

    /**
     * Report the Java Saxon version
     *
     * @return string
     */
    public function version() {}

    /**
     * Enables the ability to use PHP functions as XSLT functions. Accepts as parameter the full path of the Saxon/C PHP Extension library. This is needed to do the callbacks.
     *
     * @param string $library The full path of the Saxon/C PHP Extension library. This is needed to do the callbacks.
     * @return void
     */
    public function registerPHPFunctions($library) {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_xsltprocessor
 */
class XsltProcessor
{
    /**
     * Perform a one shot transformation. The result is stored in the supplied outputfile name.
     *
     * @param string $sourceFileName
     * @param string $stylesheetFileName
     * @param string $outputfileName
     * @return void
     */
    public function transformFileToFile($sourceFileName, $stylesheetFileName, $outputfileName) {}

    /**
     * Perform a one shot transformation. The result is returned as a string. If there are failures then a null is returned.
     *
     * @param string $sourceFileName
     * @param string $stylesheetFileName
     * @return string|null
     */
    public function transformFileToString($sourceFileName, $stylesheetFileName) {}

    /**
     * Perform a one shot transformation. The result is returned as an {@link XdmValue}.
     *
     * @param string $fileName
     * @return XdmValue
     */
    public function transformFileToValue($fileName) {}

    /**
     * Perform the transformation based upon cached stylesheet and source document.
     *
     * @return void
     */
    public function transformToFile() {}

    /**
     * @return string
     */
    public function transformToString() {}

    /**
     * Perform the transformation based upon cached stylesheet and any source document. Result returned as an {@link XdmValue} object. If there are failures then a null is returned
     *
     * @return XdmValue|null
     */
    public function transformToValue() {}

    /**
     * Compile a stylesheet supplied by file name
     *
     * @param string $fileName
     * @return void
     */
    public function compileFromFile($fileName) {}

    /**
     * Compile a stylesheet received as a string.
     *
     * @param string $str
     * @return void
     */
    public function compileFromString($str) {}

    /**
     * Compile a stylesheet received as an {@link XdmNode}.
     *
     * @param XdmNode $node
     * @return void
     */
    public function compileFromValue($node) {}

    /**
     * Set the output file name of where the transformation result is sent
     *
     * @param string $fileName
     * @return void
     */
    public function setOutputFile($fileName) {}

    /**
     * The source used for a query or stylesheet. Requires an {@link XdmValue} object
     *
     * @param XdmValue $value
     * @return void
     */
    public function setSourceFromXdmValue($value) {}

    /**
     * The source used for a query or stylesheet. Requires a file name as string
     *
     * @param string $filename
     * @return void
     */
    public function setSourceFromFile($filename) {}

    /**
     * Set the parameters required for XSLT stylesheet
     *
     * @param string $name
     * @param XdmValue $value
     * @return void
     */
    public function setParameter($name, $value) {}

    /**
     * Set properties for the stylesheet.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setProperty($name, $value) {}

    /**
     * Clear parameter values set
     *
     * @return void
     */
    public function clearParameters() {}

    /**
     * Clear property values set
     *
     * @return void
     */
    public function clearProperties() {}

    /**
     * Clear any exception thrown
     *
     * @return void
     */
    public function exceptionClear() {}

    /**
     * Get the $i'th error code if there are any errors
     *
     * @param int $i
     * @return string
     */
    public function getErrorCode($i) {}

    /**
     * Get the $i'th error message if there are any errors
     *
     * @param int $i
     * @return string
     */
    public function getErrorMessage($i) {}

    /**
     * Get number of error during execution or evaluate of stylesheet
     *
     * @return int
     */
    public function getExceptionCount() {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_xslt30processor
 */
class Xslt30Processor
{
    /**
     * File names to XSLT packages stored on filestore are added to a set of packages, which will be imported later for use when compiling.
     *
     * @param string[] $packageFileNames
     * @return void
     */
    public function addPackages($packageFileNames) {}

    /**
     * Invoke the most recently compiled stylsheet by applying templates to a supplied input sequence (the initial match selection), saving the results to the file specified in the $fileName argument.
     *
     * @param string|null $stylesheetFileName
     * @param string $fileName
     * @return void
     */
    public function applyTemplatesReturningFile($stylesheetFileName, $fileName) {}

    /**
     * Invoke a stylesheet by applying templates to a supplied input sequence (the initial match selection). The result is returned as a serialized string. The stylesheet file name can be supplied as an argument here. If null then the most recently compiled stylsheet is used.
     *
     * @param string $stylesheetFileName
     * @return string
     */
    public function applyTemplatesReturningString($stylesheetFileName) {}

    /**
     * Invoke a stylesheet by applying templates to a supplied input sequence (the initial match selection). The result is returned as an XdmValue object. The stylesheet file name can be supplied as an argument here. If null then the most recently compiled stylsheet is used.
     *
     * @param string $stylesheetFileName
     * @return XdmValue
     */
    public function applyTemplatesReturningValue($stylesheetFileName) {}

    /**
     * Get the stylesheet associated via the xml-stylesheet processing instruction (@link http://www.w3.org/TR/xml-stylesheet/) in the document specified in the $xmlFileName argument, and that match the given criteria. If there are several suitable xml-stylesheet processing instructions, then the returned source will identify a synthesized stylesheet module that imports all the referenced stylesheet modules.
     *
     * @param string $xmlFileName
     * @return void
     */
    public function compileFromAssociatedFile($xmlFileName) {}

    /**
     * Compile a stylesheet supplied as a file as specified by the $fileName argument.
     *
     * @param string $fileName
     * @return void
     */
    public function compileFromFile($fileName) {}

    /**
     * Compile a stylesheet received as a string.
     *
     * @param string $str
     * @return void
     */
    public function compileFromString($str) {}

    /**
     * Compile a stylesheet received as an {@link XdmValue}.
     *
     * @param XdmValue $node
     * @return string|null
     */
    public function compileFromValue($node) {}

    /**
     * Compile a stylesheet supplied as a file as specified by the $fileName argument, and save as an exported file (SEF).
     *
     * @param string $fileName
     * @param string $outputFileName
     * @return void
     */
    public function compileFromFileAndSave($fileName, $outputFileName) {}

    /**
     * Compile a stylesheet received as a string and save as an exported file (SEF).
     *
     * @param string $str
     * @param string $outputFileName
     * @return void
     */
    public function compileFromStringAndSave($str, $outputFileName) {}

    /**
     * Compile a stylesheet received as an {@link XdmNode} and save as an exported file (SEF).
     *
     * @param XdmNode $node
     * @param string $outputFileName
     * @return void
     */
    public function compileFromValueAndSave($node, $outputFileName) {}

    /**
     * Call a public user-defined function in the stylesheet. Here we wrap the result in an XML document, and send this document to a specified file. The function name is supplied as a string, and the values of the arguments to be supplied to the function are supplied as an array of {@link XdmValue} objects. These will be converted if necessary to the type as defined in the function signature, using the function conversion rules.
     *
     * @param string $functionName
     * @param XdmValue[] $arguments
     * @param string $outputFileName
     * @return void
     */
    public function callFunctionReturningFile($functionName, $arguments, $outputFileName) {}

    /**
     * Call a public user-defined function in the stylesheet. Here we wrap the result in an XML document, and serialize this document to a string value. The function name is supplied as a string, and the values of the arguments to be supplied to the function are supplied as an array of {@link XdmValue} objects. These will be converted if necessary to the type as defined in the function signature, using the function conversion rules.
     *
     * @param string $functionName
     * @param XdmValue[] $arguments
     * @return string
     */
    public function callFunctionReturningString($functionName, $arguments) {}

    /**
     * Call a public user-defined function in the stylesheet. Here we wrap the result in an XML document, and return the document as an {@link XdmValue}. The function name is supplied as a string, and the values of the arguments to be supplied to the function are supplied as an array of {@link XdmValue} objects. These will be converted if necessary to the type as defined in the function signature, using the function conversion rules.
     *
     * @param string $functionName
     * @param XdmValue[] $arguments
     * @return XdmValue
     */
    public function callFunctionReturningValue($functionName, $arguments) {}

    /**
     * Invoke a transformation by calling a named template, saving the results to the file specified in the $outputFileName argument. If the $templateName argument is null then the xsl:initial-template is used. Parameters supplied using setInitialTemplateParameters() are made available to the called template.
     *
     * @param string $stylesheetFileName
     * @param string|null $templateName
     * @param string $outputFileName
     * @return void
     */
    public function callTemplateReturningFile($stylesheetFileName, $templateName, $outputFileName) {}

    /**
     * Invoke a transformation by calling a named template, and return the result as a string. If the $templateName argument is null then the xsl:initial-template is used. Parameters supplied using {@link setInitialTemplateParameters()} are made available to the called template.
     *
     * @param string $stylesheetFileName
     * @param string|null $templateName
     * @return string
     */
    public function callTemplateReturningString($stylesheetFileName, $templateName) {}

    /**
     * Invoke a transformation by calling a named template, and return the result as an {@link XdmValue}. If the $templateName argument is null then the xsl:initial-template is used. Parameters supplied using {@link setInitialTemplateParameters()} are made available to the called template.
     *
     * @param string $stylesheetFileName
     * @param string|null $templateName
     * @return XdmValue
     */
    public function callTemplateReturningValue($stylesheetFileName, $templateName) {}

    /**
     * Perform a one shot transformation, saving the results to the file specified in the $outputFileName argument.
     *
     * @param string $sourceFileName
     * @param string $stylesheetFileName
     * @param string $outputFileName
     * @return void
     */
    public function transformFileToFile($sourceFileName, $stylesheetFileName, $outputFileName) {}

    /**
     * Perform a one shot transformation. The result is returned as an {@link XdmValue}.
     *
     * @param string $fileName
     * @return XdmValue|null
     */
    public function transformFileToValue($fileName) {}

    /**
     * Perform a one shot transformation. The result is returned as a string.
     *
     * @param string $fileName
     * @return string
     */
    public function transformFileToString($fileName) {}

    /**
     * Perform a one shot transformation, saving the results to the file as previously set (e.g. using {@link setOutputFile()}). The global context item may be supplied in the $context argument.
     *
     * @param XdmNode|null $context
     * @return void
     */
    public function transformToFile($context = null) {}

    /**
     * Perform a one shot transformation. The result is returned as a serialized string. The global context item may be supplied in the $context argument.
     *
     * @param XdmNode|null $context
     * @return string
     */
    public function transformToString($context = null) {}

    /**
     * Perform a one shot transformation. The result is returned as an {@link XdmValue} object. If there are failures then a null is returned. The global context item may be supplied in the $context argument.
     *
     * @param XdmNode|null $context
     * @return XdmValue
     */
    public function transformToValue($context = null) {}

    /**
     * Set parameters to be passed to the initial template. These are used whether the transformation is invoked by applying templates to an initial context item, or by invoking a named template. The parameters in question are the xsl:param elements appearing as children of the xsl:template element. The $tunnel argument should be set to true if these values are to be used for setting tunnel parameters.
     *
     * @param array $parameters
     * @param bool $tunnel
     * @return void
     */
    public function setInitialTemplateParameters($parameters, $tunnel) {}

    /**
     * Set the initial value to which templates are to be applied (equivalent to the 'select' attribute of xsl:apply-templates).
     *
     * @param XdmValue $value
     * @return void
     */
    public function setInitialMatchSelection($value) {}

    /**
     * Set the initial value to which templates are to be applied (equivalent to the 'select' attribute of xsl:apply-templates). This initial match selection is supplied as a file as specified by the $fileName argument.
     *
     * @param string $fileName
     * @return void
     */
    public function setInitialMatchSelectionAsFile($fileName) {}

    /**
     * Supply the context item to be used when evaluating global variables and parameters.
     *
     * @param XdmItem $item
     * @return void
     */
    public function setGlobalContextItem($item) {}

    /**
     * Supply the context item to be used when evaluating global variables and parameters, as a file as specified by the $fileName argument.
     *
     * @param string $fileName
     * @return void
     */
    public function setGlobalContextFromFile($fileName) {}

    /**
     * Set the output file to which the transformation result will be sent.
     *
     * @param string $fileName
     * @return void
     */
    public function setOutputFile($fileName) {}

    /**
     * Set the parameters required for the XSLT stylesheet.
     *
     * @param string $name
     * @param XdmValue $value
     * @return void
     */
    public function setParameter($name, $value) {}

    /**
     * Set properties for the XSLT processor.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setProperty($name, $value) {}

    /**
     * Say whether just-in-time compilation of template rules should be used.
     *
     * @param bool $value
     * @return void
     */
    public function setJustInTimeCompilation($value) {}

    /**
     * Set true if the result of a transformation should be returned as a raw {@link XdmValue} result, rather than as a result tree (an {@link XdmNode} object with a Document node as its root).
     *
     * @param bool $value
     * @return void
     */
    public function setResultAsRawValue($value) {}

    /**
     * Clear parameter values set.
     *
     * @return void
     */
    public function clearParameters() {}

    /**
     * Clear property values set.
     *
     * @return void
     */
    public function clearProperties() {}

    /**
     * Clear any exceptions thrown.
     *
     * @return void
     */
    public function exceptionClear() {}

    /**
     * Get the i'th error code if there are any errors.
     *
     * @param int $i
     * @return string
     */
    public function getErrorCode($i) {}

    /**
     * Get the i'th error message if there are any errors.
     *
     * @param int $i
     * @return string
     */
    public function getErrorMessage($i) {}

    /**
     * Get the number of errors during execution or evaluation of a stylesheet.
     *
     * @return int
     */
    public function getExceptionCount() {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_xqueryprocessor
 */
class XQueryProcessor
{
    /**
     * Compile and evaluate the query. Result returned as an XdmValue object. If there are failures then a null is returned
     *
     * @return XdmValue|null
     */
    public function runQueryToValue() {}

    /**
     * Compile and evaluate the query. Result returned as string. If there are failures then a null is returned
     *
     * @return string|null
     */
    public function runQueryToString() {}

    /**
     * Compile and evaluate the query. Save the result to file
     *
     * @param string $outfilename
     * @return void
     */
    public function runQueryToFile($outfilename) {}

    /**
     * query supplied as a string
     *
     * @param string $str
     * @return void
     */
    public function setQueryContent($str) {}

    /**
     * @param XdmItem $item
     * @return void
     */
    public function setQueryItem($item) {}

    /**
     * query supplied as a file
     *
     * @param string $filename
     * @return void
     */
    public function setQueryFile($filename) {}

    /**
     * Set the initial context item for the query. Supplied as filename
     *
     * @param string $fileName
     * @return void
     */
    public function setContextItemFromFile($fileName) {}

    /**
     * Set the initial context item for the query.
     * Any one of the objects are accepted: {@link XdmValue}, {@link XdmItem}, {@link XdmNode} and {@link XdmAtomicValue}.
     *
     * @param XdmValue|XdmItem|XdmNode|XdmAtomicValue $obj
     * @return void
     */
    public function setContextItem($obj) {}

    /**
     * Set the static base URI for a query expressions compiled using this XQuery Processor. The base URI is part of the static context, and is used to resolve any relative URIS appearing within a query
     *
     * @param string $uri
     * @return void
     */
    public function setQueryBaseURI($uri) {}

    /**
     * Declare a namespace binding as part of the static context for XPath expressions compiled using this XQuery processor
     *
     * @param string $prefix
     * @param string $namespace
     * @return void
     */
    public function declareNamespace($prefix, $namespace) {}

    /**
     * Set the parameters required for XQuery Processor
     *
     * @param string $name
     * @param XdmValue $value
     * @return void
     */
    public function setParameter($name, $value) {}

    /**
     * Set properties for Query.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setProperty($name, $value) {}

    /**
     * Clear parameter values set
     *
     * @return void
     */
    public function clearParameters() {}

    /**
     * Clear property values set
     *
     * @return void
     */
    public function clearProperties() {}

    /**
     * Clear any exception thrown
     *
     * @return void
     */
    public function exceptionClear() {}

    /**
     * Get the $i'th error code if there are any errors
     *
     * @param int $i
     * @return string
     */
    public function getErrorCode($i) {}

    /**
     * Get the $i'th error message if there are any errors
     *
     * @param int $i
     * @return string
     */
    public function getErrorMessage($i) {}

    /**
     * Get number of error during execution or evaluate of query
     *
     * @return int
     */
    public function getExceptionCount() {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_xpathprocessor
 */
class XPathProcessor
{
    /**
     * Set the context item from a {@link XdmItem}
     *
     * @param XdmItem $item
     * @return void
     */
    public function setContextItem($item) {}

    /**
     * Set the context item from file
     *
     * @param string $fileName
     * @return void
     */
    public function setContextFile($fileName) {}

    /**
     * Evaluate the XPath expression, returning the effective boolean value of the result.
     *
     * @param string $xpathStr
     * @return bool
     */
    public function effectiveBooleanValue($xpathStr) {}

    /**
     * Compile and evaluate an XPath expression, supplied as a character string. Result is an {@link XdmValue}
     *
     * @param string $xpathStr
     * @return XdmValue
     */
    public function evaluate($xpathStr) {}

    /**
     * Compile and evaluate an XPath expression whose result is expected to be a single item, with a given context item. The expression is supplied as a character string, and the result returned as an {@link XdmItem}. Return NULL if the expression returns an empty sequence. If the expression returns a sequence of more than one item, any items after the first are ignored.
     *
     * @param string $xpathStr
     * @return XdmItem|null
     */
    public function evaluateSingle($xpathStr) {}

    /**
     * Declare a namespace binding as part of the static context for XPath expressions compiled using this {@link XPathProcessor}
     *
     * @param $prefix
     * @param $namespace
     * @return void
     */
    public function declareNamespace($prefix, $namespace) {}

    /**
     * Set the static base URI for XPath expressions compiled using this XQuery Processor. The base URI is part of the static context, and is used to resolve any relative URIS appearing within a query
     *
     * @param string $uri
     * @return void
     */
    public function setBaseURI($uri) {}

    /**
     * Set the parameters required for XQuery Processor
     *
     * @param string $name
     * @param XdmValue $value
     * @return void
     */
    public function setParameter($name, $value) {}

    /**
     * Set properties for Query.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setProperty($name, $value) {}

    /**
     * Clear parameter values set
     *
     * @return void
     */
    public function clearParameters() {}

    /**
     * Clear property values set
     *
     * @return void
     */
    public function clearProperties() {}

    /**
     * Clear any exception thrown
     *
     * @return void
     */
    public function exceptionClear() {}

    /**
     * Get the $i'th error code if there are any errors
     *
     * @param int $i
     * @return string
     */
    public function getErrorCode($i) {}

    /**
     * Get the $i'th error message if there are any errors
     *
     * @param int $i
     * @return string
     */
    public function getErrorMessage($i) {}

    /**
     * Get number of error during execution or evaluate of stylesheet and query, respectively
     *
     * @return int
     */
    public function getExceptionCount() {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_schemavalidator
 */
class SchemaValidator
{
    /**
     * The instance document to be validated. Supplied as an Xdm Node
     *
     * @param XdmNode $node
     * @return void
     */
    public function setSourceNode($node) {}

    /**
     * The instance document to be validated. Supplied file name is resolved and accessed
     *
     * @param string $fileName
     * @return void
     */
    public function setOutputFile($fileName) {}

    /**
     * Register the Schema which is given as file name.
     *
     * @param string $fileName
     * @return void
     */
    public function registerSchemaFromFile($fileName) {}

    /**
     * Register the Schema which is given as a string representation.
     *
     * @param string $schemaStr
     * @return void
     */
    public function registerSchemaFromString($schemaStr) {}

    /**
     * Validate an instance document supplied as a Source object. Assume source document has already been supplied through accessor methods
     *
     * @param string|null $filename The name of the file to be validated. $filename can be null, in that case it is assumed source document has already been supplied through accessor methods
     * @return void
     */
    public function validate($filename = null) {}

    /**
     * Validate an instance document supplied as a Source object with the validated document returned to the calling program.
     *
     * @param string|null $filename The name of the file to be validated. $filename can be null, in that case it is assumed source document has already been supplied through accessor methods
     * @return XdmNode
     */
    public function validateToNode($filename = null) {}

    /**
     * Get the validation report produced after validating the source document. The reporting feature is switched on via setting the property on the {@link SchemaValidator): $validator->setProperty('report', 'true').
     *
     * @return XdmNode
     */
    public function getValidationReport() {}

    /**
     * Set the parameters required for XQuery Processor
     *
     * @param string $name
     * @param XdmValue $value
     * @return void
     */
    public function setParameter($name, $value) {}

    /**
     * Set properties for Schema Validator.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setProperty($name, $value) {}

    /**
     * Clear parameter values set
     *
     * @return void
     */
    public function clearParameters() {}

    /**
     * Clear property values set
     *
     * @return void
     */
    public function clearProperties() {}

    /**
     * Clear any exception thrown
     *
     * @return void
     */
    public function exceptionClear() {}

    /**
     * Get the $i'th error code if there are any errors
     *
     * @param int $i
     * @return string
     */
    public function getErrorCode($i) {}

    /**
     * Get the $i'th error message if there are any errors
     *
     * @param int $i
     * @return string
     */
    public function getErrorMessage($i) {}

    /**
     * Get number of error during execution of the validator
     *
     * @return int
     */
    public function getExceptionCount() {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_xdmvalue
 */
class XdmValue
{
    /**
     * Get the first item in the sequence
     *
     * @return XdmItem
     */
    public function getHead() {}

    /**
     * Get the n'th item in the value, counting from zero
     *
     * @param int $index
     * @return XdmItem
     */
    public function itemAt($index) {}

    /**
     * Get the number of items in the sequence
     *
     * @return int
     */
    public function size() {}

    /**
     * Add item to the sequence at the end.
     *
     * @param XdmItem $item
     */
    public function addXdmItem($item) {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_xdmitem
 */
class XdmItem extends XdmValue
{
    /**
     * Get the string value of the item. For a node, this gets the string value of the node. For an atomic value, it has the same effect as casting the value to a string. In all cases the result is the same as applying the XPath string() function.
     *
     * @return string
     */
    public function getStringValue() {}

    /**
     * Determine whether the item is a node value or not.
     *
     * @return bool
     */
    public function isNode() {}

    /**
     * Determine whether the item is an atomic value or not.
     *
     * @return bool
     */
    public function isAtomic() {}

    /**
     * Provided the item is an atomic value we return the {@link XdmAtomicValue} otherwise return null
     *
     * @return XdmAtomicValue|null
     */
    public function getAtomicValue() {}

    /**
     * Provided the item is a node value we return the {@link XdmNode} otherwise return null
     *
     * @return XdmNode|null
     */
    public function getNodeValue() {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_xdmnode
 */
class XdmNode extends XdmItem
{
    /**
     * Get the string value of the item. For a node, this gets the string value of the node.
     *
     * @return string
     */
    public function getStringValue() {}

    /**
     * Get the kind of node
     *
     * @return int
     */
    public function getNodeKind() {}

    /**
     * Get the name of the node, as a EQName
     *
     * @return string
     */
    public function getNodeName() {}

    /**
     * Determine whether the item is an atomic value or a node. This method will return FALSE as the item is not atomic
     *
     * @return false
     */
    public function isAtomic() {}

    /**
     * Get the count of child node at this current node
     *
     * @return int
     */
    public function getChildCount() {}

    /**
     * Get the count of attribute nodes at this node
     *
     * @return int
     */
    public function getAttributeCount() {}

    /**
     * Get the n'th child node at this node. If the child node selected does not exist then return null
     *
     * @param int $index
     * @return XdmNode|null
     */
    public function getChildNode($index) {}

    /**
     * Get the parent of this node. If parent node does not exist then return null
     *
     * @return XdmNode|null
     */
    public function getParent() {}

    /**
     * Get the n'th attribute node at this node. If the attribute node selected does not exist then return null
     *
     * @param int $index
     * @return XdmNode|null
     */
    public function getAttributeNode($index) {}

    /**
     * Get the n'th attribute node value at this node. If the attribute node selected does not exist then return null
     *
     * @param int $index
     * @return string|null
     */
    public function getAttributeValue($index) {}
}

/**
 * @link https://www.saxonica.com/saxon-c/documentation/index.html#!api/saxon_c_php_api/saxon_c_php_xdmatomicvalue
 */
class XdmAtomicValue extends XdmItem
{
    /**
     * Get the string value of the item. For an atomic value, it has the same effect as casting the value to a string. In all cases the result is the same as applying the XPath string() function.
     *
     * @return string
     */
    public function getStringValue() {}

    /**
     * Get the value converted to a boolean using the XPath casting rules
     *
     * @return bool
     */
    public function getBooleanValue() {}

    /**
     * Get the value converted to a float using the XPath casting rules. If the value is a string, the XSD 1.1 rules are used, which means that the string "+INF" is recognised
     *
     * @return float
     */
    public function getDoubleValue() {}

    /**
     * Get the value converted to an integer using the XPath casting rules
     *
     * @return int
     */
    public function getLongValue() {}

    /**
     * Determine whether the item is an atomic value or a node. Return TRUE if the item is an atomic value
     *
     * @return true
     */
    public function isAtomic() {}
}
