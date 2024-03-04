<?php

/**
 * Stub file - DO NOT INCLUDE! = For PHPStorm to analyse.
 */

/**
 * Begin constants.
 */
define('AppWindow', 1); // A fixed-size application window.
define('ModalDialog', 2); // A modal dialog box (requires to be closed before continuing to other tasks).
define('ModelessDialog', 3); // A modeless dialog box (other tasks can be performed while it is open).
define('NakedWindow', 4); // A fixed-size application window with no border and no title bar.
define('PopupWindow', 5); // A fixed-size application window that cannot be minimized.
define('ResizableWindow', 6); // A normal application window with a resizable border.
define('ToolDialog', 7); // A modeless dialog box with a small caption.
define('Accel', 8);
define('Calendar', 9);
define('CheckBox', 10);
define('ComboBox', 11);
define('EditBox', 12);
define('Frame', 13);
define('Gauge', 14);
define('HTMLControl', 15);
define('HyperLink', 16);
define('ImageButton', 17);
define('InvisibleArea', 18);
define('Label', 19);
define('ListBox', 20);
define('ListView', 21);
define('Menu', 22);
define('PushButton', 23);
define('RTFEditBox', 24);
define('RadioButton', 25);
define('ScrollBar', 26);
define('Slider', 27);
define('Spinner', 28);
define('StatusBar', 29);
define('TabControl', 30);
define('ToolBar', 31);
define('TreeView', 32);
define('Timer', Timer); // Doesnt really exist - Added for IDE help
//define('PopupMenu', PopupMenu); // Doesnt really exist - Added for IDE help
define('WBC_VERSION', '2010.10.14');
define('WBC_BORDER', 8);
define('WBC_BOTTOM', 8192);
define('WBC_CENTER', 2048);
define('WBC_CHECKBOXES', 65536);
define('WBC_CUSTOMDRAW', 268435456);
define('WBC_DEFAULTPOS', -2147483648);
define('WBC_DISABLED', 2);
define('WBC_ELLIPSIS', 131072);
define('WBC_ENABLED', 0);
define('WBC_GROUP', 524288);
define('WBC_IMAGE', 4);
define('WBC_INVISIBLE', 1);
define('WBC_LEFT', 0);
define('WBC_LINES', 128);
define('WBC_MASKED', 256);
define('WBC_MIDDLE', 0);
define('WBC_MULTILINE', 128);
define('WBC_NOTIFY', 16);
define('WBC_NUMBER', 1024);
define('WBC_READONLY', 64);
define('WBC_RIGHT', 32);
define('WBC_SINGLE', 1048576);
define('WBC_SORT', 262144);
define('WBC_TASKBAR', 512);
define('WBC_AUTOREPEAT', 512);
define('WBC_TOP', 4096);
define('WBC_VISIBLE', 0);
define('WBC_TRANSPARENT', 536870912);
define('WBC_DEFAULT', 8);
define('WBC_MULTISELECT', 1073741824);
define('WBC_NOHEADER', 268435456);
define('WBC_DBLCLICK', 64);
define('WBC_MOUSEMOVE', 128);
define('WBC_MOUSEDOWN', 256);
define('WBC_MOUSEUP', 512);
define('WBC_KEYDOWN', 1024);
define('WBC_KEYUP', 2048);
define('WBC_GETFOCUS', 4096);
define('WBC_RESIZE', 8192);
define('WBC_REDRAW', 16384);
define('WBC_HEADERSEL', 32768);
define('WBC_ALT', 32);
define('WBC_CONTROL', 8);
define('WBC_SHIFT', 4);
define('WBC_LBUTTON', 1);
define('WBC_MBUTTON', 16);
define('WBC_RBUTTON', 2);
define('WBC_BEEP', -1);
define('WBC_INFO', 64);
define('WBC_OK', 0);
define('WBC_OKCANCEL', 33);
define('WBC_QUESTION', 32);
define('WBC_STOP', 16);
define('WBC_WARNING', 48);
define('WBC_YESNO', 36);
define('WBC_YESNOCANCEL', 35);
define('WBC_MAXIMIZED', 2);
define('WBC_MINIMIZED', 1);
define('WBC_NORMAL', 0);
define('WBC_MINSIZE', 2);
define('WBC_MAXSIZE', 3);
define('WBC_TITLE', 1);

define('WBC_RTF_TEXT', 1);

define('IDABORT', 3);
define('IDCANCEL', 2);
define('IDCLOSE', 8);
define('IDDEFAULT', 0);
define('IDHELP', 9);
define('IDIGNORE', 5);
define('IDNO', 7);
define('IDOK', 1);
define('IDRETRY', 4);
define('IDYES', 6);
define('FTA_BOLD', 1);
define('FTA_ITALIC', 2);
define('FTA_NORMAL', 0);
define('FTA_REGULAR', 0);
define('FTA_STRIKEOUT', 8);
define('FTA_UNDERLINE', 4);
define('BLACK', 0);
define('BLUE', 16711680);
define('CYAN', 16776960);
define('DARKBLUE', 8388608);
define('DARKCYAN', 8421376);
define('DARKGRAY', 8421504);
define('DARKGREEN', 32768);
define('DARKMAGENTA', 8388736);
define('DARKRED', 128);
define('DARKYELLOW', 32896);
define('GREEN', 65280);
define('LIGHTGRAY', 12632256);
define('MAGENTA', 16711935);
define('RED', 255);
define('WHITE', 16777215);
define('YELLOW', 65535);
define('NOCOLOR', -1);
define('bgrBLACK', 0);
define('bgrBLUE', 255);
define('bgrCYAN', 65535);
define('bgrDARKBLUE', 128);
define('bgrDARKCYAN', 32896);
define('bgrDARKGRAY', 8421504);
define('bgrDARKGREEN', 32768);
define('bgrDARKMAGENTA', 8388736);
define('bgrDARKRED', 8388608);
define('bgrDARKYELLOW', 8421376);
define('bgrGREEN', 65280);
define('bgrLIGHTGRAY', 12632256);
define('bgrMAGENTA', 16711935);
define('bgrRED', 16711680);
define('bgrWHITE', 16777215);
define('bgrYELLOW', 16776960);
define('bgrNOCOLOR', -1);

// New @Wagy constants
define('WBC_LV_NONE', 0);
define('WBC_LV_FORE', 1);
define('WBC_LV_BACK', 2);
define('WBC_LV_DEFAULT', 0);
define('WBC_LV_DRAW', 1);
define('WBC_LV_COLUMNS', 2);

/**
 * Begin functions.
 */
/**
 * Enters the Windows main loop.
 * This function must be called if the application has a window.
 * The call to wb_main_loop() must be the last executable statement of the PHP script:
 * All statements after it will be ignored.
 * The return value is used for debugging purposes only and may be ignored.
 * @return void - For debugging
 */
function wb_main_loop() {}

/**
 * Looks for a file in the Windows and System directories, in this order.
 * If the file exists, return the complete path to it.
 * If not, return filename.
 *
 * @param $filename
 *
 * @return string
 */
function wb_find_file($filename) {}

/**
 * Creates and displays a message box under the style supplied and returns a value according to the button pressed.
 *
 * Value for style & What is displayed
 *
 * WBC_OK (the default) - An OK button.
 *
 * WBC_INFO - An information icon and an OK button.
 *
 * WBC_WARNING - An exclamation point icon and an OK button.
 *
 * WBC_STOP - A stop icon and an OK button.
 *
 * WBC_QUESTION - A question mark icon and an OK button.
 *
 * WBC_OKCANCEL - A question mark icon, an OK button and a Cancel button.
 *
 * WBC_YESNO - A question mark icon, a Yes button and a No button.
 *
 * WBC_YESNOCANCEL - A question mark icon, a Yes button, a No button and a Cancel button.
 *
 * @param $parent
 * @param $message
 * @param null $title
 * @param null $style
 *
 * @return int
 */
function wb_message_box($parent, $message, $title = null, $style = null) {}

/**
 * Loads and plays a sound file or system sound.
 * Parameter source may be a sound file name or a system sound constant.
 * Parameter command may be used used to play a WAV sound synchronously or in a loop.
 * A synchronous sound stops the currently playing sound and suspends the application control until it finishes.
 * A MIDI soundtrack always stops any currently playing MIDI soundtrack.
 * To stop one or more sounds, use function wb_stop_sound().
 *
 * Value of $source:
 * MIDI file name - Load and play the specified MIDI file.
 *
 * WBC_OK - Default system sound
 *
 * WBC_INFO - System information sound
 *
 * WBC_WARNING - Warning sound
 *
 * WBC_STOP - Error sound
 *
 * WBC_QUESTION - Question sound
 *
 * WBC_BEEP - Default beep (via the computer speaker)
 *
 * Value of $command:
 * null or empty - Load and play the specified WAV sound file.
 *
 * 'sync' - Load and play the specified WAV sound file synchronously.
 *
 * 'loop' - Load and loop the specified WAV sound file.
 *
 * Returns TRUE on success or FALSE otherwise.
 *
 * @param $source
 * @param null $command
 *
 * @return bool
 */
function wb_play_sound($source, $command = null) {}

/**
 * Stops one or more sounds that were started with wb_play_sound().
 *
 * null, empty or 'all' - Stop all sounds.
 *
 * 'wav' or 'wave' - Stop all WAV sounds.
 *
 * 'mid' or 'midi' - Stop all MIDI sounds.
 *
 * Returns TRUE on success or FALSE otherwise.
 *
 * @param null $command
 *
 * @return bool
 */
function wb_stop_sound($command = null) {}

/**
 * Opens or executes a command. The string passed to this function can be one of the following:.
 *
 * A WinBinder script.
 * An executable file.
 * A non-executable file associated with an application.
 * A folder name. Passing a null or empty string opens the current folder.
 * A help file or help file topic.
 * An URL, e-mail, newsgroup, or another Internet client application.
 *
 * Optional parameters can be passed to the command or application through the variable param.
 *
 * @param $command
 * @param null $param
 *
 * @return bool
 */
function wb_exec($command, $param = null) {}

/**
 * Returns information about the current system and application, according to the string info.
 *
 * The parameter info is not case-sensitive.
 *
 * "appmemory"    The total memory used by the application¹
 * "backgroundcolor"    The main face color for Windows dialog boxes and controls
 * "colordepth"    The current color depth in bits per pixel
 * "commandline"    The original Windows command line including the executable file
 * "computername"    The name of the computer inside the network
 * "consolemode"    1 indicates that console mode (DOS box) is active, 0 otherwise
 * "diskdrives"    The list of all available disk drives
 * "exepath"    The path to the main executable (PHP.EXE)
 * "fontpath"    The current font path
 * "freememory"    The available physical memory
 * "gdiobjects"    The number of currently allocated GDI handles
 * "instance"    The instance identifier of the current application
 * "osnumber"    The numeric OS version number
 * "ospath"    The current OS path
 * "osversion"    The complete OS version name
 * "pgmpath"    The default OS application path
 * "screenarea"    The total area (x, y, width, height) of the screen, in pixels
 * "systemfont"    The common (default) system font for dialog boxes
 * "systempath"    The OS system path
 * "temppath"    The path used by the OS to hold temporary files
 * "totalmemory"    The total physical memory installed
 * "username"    The name of the currently logged user
 * "userobjects"    The number of currently allocated USER handles
 * "workarea"    The valid area (x, y, width, height) of the screen, in pixels
 *
 * @param $info
 *
 * @return mixed
 */
function wb_get_system_info($info) {}

/**
 * Reads a string or integer value from the Windows registry item referenced by key, subkey and entry.
 * The subkey may contain forward or reverse slashes.
 * If entry is an empty string, a NULL value or is not supplied, the function retrieves the default value for the subkey.
 *
 * Values are always returned as strings.
 * If the requested entry is an empty string, an empty string is returned.
 * If the key does not exist in the registry, the function returns NULL.
 *
 * @param $key
 * @param $subkey
 * @param null $entry
 *
 * @return string|null
 */
function wb_get_registry_key($key, $subkey, $entry = null) {}

/**
 * Reads a string or integer value from the Windows registry item referenced by key, subkey and entry.
 * The subkey may contain forward or reverse slashes.
 * If entry is an empty string, a NULL value or is not supplied, the function retrieves the default value for the subkey.
 *
 * Values are always returned as strings.
 * If the requested entry is an empty string, an empty string is returned.
 * If the key does not exist in the registry, the function returns NULL.
 *
 * @param $key
 * @param $subkey
 * @param null $entry
 * @param null $value
 *
 * @return bool
 */
function wb_set_registry_key($key, $subkey, $entry = null, $value = null) {}

/**
 * Creates a timer in the specified window.
 * The timer must be given an integer id that must be unique to all timers and controls.
 * interval specifies the time-out value in milliseconds.
 * Timer events are passed to and processed by the window callback function.
 * A call to wb_destroy_timer() destroys the timer.
 *
 * Low resolution and high resolution timers
 *
 * This function supports both conventional (low-resolution) and multimedia (high-resolution) timers.
 * Use a non-negative id to specify a low-resolution timer or a negative id to specify a high-resolution timer.
 * Hi-res timers have a 10:1 increase in speed (resolution can go down to 1 ms opposed to 10 ms of a conventional timer) and much higher precision.
 *
 * NOTE: Only one high-resolution timer is allowed per application and it must be on the main window.
 *
 * @param $window
 * @param $id
 * @param $interval
 *
 * @return int
 */
function wb_create_timer($window, $id, $interval) {}

/**
 * This function creates a delay and verifies if mouse buttons are pressed and/or the keyboard state.
 * This function is useful for lengthy operations.
 * In this case, wb_wait guarantees that the message control is sent back to the main loop, avoiding an unpleasant "freezing" effect.
 * Using this function also provides an way to easily exit lengthy operations by constantly monitoring the keyboard and mouse.
 *
 * Parameters:
 * WBC_MOUSEDOWN
 * WBC_MOUSEUP
 * WBC_KEYDOWN
 * WBC_KEYUP
 *
 * @param null $window
 * @param null $pause
 * @param null $flags
 *
 * @return int
 */
function wb_wait($window = null, $pause = null, $flags = null) {}

/**
 * Destroys a timer created with wb_create_timer().
 * The window and the id parameters must be the same that were passed to wb_create_timer() when the timer was created.
 *
 * @param $window
 * @param $id
 *
 * @return bool
 */
function wb_destroy_timer($window, $id) {}

/**
 * Loads the image, icon or cursor file filename from disk and returns a handle to it.
 * If filename is an icon library, index specifies the index of the image inside the file. Default index is 0.
 *
 * If source is an icon or a cursor, if param is 0 (the default), the function returns a large icon or cursor
 * if param is 1, it returns a small icon or cursor; if param is -1, the function returns the default icon or cursor.
 *
 * NOTE: The resulting image must be destroyed by a call to wb_destroy_image().
 *
 * @param $filename
 * @param null $index
 * @param null $param
 *
 * @return int
 */
function wb_load_image($filename, $index = null, $param = null) {}

/**
 * Saves the bitmap image to file filename.
 * The image handle must have been obtained with wb_create_image(), wb_create_mask() or wb_load_image().
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $image
 * @param $filename
 *
 * @return bool
 */
function wb_save_image($image, $filename) {}

/**
 * Creates a true-color image measuring width by height pixels.
 *
 * NOTE: The resulting image must be destroyed by a call to wb_destroy_image().
 *
 * @param int  $width
 * @param int  $height
 * @param null $dibbmi
 * @param null $dibbits
 *
 * @return int
 */
function wb_create_image($width = 0, $height = 0, $dibbmi = null, $dibbits = null) {}

/**
 * Creates a transparency mask of a true-color bitmap.
 * The mask returned is also a bitmap. The transparent color is set by transparent_color.
 *
 * NOTE: The resulting image must be destroyed by a call to wb_destroy_image().
 *
 * @param $bitmap
 * @param $transparent_color
 *
 * @return int
 */
function wb_create_mask($bitmap, $transparent_color) {}

/**
 * Destroys an image created by wb_create_image(), wb_create_mask() or wb_load_image().
 *
 * @param $image
 *
 * @return bool
 */
function wb_destroy_image($image) {}

/**
 * Returns a string of data containing a copy of the internal true-color representation of the given image.
 * If compress4to3 is TRUE, every fourth byte of the original 32-bit data is skipped, yielding a RGB (24-bit) data string.
 * This is required for image libraries such as FreeImage.
 *
 * @param $image
 * @param $compress4to3
 *
 * @return int
 */
function wb_get_image_data($image, $compress4to3) {}

/**
 * Returns the RGB color value of the pixel at the given coordinates. The first parameter, source, may be a WinBinder object, a window handle, a drawing surface or a bitmap.
 *
 * Returns NOCOLOR if an error occurs.
 *
 * @param $source
 * @param $xpos
 * @param $ypos
 *
 * @return int
 */
function wb_get_pixel($source, $xpos, $ypos) {}

/**
 * Draws a point of color, setting the RGB color value of the pixel that exists at the given coordinates.
 * The first parameter, source, may be a WinBinder object, a window handle, a drawing surface or a bitmap.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $source
 * @param $xpos
 * @param $ypos
 * @param $color
 *
 * @return bool
 */
function wb_draw_point($source, $xpos, $ypos, $color) {}

/**
 * Draws a straight line. The first parameter, target, may be a WinBinder object, a window handle, a drawing surface or a bitmap.
 *
 * The start and end points of the line are (x0, y0) and (x1, y1) respectively, in pixels.
 * color is a RGB color value and linewidth is the width of the line, in pixels.
 * A linewidth of zero sets the width to 1 pixel. Parameter linestyle accepts the values specified in the table below.
 *
 * 0    Solid line (the default style)
 * 1    Dotted line
 * 2-7    Dashed lines with increasing lengths
 * 8    Line with alternating dashes and dots
 * 9    Line with alternating dashes and double dots
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $target
 * @param $x0
 * @param $y0
 * @param $x1
 * @param $y1
 * @param $color
 * @param null $linewidth
 * @param null $linestyle
 *
 * @return bool
 */
function wb_draw_line($target, $x0, $y0, $x1, $y1, $color, $linewidth = null, $linestyle = null) {}

/**
 * Draws a filled or hollow rectangle.
 * The first parameter, target, may be a WinBinder object, a window handle, a drawing surface or a bitmap.
 *
 * xpos and ypos are the coordinates of the upper-left corner of the rectangle, in pixels.
 * width and height are the dimensions of the rectangle. color is a RGB color value.
 * Set filled to FALSE to draw a border.
 * A linewidth of zero sets the width to 1 pixel.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $target
 * @param $xpos
 * @param $ypos
 * @param $width
 * @param $height
 * @param $color
 * @param null $filled
 * @param null $linewidth
 * @param null $linestyle
 *
 * @return bool
 */
function wb_draw_rect($target, $xpos, $ypos, $width, $height, $color, $filled = null, $linewidth = null, $linestyle = null) {}

/**
 * Draws a filled or hollow rectangle.
 * The first parameter, target, may be a WinBinder object, a window handle, a drawing surface or a bitmap.
 *
 * xpos and ypos are the coordinates of the upper-left corner of the rectangle, in pixels.
 * width and height are the dimensions of the rectangle. color is a RGB color value.
 * Set filled to FALSE to draw a border. In this case, linewidth sets the width of the border, in pixels.
 * A linewidth of zero sets the width to 1 pixel.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $target
 * @param $xpos
 * @param $ypos
 * @param $width
 * @param $height
 * @param $color
 * @param null $filled
 * @param null $linewidth
 * @param null $linestyle
 *
 * @return bool
 */
function wb_draw_ellipse($target, $xpos, $ypos, $width, $height, $color, $filled = null, $linewidth = null, $linestyle = null) {}

/**
 * Draws a string. The first parameter, target, may be a WinBinder object, a window handle, a drawing surface or a bitmap.
 *
 * The text parameter is the string to be drawn.
 * xpos and ypos are the coordinates of the upper-left corner, in pixels.
 * width and height optionally provide a limit to the drawing area.
 * If they are not provided or zero, there is no limit to the drawing area.
 * To use a specific font, an identifier created with wb_create_font() must be used as the font argument.
 * If font is NULL, negative or not given, the most recently created font is used.
 *
 * NOTE: To use the simplified call syntax (no width, no height) you must supply 4 or 5 parameters.
 *
 * @param $target
 * @param $text
 * @param $xpos
 * @param $ypos
 * @param null $width
 * @param null $height
 * @param null $font
 * @param null $flags
 *
 * @return int
 */
function wb_draw_text($target, $text, $xpos, $ypos, $width = null, $height = null, $font = null, $flags = null) {}

/**
 * Draws a bitmap. The first parameter, target, may be a WinBinder object, a window handle, a drawing surface or another bitmap.
 *
 * xpos and ypos are the coordinates of the upper-left corner, in pixels.
 * These parameters default to zero. width and height are the dimensions of the rectangle.
 * These parameters also default to zero. In this case the bitmap is drawn with its original size.
 * The parameter transparentcolor may be used to indicate which color is to be made transparent.
 * If is set to NOCOLOR (the default), no transparency is used and the image is opaque.
 * Parameters xoffset and yoffset are optionally used to specify where the image will be drawn.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $target
 * @param $bitmap
 * @param int  $xpos
 * @param int  $ypos
 * @param null $width
 * @param null $height
 * @param null $transparentcolor
 * @param null $xoffset
 * @param null $yoffset
 *
 * @return bool
 */
function wb_draw_image($target, $bitmap, $xpos = 0, $ypos = 0, $width = null, $height = null, $transparentcolor = null, $xoffset = null, $yoffset = null) {}

/**
 * Destroys a control created by wb_create_control().
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * Tip
 * It is often preferable to hide a control instead of destroying it. To hide a window, use wb_set_visible() with parameter visible set to FALSE.
 *
 * @param $control
 *
 * @return bool
 */
function wb_destroy_control($control) {}

/**
 * Retrieves the value of a control or control item. The item and subitem parameters are set to -1 if absent.
 *
 * @param null $wbobject
 * @param int  $item
 * @param int  $subitem
 *
 * @return mixed
 */
function wb_get_value($wbobject, $item = -1, $subitem = -1) {}

/**
 * Refreshes or redraws the WinBinder object wbobject, forcing an immediate redraw if the parameter now is TRUE (the default).
 * If now is FALSE, the redraw command is posted to the Windows message queue.
 *
 * Optional parameters xpos, ypos, width and height will make the function invalidate and redraw only the specified part of the screen or control.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $wbobject
 * @param null $now
 * @param null $xpos
 * @param null $ypos
 * @param null $width
 * @param null $height
 *
 * @return int
 */
function wb_refresh($wbobject, $now = null, $xpos = null, $ypos = null, $width = null, $height = null) {}

/**
 * Enables or disables control according to the value of enabled.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $control
 * @param $enabled
 *
 * @return bool
 */
function wb_set_enabled($control, $enabled) {}

/**
 * Assigns the image source to the WinBinder object wbobject.
 * Parameter source can be either an image, icon or cursor handle or a path to an image file name.
 * If a handle, it must have been obtained with wb_create_image(), wb_create_mask() or wb_load_image().
 * The optional parameter transparentcolor tells the function which color is to be considered transparent.
 * The default is NOCOLOR (no transparency).
 * index is used to select a specific image from a multi-image file (such as a DLL or executable).
 *
 * If source is an icon or a cursor, if param is 0 (the default), the function sets a large icon or cursor.
 * if param is 1, it sets a small icon or cursor; if param is -1, the function sets the default icon or cursor.
 * For minimized windows, this function will also change the icon that is displayed on the task bar.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $wbobject
 * @param $source
 * @param null $transparentcolor
 * @param null $index
 * @param null $param
 *
 * @return bool
 */
function wb_set_image($wbobject, $source, $transparentcolor = null, $index = null, $param = null) {}

/**
 * Retrieves a portion of the image already assigned to a control and assigns it to a item (and optional subitem).
 * The image must be previously assigned with wb_set_image(). The portion which is assigned is specified by index.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $wbobject
 * @param $index
 * @param null $item
 * @param null $subitem
 *
 * @return bool
 */
function wb_set_item_image($wbobject, $index, $item = null, $subitem = null) {}

/**
 * Deletes an item, a range of items, or all items from a control. Returns TRUE on success or FALSE if an error occurs.
 * Control classes.
 *
 * This function applies to the following control classes: ListBox, ComboBox, ListView and TreeView.
 *
 * $items can be:
 * integer    Deletes the specified item.
 * array of integers    Deletes the specified items.
 * zero    Deletes item zero.
 * null    Deletes all items.
 *
 * @param $ctrl
 * @param null $items
 *
 * @return bool
 */
function wb_delete_items($ctrl, $items = null) {}

/**
 * Returns an integer that corresponds to the class of the object (control, window or menu) passed as the parameter.
 * The class is passed as a parameter to functions wb_create_control() and wb_create_window().
 *
 * @param $wbobject
 *
 * @return int
 */
function wb_get_class($wbobject) {}

/**
 * Returns an integer handle that corresponds to the WinBinder object (control, toolbar item or menu item) wbobject that has the supplied identifier id.
 * This function is typically used to retrieve the handle of a child control in a dialog box or in a menu item.
 *
 * @param $wbobject
 * @param $id
 *
 * @return int
 */
function wb_get_control($wbobject, $id) {}

/**
 * Returns TRUE if wbobject is enabled or FALSE otherwise.
 *
 * @param $wbobject
 *
 * @return bool
 */
function wb_get_enabled($wbobject) {}

/**
 * Returns a handle to the window or control that has the keyboard focus.
 *
 * @return int
 */
function wb_get_focus() {}

/**
 * Returns the integer identifier of the wbobject control.
 *
 * @param $wbobject
 *
 * @return int
 */
function wb_get_id($wbobject) {}

/**
 * Returns the number of items of wbobject.
 *
 * ComboBox    The number of items
 * ListBox    The number of items
 * ListView    The number of rows
 *
 * @param $wbobject
 *
 * @return int
 */
function wb_get_item_count($wbobject) {}

/**
 * Returns the handle of the control parent if item specifies a control, or the node parent if item specifies a treeview node.
 *
 * @param $wbobject
 * @param null $item
 *
 * @return int
 */
function wb_get_parent($wbobject, $item = null) {}

/**
 * Returns a value or array with the indices or identifiers of the selected elements or items in wbobject.
 *
 * Retrieves:
 *
 * ComboBox    The index of the currently selected item.
 * ListBox    The index of the currently selected item. If multiselected only the last on will be returned (use getText for all items text)
 * ListView    An array with the indices of the selected items. ¹
 * TabControl    The index of the selected tab page.
 * TreeView    The handle of the currently selected node.
 * Window    0 (zero).
 * Other controls    0 (zero).
 *
 * @param $wbobject
 *
 * @return mixed
 */
function wb_get_selected($wbobject) {}

/**
 * Retrieves an integer representing the current state of a control item.
 * Retrieving states.
 *
 * This function currently returns the expanded or collapsed state of a treeview node indicated by item.
 * It returns TRUE if the node is expanded and FALSE if it is collapsed.
 *
 * @param $wbobject
 * @param null $item
 *
 * @return bool
 */
function wb_get_state($wbobject, $item = null) {}

/**
 * Tells whether an object is visible. Returns TRUE if wbobject is visible and FALSE otherwise.
 *
 * @param $wbobject
 *
 * @return bool
 */
function wb_get_visible($wbobject) {}

/**
 * Set or change the mouse cursor shape of a window, control, a whole class or application-wide. *
 * The cursor can be set for any window class and for control classes ImageButton, InvisibleArea (deprecated), HyperLink and EditBox.
 *
 * The source parameter can be a cursor handle from function wb_load_image() or one of the preset system cursors:
 * arrow, cross, finger, forbidden, help, ibeam, null (no cursor), sizeall, sizenesw, sizens, sizenwse, sizewe, uparrow, wait and waitarrow.
 *
 * @param $wbobject
 * @param $source
 *
 * @return bool
 */
function wb_set_cursor($wbobject, $source) {}

/**
 * Assigns the keyboard focus to wbobject. Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $wbobject
 *
 * @return bool
 */
function wb_set_focus($wbobject) {}

/**
 * Assigns the callback function fn_handler to window.
 * The handler function may be a regular PHP function or class method that is used to process events for this particular window.
 * wb_set_handler() must be called whenever the window needs to process messages and events from its controls.
 *
 * To specify a function as the handler, pass the function name in fn_handler.
 * If the handler is a class method, fn_handler must be an array which first element is the name of the object and the second one is the method name.
 *
 * For additional information, see callback functions and window handlers.
 *
 * @param $window
 * @param $fn_handler
 *
 * @return bool|null
 */
function wb_set_handler($window, $fn_handler) {}

/**
 * Sets the location of an HTMLControl or sends a special command to it.
 *
 * Returns TRUE on success or FALSE if an error occurs (except when using "cmd:busy" as explained below).
 *
 * "cmd:back"    Go to previously visited page.
 * "cmd:forward"    Go to a page previously viewed before issuing the back command.
 * "cmd:refresh"    Redraw the current page.
 * "cmd:stop"    Stop the current action, like loading a page.
 * "cmd:busy"    Return TRUE if the browser is busy or FALSE if idle.
 * "cmd:blank"    Clear the page.
 *
 * @param $wbobject
 * @param $location
 *
 * @return bool
 */
function wb_set_location($wbobject, $location) {}

/**
 * Sets the valid range of values (vmin and vmax) of a control. Valid classes are Gauge, ScrollBar, Slider and Spinner.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $control
 * @param $vmin
 * @param $vmax
 *
 * @return bool
 */
function wb_set_range($control, $vmin, $vmax) {}

/**
 * Sets the state of a control item (a treeview node). Returns TRUE on success or FALSE if an error occurs.
 *
 * Setting states:
 * This function can currently set the expanded or collapsed state of the treeview node indicated by item.
 * Set state to TRUE to expand the node or FALSE to collapse it.
 *
 * @param $wbobject
 * @param $item
 * @param $state
 *
 * @return bool
 */
function wb_set_state($wbobject, $item, $state) {}

/**
 * Sets or resets one or more styles of the WinBinder object wbobject.
 * Only a limited set of styles is supported due to Windows limitations.
 *
 * AppWindow
 * ResizableWindow
 * PopupWindow
 * NakedWindow     WBC_TOP    Make the window a topmost window.
 *
 * ListView    WBC_LINES    Display grid lines around items
 * ListView WBC_CHECKBOXES    Display check boxes in the first column of all items
 * Slider    WBC_LINES    Show tick marks. The control must be created with the WBC_LINES style
 * TreeView    WBC_LINES    Draw dotted lines linking children objects to their parents
 *
 * @param $wbobject
 * @param $style
 * @param $set
 *
 * @return bool
 */
function wb_set_style($wbobject, $style, $set) {}

/**
 * Shows or hides the WinBinder object wbobject according to the value of visible.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $wbobject
 * @param $visible
 *
 * @return bool
 */
function wb_set_visible($wbobject, $visible) {}

/**
 * Sorts the contents of a control, a control item, a ListView column or a sub-item.
 * If the ascending parameter is TRUE (the default), the control or column is ordered starting with the lowest value or string, and vice-versa.
 *
 * The sorting criteria between two given items, item1 and item2, are as follows:
 *
 * String or number    String    Alphabetical order according to system locale
 * String or number    Empty    The non-empty item is always greater than the empty one
 * Number    Number    Numeric comparison
 *
 * In a ListView, wb_sort() sorts the column indexed by subitem. The index of the first column is zero.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $control
 * @param null $ascending
 * @param null $subitem
 *
 * @return bool
 */
function wb_sort($control, $ascending = null, $subitem = null) {}

/**
 * Retrieves an integer representing the level of a control item.
 *
 * Retrieving states:
 * This function currently returns the insertion level of the treeview node specified in item.
 *
 * @param $wbobject
 * @param $item
 *
 * @return int
 */
function wb_get_level($wbobject, $item) {}

/**
 * Creates a new font. name is the font name, height is its height in points (not pixels), and color is a RGB color value. flags can be a combination of the following values:.
 *
 * FTA_NORMAL
 * FTA_REGULAR
 * FTA_BOLD
 * FTA_ITALIC
 * FTA_UNDERLINE
 * FTA_STRIKEOUT
 *
 * Constants of FTA_NORMAL and FTA_REGULAR mean the same thing and are defined as zero.
 *
 * The function returns an integer value that is the font identifier.
 *
 * After use, the font must be destroyed by a call to wb_destroy_font() to prevent resource leaks.
 *
 * NOTE: The color parameter is not implemented yet.
 *
 * @param $name
 * @param $height
 * @param null $color
 * @param null $flags
 *
 * @return int
 */
function wb_create_font($name, $height, $color = null, $flags = null) {}

/**
 * Destroys a font.
 *
 * @param $nfont
 *
 * @return bool
 */
function wb_destroy_font($nfont) {}

/**
 * Sets the font of control. font is a unique integer value returned by wb_create_font().
 * If font is zero or not given, the most recently created font is used.
 * If font is a negative number, it means the system default font.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * Tip:
 * To check the system font name and size, call wb_get_system_info() using ("systemfont") as the info parameter.
 *
 * @param $control
 * @param null $font
 * @param null $redraw
 *
 * @return bool
 */
function wb_set_font($control, $font = null, $redraw = null) {}

/**
 * Returns the address (as an integer pointer) of the variable var.
 * var can be a string, integer, boolean, or double.
 * This function is specially suited to use with wb_peek() and wb_poke().
 *
 * @param $var
 *
 * @return int
 */
function wb_get_address($var) {}

/**
 * Sends a Windows message to the HWND handle of the WinBinder object wbobject.
 * The parameters wparam and lparam, as well as the return value, depend on message.
 * See SendMessage() in the Windows API documentation for more information.
 *
 * The following constant may be used as the wbobject parameter:
 *
 * 0xFFFF
 *
 * This constant is the value of HWND_BROADCAST in the Windows API. For more information consult the Windows API documentation.
 *
 * @param $wbobject
 * @param $message
 * @param $wparam
 * @param $lparam
 *
 * @return int
 */
function wb_send_message($wbobject, $message, $wparam = 0, $lparam = 0) {}

/**
 * Gets the contents of a memory area pointed by address.
 * If length is empty or zero, returns bytes up to the first NUL character (zero-character) or up to 32767 bytes, whichever comes first.
 * If length is greater than zero, returns length bytes.
 *
 * @param $address
 * @param int $length
 *
 * @return string
 */
function wb_peek($address, $length = 0) {}

/**
 * Sets the contents of a memory area pointed by address.
 *
 * @param $address
 * @param $contents
 * @param null $length
 *
 * @return bool
 */
function wb_poke($address, $contents, $length = null) {}

/**
 * Loads a DLL into memory. Returns an integer identifying libname. If libname is NULL then returns the identifier of the last library returned. The function accepts fully qualified and raw names. Returns NULL if no library was found.
 *
 * Name expansion
 *
 * The function appends some characters to the library name until it finds the library, then it returns an identifier for that library,
 * or NULL if the library was not found. If libname is "LIB", for example, the function looks for the following files, in order:
 *
 * LIB
 * LIB.DLL
 * LIB32
 * LIB32.DLL
 * LIB.EXE
 * LIB32.EXE
 *
 * For each name, the function looks in the following locations:
 *
 * The application directory;
 * The current directory;
 * The 32-bit System directory (Usually C:\WINDOWS\SYSTEM32 or C:\WINNT\SYSTEM32);
 * The 16-bit System directory (Usually C:\WINDOWS\SYSTEM or C:\WINNT\SYSTEM);
 * The Windows directory (Usually C:\WINDOWS or C:\WINNT);
 * The directory list contained in the PATH environment variable.
 *
 * @param $libname
 *
 * @return int
 */
function wb_load_library($libname) {}

/**
 * Releases the DLL identified by idlib from memory. The idlib identifier must have been obtained with a call to wb_load_library().
 *
 * NOTE: calling this function is usually not necessary.
 *
 * @param $idlib
 *
 * @return bool
 */
function wb_release_library($idlib) {}

/**
 * Returns the address of a library function. fname is the function name and idlib identifies a library already loaded.
 * The idlib identifier must have been obtained with a call to wb_load_library().
 * If idlib is not set or is set to NULL, the last library sent to the function will be used.
 *
 * Name expansion:
 * The function prepends and appends some special characters to the function name until it finds the function name,
 * then it returns the function address or NULL if the function was not found.
 * These special characters are the most common ones encountered in various types of libraries.
 *
 * For example, if fname is set to "MyFunction", wb_get_function_address() looks for the following function names, in order:
 *
 * MyFunction
 * MyFunctionA
 * MyFunctionW
 * _MyFunction
 * _MyFunctionA
 * _MyFunctionW
 * MyFunction@0, MyFunction@4, MyFunction@8... until MyFunction@80
 * _MyFunction@0, _MyFunction@4, _MyFunction@8... until MyFunction@80
 *
 * The last two expansion options include a '@' character followed by the number of parameters times 4,
 * which is a standard way to store function names inside DLLs. The loop starts from zero ("@0") and ends when it reaches 20 parameters ("@80").
 *
 * NOTE: Function names, including the expansion characters, are limited to 255 characters.
 *
 * @param $fname
 * @param $idlib
 *
 * @return int
 */
function wb_get_function_address($fname, $idlib) {}

/**
 * Calls the DLL function pointed by address.
 * args is an optional array of parameters that must match those of the function being called.
 * Returns an integer that may be a valid value or a pointer to one object, according to the library function called.
 *
 * NOTE: Function arguments are limited to a maximum of 20.
 *
 * @param $address
 * @param array $args
 *
 * @return int
 */
function wb_call_function($address, $args = []) {}

/**
 * returns a pointer to MidiOutProc (can also be used for MidiInProc, WaveInProc, WaveOutProc
 * or any similar callback) for use with functions like midiOutOpen.
 *
 * @return int
 */
function wb_get_midi_callback() {}

/**
 * Enumerate windows, i think: https://docs.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-enumwindows
 *
 * @return int
 */
function wb_get_enum_callback() {}

/**
 * Unused, i think its https://docs.microsoft.com/en-us/windows/win32/api/winuser/nc-winuser-hookproc
 * @return int
 */
function wb_get_hook_callback() {}

/**
 * Destroys a window created by wb_create_window().
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $window
 *
 * @return bool
 */
function wb_destroy_window($window) {}

/**
 * Gets the dimensions of a control, window, image or string.
 * The image handle must have been obtained with wb_create_image(), wb_create_mask() or wb_load_image().
 *
 * This function generally returns an array where the first element is the width and the second is the height.
 * Measurements are in pixels. If param is TRUE, the area returned will not include the title bar and borders.
 * Default is FALSE.
 *
 * The function will return the integer WBC_MINIMIZED instead of an array if the requested window is minimized, or NULL on error.
 *
 * If object is a ListView handle and param is TRUE, the function returns an array with the widths of the column headers.
 * If param is omitted or FALSE, the function behaves normally like described above
 *
 * If object is a text string, param is optionally used to pass the handle to a font created with wb_create_font().
 * If param is null or not used, the default font is used. Object types accepted
 *
 * object may be one of the following:
 *
 * A control handle
 * A window handle
 * An icon handle
 * A bitmap handle
 * The name of a bitmap file
 * The name of an icon file
 * A text string
 *
 * @param $object
 * @param null $param
 * @return array
 */
function wb_get_size($object, $param = null) {}

/**
 * Sizes the object wbobject to width and height pixels.
 *
 * Parameters width and height may be used as follows:
 *
 * Positive integer     window or control   Sets the window or control size to width and height pixels.
 * WBC_NORMAL           window              Restores the window, if it is not already.
 * WBC_MINIMIZED        window              Minimizes the window, if it is not already.
 * WBC_MAXIMIZED        window              Maximizes the window, if it is not already.
 * Array of integers    ListView            Changes the column widths of the control.
 *
 * @param $wbobject
 * @param $width
 * @param null $height
 *
 * @return bool
 */
function wb_set_size($wbobject, $width, $height = null) {}

/**
 * Moves the object wbobject to the coordinates xpos, ypos in relation to its parent window.
 * If both xpos and ypos have the value WBC_CENTER or are not given, the window is centered on its parent window.
 *
 * Returns TRUE on success or FALSE if an error occurs.
 *
 * @param $wbobject
 * @param null $xpos
 * @param null $ypos
 *
 * @return bool
 */
function wb_set_position($wbobject, $xpos = null, $ypos = null) {}

/**
 * Returns an array with the position of the control or window related to its parent, in pixels.
 * The first element is the horizontal position and the second is the vertical position.
 * If clientarea is TRUE, the area returned will not include the title bar and borders.
 *
 * The default is FALSE.
 *
 * @param $wbobject
 * @param null $clientarea
 *
 * @return array
 */
function wb_get_position($wbobject, $clientarea = null) {}

/**
 * Creates a window of class wclass. Click here for a list of the available window classes.
 * Windows created with this function must be destroyed with a call to wb_destroy_window().
 * Optional style flags may be passed through parameter style.
 * To enable additional messages in a particular window, include the WBC_NOTIFY style in the style parameter and use param to indicate
 * which additional notification messages you want to process.
 *
 * This function may set the text and/or the tooltip (small hint window) of the window when it is created.
 * To create a tooltip, text must be an array with two elements.
 * The first one is the new caption (or NULL if one is not required) and the second one is the new tooltip (or NULL if one is not required).
 * All classes support tooltips.
 *
 * Returns the handle of the newly created window or NULL or zero if an error occurs.
 *
 * @param $parent
 * @param $wclass
 * @param null $caption
 * @param null $xpos
 * @param null $ypos
 * @param null $width
 * @param null $height
 * @param null $style
 * @param null $param
 *
 * @return int
 */
function wb_create_window($parent, $wclass, $caption = null, $xpos = null, $ypos = null, $width = null, $height = null, $style = null, $param = null) {}

/**
 * Detects a running instance of a WinBinder application.
 *
 * Detecting running instances
 *
 * Each main window of all WinBinder applications stores a 32-bit identifier that is calculated according to the initial window caption
 * and is unique to that caption. wb_get_instance() will try to find, among all current top-level windows, a WinBinder window that was
 * created with the same caption. The function returns TRUE if it finds the existing window or FALSE if it is does not.
 *
 * The function is effective even of the caption of the first instance of the application is changed at runtime because the 32-bit identifier
 * does not change throughout the life of the application.
 *
 * If bringtofront is set to TRUE, the function optionally restores the window (if minimized)
 * and brings the corresponding window to the front of other windows.
 *
 * @param $caption
 * @param null $bringtofront
 *
 * @return bool
 */
function wb_get_instance($caption, $bringtofront = null) {}

/**
 * Returns an array with a list of the child controls in window or control wbobject. Each element is an integer identifier that represents a WinBinder object.
 *
 * @param $wbobject
 *
 * @return array
 */
function wb_get_item_list($wbobject) {}

/**
 * Sets a specific area in a window. Possible values for type are:.
 *
 * WBC_TITLE        Sets the area used to drag a borderless window with the mouse.
 *
 * WBC_MINSIZE      Sets the minimum window size in a resizable window.
 *                  Parameters x and y are ignored.
 *                  If width is zero, no minimum horizontal dimension is set.
 *                  if height is zero, no minimum vertical dimension is set.
 *
 * WBC_MAXSIZE      Sets the maximum window size in a resizable window.
 *                  Parameters x and y are ignored.
 *                  If width is zero, no maximum horizontal dimension is set.
 *                  if height is zero, no maximum vertical dimension is set.
 *
 * @param $window
 * @param $type
 * @param null $x
 * @param null $y
 * @param null $width
 * @param null $height
 *
 * @return bool
 */
function wb_set_area($window, $type, $x = null, $y = null, $width = null, $height = null) {}

/**
 * Displays the standard Select Path dialog box. Returns the name of the selected path, if any, or a blank string if the dialog box was canceled. Returns NULL if not successful.
 *
 * Parameters:
 *
 * parent is a handle to the WinBinder object that will serve as the parent for the dialog box.
 * title is an optional string to be displayed in the dialog box.
 * path is an optional folder used to initialize the dialog box.
 *
 * @param $parent
 * @param null $title
 * @param null $path
 *
 * @return string
 */
function wb_sys_dlg_path($parent, $title = null, $path = null) {}

/**
 * Displays the standard Select Color dialog box. Returns a RGB value which is the selected color value or NOCOLOR if the dialog box was canceled. Returns NULL if not successful.
 *
 * Parameters:
 *
 * parent is a handle to the WinBinder object that will serve as the parent for the dialog box.
 * title is currently ignored.
 * color is an optional RGB value used to initialize the dialog box.
 *
 * @param $parent
 * @param null $title
 * @param null $color
 *
 * @return int
 */
function wb_sys_dlg_color($parent, $title = null, $color = null) {}

/**
 * @param $parent
 * @param $accels
 *
 * @return int
 */
function wbtemp_set_accel_table($parent, $accels) {}

/**
 * @param $parent
 * @param $class
 * @param $caption
 * @param $xpos
 * @param $ypos
 * @param $width
 * @param $height
 * @param $id
 * @param $style
 * @param $lparam
 * @param $ntab
 *
 * @return int
 */
function wbtemp_create_control($parent, $class, $caption, $xpos, $ypos, $width, $height, $id, $style, $lparam, $ntab) {}

/**
 * @param $ctrl
 * @param $str
 *
 * @return int
 */
function wbtemp_create_item($ctrl, $str) {}

/**
 * @param $ctrl
 * @param $items
 * @param $clear
 * @param $param
 *
 * @return int
 */
function wbtemp_create_statusbar_items($ctrl, $items, $clear, $param) {}

/**
 * @param $ctrl
 * @param null $item - item can also be WBC_RTF_TEXT to get the RTF code of an RTF input
 *
 * @return int
 */
function wbtemp_get_text($ctrl, $item = null) {}

/**
 * @param $ctrl
 * @param $text
 * @param $item
 *
 * @return int
 */
function wbtemp_set_text($ctrl, $text, $item) {}

/**
 * @param $ctrl
 * @param $selitems
 *
 * @return int
 */
function wbtemp_select_tab($ctrl, $selitems) {}

/**
 * @param $ctrl
 * @param $value
 * @param $item
 *
 * @return int
 */
function wbtemp_set_value($ctrl, $value, $item = null) {}

/**
 * @param $ctrl
 * @param $item
 * @param $image
 * @param $value
 *
 * @return int
 */
function wbtemp_create_listview_item($ctrl, $item, $image, $value) {}

/**
 * @param $ctrl
 * @param $index
 * @param $value
 *
 * @return int
 */
function wbtemp_set_listview_item_checked($ctrl, $index, $value) {}

/**
 * Return TRUE if the item's checkbox is checked
 * @param $ctrl
 * @param $item
 * @return bool
 */
function wbtemp_get_listview_item_checked($ctrl, $item) {}

/**
 * @param $ctrl
 * @param $item
 * @param $subitem
 * @param $text
 *
 * @return int
 */
function wbtemp_set_listview_item_text($ctrl, $item, $subitem, $text) {}

/**
 * @param $ctrl
 * @param $item
 *
 * @return mixed
 */
function wbtemp_get_listview_text($ctrl, $item) {}

/**
 * Get the number of columns in the pwbo control,
 *
 * @param $ctrl
 *
 * @return int
 */
function wbtemp_get_listview_columns($ctrl) {}

/**
 * @param $ctrl
 * @param $i
 * @param $text
 * @param $width - If nWidth is negative, calculate width automatically
 * @param $align - WBC_LEFT, WBC_RIGHT, WBC_CENTER
 *
 * @return int
 */
function wbtemp_create_listview_column($ctrl, $i, $text, $width, $align) {}

/**
 * @param $ctrl
 *
 * @return int
 */
function wbtemp_clear_listview_columns($ctrl) {}

/**
 * @param $ctrl
 * @param $item
 * @param $selected
 *
 * @return int
 */
function wbtemp_select_listview_item($ctrl, $item, $selected) {}

/**
 * @param $ctrl
 * @param $bool
 *
 * @return int
 */
function wbtemp_select_all_listview_items($ctrl, $bool) {}

/**
 * @param $parent
 * @param $caption
 *
 * @return int
 */
function wbtemp_create_menu($parent, $caption) {}

/**
 * @param $ctrl
 * @param $item
 *
 * @return int
 */
function wbtemp_get_menu_item_checked($ctrl, $item) {}

/**
 * @param $ctrl
 * @param $selitems
 * @param $selected
 *
 * @return int
 */
function wbtemp_set_menu_item_checked($ctrl, $selitems, $selected) {}

/**
 * @param $ctrl
 * @param int $item
 * @param bool $selected
 *
 * @return int
 */
function wbtemp_set_menu_item_selected($ctrl, $item, $selected) {}

/**
 * @param $ctrl
 * @param $item
 * @param $imageHandle
 *
 * @return int
 */
function wbtemp_set_menu_item_image($ctrl, $item, $imageHandle) {}

/**
 * @param $parent
 * @param $caption
 * @param $width
 * @param $height
 * @param $lparam
 *
 * @return int
 */
function wbtemp_create_toolbar($parent, $caption, $width, $height, $lparam) {}

/**
 * @param $ctrl
 * @param $name
 * @param $value
 * @param $where
 * @param $image_index
 * @param $selected_image
 * @param $selected_image_index
 *
 * @return int
 */
function wbtemp_create_treeview_item($ctrl, $name, $value, $where = 0, $image_index = 0, $selected_image = 0, $selected_image_index = 0) {}

/**
 * @param $ctrl
 * @param $selitems
 *
 * @return int
 */
function wbtemp_set_treeview_item_selected($ctrl, $selitems) {}

/**
 * @param $ctrl
 * @param $item
 * @param $text
 *
 * @return bool
 */
function wbtemp_set_treeview_item_text($ctrl, $item, $text) {}

/**
 * @param $ctrl
 * @param $item
 * @param $value
 *
 * @return int
 */
function wbtemp_set_treeview_item_value($ctrl, $item, $value) {}

/**
 * @param $ctrl
 * @param $item
 *
 * @return int
 */
function wbtemp_get_treeview_item_text($ctrl, $item) {}

/**
 * @param $parent
 * @param null $title
 * @param null $filter
 * @param null $path
 * @param null $flags
 *
 * @return int
 */
function wbtemp_sys_dlg_open($parent, $title = null, $filter = null, $path = null, $flags = null) {}

/**
 * @param $wbObj
 * @param string $title
 * @param string $filter
 * @param string $path
 * @param string $filename
 * @param string $defext
 *
 * @return int
 */
function wbtemp_sys_dlg_save($wbObj, $title = '', $filter = '', $path = '', $filename = '', $defext = '') {}
