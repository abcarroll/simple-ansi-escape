#!/usr/bin/php -q
<?php
/*
 * This file is a part of the simple-ansi-escape package:
 * http://github.com/abcarroll/simple-ansi-escape
 *
 * Copyright (c) 2013-2015, Armond B. Carroll III <ben@hl9.net>
 * Distributed under the BSD License.  For full license text, please
 * view the LICENSE file that was distributed with this source code.
 */

/*
 * Please use the PSR autoloader instead of include() when possible!
 */

include dirname(__FILE__) . '/../src/SimpleAnsiEscape.php';
use SimpleAnsiEscape\SimpleAnsiEscape as esc;

echo "\n";

/*
 * Our first example shows SimpleAnsiEscape::ansiEscape() using the easiest way
 * to use ansiEscape(), the 'two-parameter' variant.
 */

// Using the two-parameter variant, you simply pass one or more 'escape aliases'
// to the first parameter, and the text you want to wrap in the sequences as
// the second parameter:

echo esc::ansiEscape('text/yellow', "I hope you ");

// In the previous example, the text in the second parameter will show as a
// nice yellow in a supporting terminal.  Using the two-parameter variant,
// you don't need to worry about resetting open ended sequences or your
// terminal getting messed up.  Reset is always called (appended) after the
// text.

// You can concatenate multiple calls in one call, or apply more than one type
// of formatting by using a comma separated list:

echo esc::ansiEscape('text/blue, bold', 'enjoy ') // 'enjoy' will be blue + bold
    . esc::ansiEscape('text/cyan', 'using '); // 'using' will be cyan, not bold

// Or even use complex loops without hassle.  You can also pass the escape
// aliases (first parameter) as an array of one or elements.

$randomBackgrounds = ['bg/blue', 'bg/cyan'];
$randomText = ['text/black', 'text/white'];
$randomStyle = ['bold', 'underline', 'negative'];

$text = 'simple-ansi-escape!';
$text = str_split($text, 2); // split into an array 2 chars each

foreach($text as $chunk) {
    $formatting = array(); // passed as first parameter
    $formatting[] = $randomBackgrounds[array_rand($randomBackgrounds)];
    $formatting[] = $randomText[array_rand($randomText)];
    if(mt_rand(0,1) == 0) { // 50% get a style
        $formatting[] = $randomStyle[array_rand($randomStyle)];
    }

    echo esc::ansiEscape($formatting, $chunk);
}

echo "\n\n";

/*
 * Our second example shows how one might use the one-parameter method.
 * This allows you to retain escape sequences across multiple types of output,
 * and lets you retain slightly more control than the single-call method.  We'll
 * also show the possible unintended side-effects of mixing one-parameter and
 * two-parameter variants.
 */


// First we'll begin with 'bold' + 'blue' + 'blinking' text.  *All* output by
// your script will be this format until you remove them individually (that is,
// negate with a '~negate' alias if one exists) or reset entirely.

echo esc::ansiEscape('text/blue,bold,blink'); // bold, blinking, blue output
echo "This is a ";

// Now, we can negate individual formatting options using the corresponding
// negate alias, All negate aliases start with '~', and the negate alias
// for blinking is '~blink'.  This will turn off blinking for further, but
// retain the blue and bold.  Not all sequences have a corresponding negate,
// particularly text and background colors.  (For those you would use bg/default
// or text/default, respectively)

echo esc::ansiEscape('~blink'); // turns off blinking for further output
echo "demonstration ";

// The above could've also ben written as:
echo esc::ansiEscape('!blink');
// While '~' is the default negate character, '^' and '!' work just the same.
// It's up to you how you want to express NOT.

// Take note that mixing the two formats might produce unexpected consequences!
// Calling ansiEscape() with the second parameter forces a reset after the text
// in the second parameter.

echo esc::ansiEscape('text/red', 'of '); // resets terminal after output 'of '
                                         // since the two-parameter variant is used

// Now, since we called the two-parameter variant above, we've reset everything!
// The following text will be in default terminal coloring, and intensity.  To
// prevent this, don't call the two-parameter method while you have any open
// ended escape sequences still affecting your terminal.

echo "simple-ansi-escape. \n"; // this will be in default terminal style

// The escape sequences are just pieces of text that the terminal interprets as
// they are printed.  It's perfectly fine to save them in a variable/buffer.
// Also remember that everyone's terminal is different, so if you set a
// background color, you might want to explicitly set a foreground (text/)
// color, too.


// Don't let the concatenation (.) fool you, we're still using the one-parameter
// variant here:
$buffer = esc::ansiEscape('bg/blue,text/white') . 'simple-ansi-escape was written by ';

// Since we used the one-parameter variant, our buffer still has the white on a
// blue background sequence "open ended".  Let's add some bold, and underline to
// our buffer.  In addition to passing comma-separated strings, we can also pass
// an array for the exact same effect:

$buffer .= esc::ansiEscape(array(
    'bold', 'underline'
));

$buffer .= "A.B. Carroll";

// Finally, let's print this buffer we've been working on:

echo $buffer;

// Still, we have 'open ended' terminal escapes: white text, blue background,
// and now bold and underlined.  Pretty gaudy if you ask me.  Let's reset our
// terminal back to default.  We could have just as easily added this to our
// buffer before we 'echo'd it out.  Calling ansiEscape() with no parameters is
// synonymous to ansiEscape('reset').

echo esc::ansiEscape() . "\n\n";

/*
 * Additional Examples:
 * --------------------
 */

// A good use case for the one-parameter variant is when we do not get text
// returned and instead output directly, we can ue the one-parameter to negate
// the need for output buffering, ex.:

echo esc::ansiEscape('faint'); // set our format

// var_dump() is a good example of a function that does not return it's output
var_dump("Don't forget to run printEscAliases.php for a full list of aliases!");

// But of course don't forget to reset afterwards, or we'll bleed into our shell!
echo esc::ansiEscape(); // resets the terminal

echo "\n";

/*
 * Demonstrations of one-paramter variants:
 */

echo esc::ansiEscape(
        "bold, color/blue",
        "Simple ANSI Escape: http://github.com/abcarroll/simple-ansi-escape"
    ) . "\n";

echo esc::ansiEscape('text/pink',
        "Many thanks to the Wikipedia Foundation & The Contributers to the "
        . "Wikipedia ANSI Escape Codes Wiki Page"
    ) . "\n";

/*
 * End
 */