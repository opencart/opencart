    title: Documentation
--

<h1 skip="true">scssphp $current_version Documentation</h1>

<div class="index">$index</div>

## PHP Interface

### Including

The entire project comes in a single file. Just include it somewhere to start
using it:

    ```php
    <?php
    require "scssphp/scss.inc.php";
    ```

### Compiling

In order to manually compile code from PHP you must create an instance of the
`scssc` class. The typical flow is to create the instance, set any compile time
options, then run the compiler with the `compile` method.

    ```php
    <?php
    require "scssphp/scss.inc.php";
    $scss = new scssc();

    echo $scss->compile('
      $color: #abc;
      div { color: lighten($color, 20%); }
    ');
    ```

* <p>`compile($scssCode)` will attempt to compile a string of SCSS code. If it
  succeeds then the CSS will be returned as a string. If there is any error, an
  exception is thrown with an appropriate error message.
  </p>

### Import Paths

When you import a file using the `@import` directive, the current path of your
PHP script is used as the search path by default. This is often not what
you want, so there are two methods for manipulating the import path:
`addImportPath`, and `setImportPaths`.

* `addImportPath($path)` will append `$path` to the list of the import
  paths that are searched.

* `setImportPaths($pathArray)` will replace the entire import path with
  `$pathArray`. The value of `$pathArray` will be converted to an array if it
  isn't one already.

If the import path is set to `array()` then importing is effectively disabled.
The default import path is `array("")`, which means the current directory.

    ```php
    <?php
    require "scssphp/scss.inc.php";
    $scss = new scssc();
    $scss->setImportPaths("assets/stylesheets/");

    // will search for `assets/stylesheets/mixins.scss'
    echo $scss->compile('@import "mixins.scss"');
    ```

Besides adding static import paths, it's also possible to add custom import
functions. This allows you to load paths from a database, or HTTP, or using
files that SCSS would otherwise not process (such as vanilla CSS imports).

    ```php
    <?php
    require "scssphp/scss.inc.php";
    $scss = new scssc();
    $scss->addImportPath(function($path) {
        if (!file_exists('stylesheets/'.$path)) return null;
        return 'stylesheets/'.$path;
    });

    // will import `stylesheets/vanilla.css'
    echo $scss->compile('@import "vanilla.css"');
    ```

### Output Formatting

It's possible to customize the formatting of the output CSS by changing the
default formatter.

Three formatters are included:

* `scss_formatter`
* `scss_formatter_nested` *(default)*
* `scss_formatter_compressed`

We can change the formatting using the `setFormatter` method.

* <p>`setFormatter($formatterName)` sets the current formatter to `$formatterName`,
  the name of a class as a string that implements the formatting interface. See
  the source for `scss_formatter` for an example.
  </p>

Given the following SCSS:

    ```scss
    .navigation {
        ul {
            line-height: 20px;
            color: blue;
            a {
                color: red;
            }
        }
    }

    .footer {
        .copyright {
            color: silver;
        }
    }
    ```

The formatters will output,

`scss_formatter`:

    ```css
    .navigation ul {
      line-height: 20px;
      color: blue;
    }
    .navigation ul a {
      color: red;
    }
    .footer .copyright {
      color: silver;
    }
    ```

`scss_formatter_nested`:

    ```css
    .navigation ul {
      line-height: 20px;
      color: blue; }
        .navigation ul a {
          color: red; }

    .footer .copyright {
      color: silver; }
    ```

`scss_formatter_compressed`:

    ```css
    .navigation ul{line-height:20px;color:blue;}.navigation ul a{color:red;}.footer .copyright{color:silver;}
    ```

### Custom Functions

It's possible to register custom functions written in PHP that can be called
from SCSS. Some possible applications include appending your assets directory
to a URL with an `asset-url` function, or converting image URLs to an embedded
data URI to reduce the number of requests on a page with a `data-uri` function.

We can add and remove functions using the methods `registerFunction` and
`unregisterFunction`.

* `registerFunction($functionName, $callable)` assigns the callable value to
  the name `$functionName`. The name is normalized using the rules of SCSS.
  Meaning underscores and dashes are interchangeable. If a function with the
  same name already exists then it is replaced.

* `unregisterFunction($functionName)` removes `$functionName` from the list of
  available functions.


The `$callable` can be anything that PHP knows how to call using
`call_user_func`. The function receives two arguments when invoked. The first
is an array of SCSS typed arguments that the function was sent. The second is a
reference to the current `scss` instance.

The *SCSS typed arguments* are actually just arrays that represent SCSS values.
SCSS has different types than PHP, and this is how **scssphp** represents them
internally.

For example, the value `10px` in PHP would be `array("number", 1, "px")`. There
is a large variety of types. Experiment with a debugging function like `print_r`
to examine the possible inputs.

The return value of the custom function can either be a SCSS type or a basic
PHP type. (such as a string or a number) If it's a PHP type, it will be converted
automatically to the corresponding SCSS type.

As an example, a function called `add-two` is registered, which adds two numbers
together. PHP's anonymous function syntax is used to define the function.

    ```php
    <?php
    $scss = new scssc();

    $scss->registerFunction("add-two", function($args) {
      list($a, $b) = $args;
      return $a[1] + $b[1];
    });

    $scss->compile('.ex1 { result: add-two(10, 10); }');
    ```

It's worth noting that in this example we lose the units of the number, and we
also don't do any type checking. This will have undefined results if we give it
anything other than two numbers.


## SCSS Server

The SCSS server is a small class that helps with automatically compiling SCSS.

It's an endpoint for your web application that searches for SCSS files in a
directory then compiles and serves them as CSS. It will only compile
files if they've been modified (or one of the imports has been modified).

### Using `serveFrom`

`scss_server::serveFrom` is a simple to use function that should handle most cases.

For example, create a file `style.php`:

    ```php
    <?php
    $directory = "stylesheets";

    require "scssphp/scss.inc.php";
    scss_server::serveFrom($directory);
    ```

Going to the URL `example.com/style.php/style.scss` will attempt to compile
`style.scss` from the `stylesheets` directory, and serve it as CSS.

* <p>`scss_server::serveFrom($directory)` will serve SCSS files out of
  `$directory`. It will attempt to get the path to the file out of
  `$_SERVER["PATH_INFO"]`. (It also looks at the GET parameter `p`)
  </p>

If it can not find the file it will return an HTTP 404 page:

    ```text
    /* INPUT NOT FOUND scss v0.0.1 */
    ```

If the file can't be compiled due to an error, then an HTTP 500 page is
returned. Similar to the following:

    ```text
    Parse error: parse error: failed at `height: ;` stylesheets/test.scss on line 8
    ```

By default , the SCSS server must have write access to the style sheet
directory. It writes its cache in a special directory called `scss_cache`.

Also, because SCSS server writes headers, make sure no output is written before
it runs.

### Using `scss_server`

Creating an instance of `scss_server` is just another way of accomplishing what
`serveFrom` does. It let's us customize the cache directory and the instance
of the `scssc` that is used to compile


* <p>`new scss_server($sourceDir, $cacheDir, $scss)` creates a new server that
  serves files from `$sourceDir`. The cache dir is where the cached compiled
  files are placed. When `null`, `$sourceDir . "/scss_cache"` is used. `$scss`
  is the instance of `scss` that is used to compile.
  </p>

Just call the `serve` method to let it render its output.

Here's an example of creating a SCSS server that outputs compressed CSS:

    ```php
    <?php
    require "scssphp/scss.inc.php";

    $scss = new scssc();
    $scss->setFormatter("scss_formatter_compressed");

    $server = new scss_server("stylesheets", null, $scss);
    $server->serve();
    ```


## Command Line Tool

A really basic command line tool is included for integration with scripts. It
is called `pscss`. It reads a SCSS file from standard out and returns the CSS.

If passed the flag `-v`, input is ignored and the current version if returned.

The flag `-f` can be used to set the [formatter](#Output_formatting):

    ```bash
    $ ./pscss -f scss_formatter_compressed < styles.scss
    ```

