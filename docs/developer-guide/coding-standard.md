# Coding Standard

* [File types & encoding](https://docs.opencart.com/developer-guide/coding-standard#file-types)
* [PHP tags](https://github.com/opencart/opencart/wiki/Coding-Standards#php-tags)
* [Indentation](https://github.com/opencart/opencart/wiki/Coding-Standards#indentation)
* [Spacing](https://github.com/opencart/opencart/wiki/Coding-Standards#spacing)
* [Whitespace](https://github.com/opencart/opencart/wiki/Coding-Standards#whitespace)
* [New lines](https://github.com/opencart/opencart/wiki/Coding-Standards#new-lines)
* [File naming](https://github.com/opencart/opencart/wiki/Coding-Standards#file-naming)
* [Class & method naming](https://github.com/opencart/opencart/wiki/Coding-Standards#class-method-naming)
* [Helper naming](https://github.com/opencart/opencart/wiki/Coding-Standards#helper-naming)
* [PHP variable naming](https://github.com/opencart/opencart/wiki/Coding-Standards#php-variable-naming)
* [User defined constants](https://github.com/opencart/opencart/wiki/Coding-Standards#user-constants)
* [PHP constants](https://github.com/opencart/opencart/wiki/Coding-Standards#php-constants)
* [HTML / CSS rules](https://github.com/opencart/opencart/wiki/Coding-Standards#html-css)
* [PHP CS Fixer](https://github.com/opencart/opencart/wiki/Coding-Standards#cs-fixer)
* [PHPStan](https://github.com/opencart/opencart/wiki/Coding-Standards#phpstan)

### File types & encoding



All PHP files with the exception of view/template files have the extension .php

All view/template files have the extension .twig

Line feeds are handled automatically by Git, the repo is managed using LF. When cloning all line feeds will be converted automatically to your native environment (CRLF for Windows, LF for Mac/Linux).

### PHP Tags



Short PHP opening tags and ASP tags are not supported. The characters should be lowercase.

`<?php`

All PHP files must include a closing tag for versions before 2.0. PHP files in and after 2.0 will no longer have a closing tag.

```
?>
```

### Indentation



PHP files must be indented using the TAB character. 4 space tabs are not supported.

HTML in template files (.twig) must be indented using 2 spaces, not 4 spaces or TABS. JavaScript must be indented using the TAB character.

### Spacing



IF, WHILE, FOR etc should have a space before and after the brackets.

**Correct**



```
if () {
```

**Incorrect**



```
if(){
```

ELSE etc should have a space after and before the curly braces

**Correct**



```
} else {
```

**Incorrect**



```
}else{
```

Type casting does NOT have a space before the variable

**Correct**



```
(int)$var
```

**Incorrect**



```
(int) $var
```

Setting a variable should always have a space before and after the equals sign

**Correct**



```
$var = 1;
```

**Incorrect**



```
$var=1;
```

### Whitespace



After any code, but before a new line - there should be no white space. The same is true for an empty line.

After the closing PHP tag it is extremely important to remove any white space.

### New Lines



Opening curly braces do not go onto a new line, they will always have a space before and be on the same line.

1 True Brace Style (1TBS) ([WIKI](http://en.wikipedia.org/wiki/Indent_style#Variant:_1TBS))

**Correct**



```
if ($my_example == 1) {

class ModelExampleExample extends Model {

public function addExample() {

} else {
```

**Incorrect**



```
if ($my_example == 1)
{

class ModelExampleExample extends Model
{

public function addExample()
{

}
else
{
```

### File naming



All files should be in lower case and words separated by an underscore.

### Class & method naming



Class names and method names should be camel case.

**Correct**



```
class ModelExampleExample extends Model

public function addExample()
```

**Incorrect**



```
class model_exampleexample extends Model

public function add_example()
```

A method scope should always be cast.

**Correct**



```
public function addExample()
```

**Incorrect**



```
function addExample()
```

### PHP Function (helpers) naming



Helper function names should be lower case and an underscore used to separate words.

### PHP variable naming



PHP variables should be lower case and an underscore used to separate words.

**Correct**



```
$var = 123;
$new_var = 12345;
```

**Incorrect**



```
$Var = 123;
$newVar = 12345;
```

### User defined constants



User defined constants are set as upper case.

**Correct**



```
define('MY_VAR', 'My constant string value');
```

**Incorrect**



```
define('my_var', 'My constant string value');
```

### PHP constants



These types of constant (true,false,null) are set as lower case

**Correct**



```
$my_var = true;
```

**Incorrect**



```
$my_var = TRUE;
```

### HTML / CSS rules



Class names and id's should be hyphenated and not use an underscore

**Correct**



```
class="my-class"
```

**Incorrect**



```
class="my_class"
```

### PHP Coding Standards Fixer



Ensure your code adheres to the project's standards effortlessly by utilizing [PHP Coding Standards Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer). The provided configuration allows for automatic code formatting.

If your IDE doesn't already integrate with php-cs-fixer, run it as a standalone tool:

Use the following command to apply code adjustments and align it with the project's Code Standard:

```
php tools/php-cs-fixer.phar fix -v
```

Integrating php-cs-fixer into your workflow ensures consistent code formatting, streamlining collaboration and maintaining a clean, standardized codebase.

### Static Code Analysis



To preempt common coding mistakes, the code undergoes thorough analysis with the [PHPStan](https://phpstan.org/) code analyzer. If your IDE does not have native integration with PHPStan, you can use it as a standalone tool:

Initiate the following command to analyze your code changes and identify any potential issues:

```
php tools/phpstan.phar
```

Leveraging PHPStan enhances code quality by detecting errors and inconsistencies early in the development process. This proactive approach aids in maintaining a robust and error-free codebase.
