<?php
namespace SimpleAnsiEscape;

/**
 * SimpleAnsiEscape is a standalone php component enabling you to add simple command-line/shell coloring to your php apps.
 *
 * Refer to the supplied README.md for a complete overview of how ANSI escape sequences work in regards to resets, as
 * well as a complete overview of available shortcut codes such as 'bg/blue' and 'underline'.
 *
 * To use, simply call the ansiEscape() method or ansi_esc() function.  Example use with namespaces:
 *
 *     <?php
 *     use abcarroll\SimpleAnsiEscape as AnsiEsc; // At the top with the rest of your declarations
 *
 *     echo AnsiEsc::ansiEscape('text/blue', "This text will be blue! Yeah!"); // Within your application/script
 *     ?>
 *
 * There are two ways to use SimpleAnsiEscape.  The above method is demonstrating specifying the wrap-around text.
 * Alternatively, if you omit the second parameter, an OPEN-ENDED escape sequence will be given instead.  This means
 * you MUST "reset" the sequence manually when you are ready to return to normal colorings.  Example:
 *
 *    <?php
 *    echo AnsiEsc::ansiEscape('text/red') . "This text will be red.  It will continue to be red until you call reset";
 *    echo AnsiEsc::ansiEscape(); // which, for simplicity, if you call ansiEscape() with no param, it is a shortcut for reset
 *    echo AnsiEsc::ansiEscape('reset'); // or, the verbose way
 *    ?>
 *
 * @copyright  Copyright (c) 2013-2017
 * @author     A.B. Carroll III <ben@hl9.net>
 * @license    BSD 2-Clause, see LICENSE.
 *
 * @link       https://github.com/abcarroll/simple-ansi-escape/
 * @package    abcarroll\SimpleAnsiEscape
 *
 * @see        http://en.wikipedia.org/wiki/ANSI_escape_code - Many descriptions of the ANSI codes were taken *directly*
 *             from the Wikipedia article.  I claim no ownership to these descriptions.  Please help improve the
 *             'ANSI Escape Code' Wikipedia page.
 *
 * @version   1.0.1 - Cleaned up and greatly expanded upon phpdoc.
 */
class SimpleAnsiEscape {

    /**
     * Our list of 'shortcut codes' to their ANSI byte equivalent expressed as an integer.
     *
     * The key part, called shortcut-codes are my own made-up names and are *not* a part of any sort of official
     * standard.  This list has been stable and unchanged since 2013.
     *
     * Also note that several codes are specified more than once.  This is to allow more intuitive usage: for example,
     * for conceal and reveal, you may use "conceal" to conceal the text and "~conceal" (not conceal) to reveal it.
     *
     * Alternatively, you may prefer to use 'conceal' and 'reveal'.  "reveal" and "~conceal" both map to the same ANSI
     * code.
     *
     * @var array
     */

    static public $ansiMap = [
        // Reset / Normal - all attributes off
        'reset'        => 0,
        // Bold or increased intensity
        'bold'         => 1,
        // Faint (decreased intensity) - not widely supported
        'faint'        => 2,
        // Italic: on - not widely supported. Sometimes treated as inverse.
        'italic'       => 3,
        // Underline: Single
        'underline'    => 4,
        // Blink: Slow - less than 150 per minute
        'blink'        => 5,
        'blinkslow'    => 5,
        // Blink: Rapid - MS-DOS ANSI.SYS; 150 per minute or more; not widely supported
        'blinkfast'    => 6,
        // Image: Negative - inverse or reverse; swap foreground and background (reverse video)
        'negative'     => 7,
        '~positive'    => 7,
        // Conceal - not widely supported
        'conceal'      => 8,
        '~reveal'      => 8,
        // Crossed-out - Characters legible, but marked for deletion. Not widely supported.
        'crossed'      => 9,
        'font/default' => 10,
        'font/0'       => 10,
        // 11-19 - n-th alternate font
        // Select the n-th alternate font. 14 being the fourth alternate font, up to 19 being the 9th alternate font.
        'font/1'       => 11,
        'font/2'       => 12,
        'font/3'       => 13,
        'font/4'       => 14,
        'font/5'       => 15,
        'font/6'       => 16,
        'font/7'       => 17,
        'font/8'       => 18,
        'font/9'       => 19,
        // Fraktur - Not widely supported
        'fraktur'      => 20,
        // Bold: off or Underline: Double - bold off not widely supported, double underline
        // Didn't know exactly how to handle this as ~bold or 22 is more widely supported
        'dblunderline' => 21,
        '~~bold'       => 21,
        // Normal color or intensity - neither bold nor faint, i.e. reset to normal
        '~bold'        => 22,
        '~faint'       => 22,
        // Not italic. Not Fraktur
        '~italic'      => 23,
        '~fraktur'     => 23,
        // Underline: None - not singly or doubly underlined
        '~underline'   => 24,
        // Blink: off
        '~blink'       => 25,
        // 26 -  Reserved

        // Image: Positive
        'positive'     => 27,
        '~negative'    => 27,
        // Reveal - conceal off
        'reveal'       => 28,
        '~conceal'     => 28,
        // Not crossed out
        '~crossed'     => 29,
        // 30–37 - Set text color   30 + x, where x is from the color table below
        // 0 Black 1 Red 2 Green 3 Yellow 4 Blue 5 Magent 6 Cyan 7 White
        'text/black'   => 30,
        'text/red'     => 31,
        'text/green'   => 32,
        'text/yellow'  => 33,
        'text/blue'    => 34,
        'text/magenta' => 35,
        'text/cyan'    => 36,
        'text/white'   => 37,
        // Unsure how to handle these.
        // 38 - Set xterm-256 text color[dubious – discuss] - next arguments are 5;x where x is color index (0..255)
        'text/default' => 39, // Default text color - implementation defined (according to standard)

        // 40–47 - Set background color 40 + x, where x is from the color table below
        // 0 Black 1 Red 2 Green 3 Yellow 4 Blue 5 Magent 6 Cyan 7 White
        'bg/black'     => 40,
        'bg/red'       => 41,
        'bg/green'     => 42,
        'bg/yellow'    => 43,
        'bg/blue'      => 44,
        'bg/magenta'   => 45,
        'bg/cyan'      => 46,
        'bg/white'     => 47,
        // Unsure how to handle these
        // 48 - Set xterm-256 background color - next arguments are 5;x where x is color index (0..255)

        // Default background color - implementation defined (according to standard)
        'bg/default'   => 49,
        // 50 - Reserved

        // Framed
        'frame'        => 51,
        // Encircled
        'encircle'     => 52,
        // Overlined
        'overline'     => 53,
        // Not framed or encircled
        '~encircle'    => 54,
        // Not framed or encircled
        '~frame'       => 54,
        // Not overlined
        '~overline'    => 55,
        // 56–59 - Reserved

        // These are unusual, non-standard, and not-well-supported codes that were intentionally excluded.

        // 60 - ideogram underline or right side line - hardly ever supported
        // 61 - ideogram double underline or double line on the right side - hardly ever supported
        // 62 - ideogram overline or left side line - hardly ever supported
        // 63 - ideogram double overline or double line on the left side - hardly ever supported
        // 64 - ideogram stress marking - hardly ever supported
        // 90–99 - Set foreground color, high intensity - aixterm (not in standard)
        // 100–109 - Set background color, high intensity - aixterm (not in standard)
    ];


    /**
     * Additional generic shortcuts/aliases that are processed *before* the primary $ansiMap table (above).
     *
     * Defining the more generic aliases here, particularly '!' and '^', makes it so that we do not need to define
     * a ridiculous amount of aliases in the primary $ansiMap table above.  Otherwise, for example, we would need to
     * define each of '~bold', '!bold', and '^bold' individually.
     *
     * In addition to the below pre-processing, there is also a hard-coded ',' to ';' replacement within AnsiEscape()
     * when $format is passed as a string (to avoid a redundant call to str_replace()).
     *
     * @var array
     */
    static public $preprocessReplace = [
        '!'             => '~',         // Logical NOT style (so !bold is equiv to ~bold)
        '^'             => '~',         // Regex NOT Style (so ^bold is equiv to ~bold)

        'color'         => 'text',      // I personally can never remember whether it is color/x or text/x for text-color.

        'strikethrough' => 'crossed',
        'strikethru'    => 'crossed',
        'double'        => 'dbl',       // Would anyone really want to type doubleunderline?
        'uline'         => 'underline', // A shortcut that I might use

        'lightblue'     => 'cyan',      // For the brutes among us that wish to use less elegant names for colors.
        'pink'          => 'magenta',

        'gothic'        => 'fraktur',  // Fraktur is rarely supported (and it's a shame).
    ];

    /**
     * Returns the magic bytes to produce the desired '$format' of coloring & formatting.  You need to print this value.
     *
     * There are three distinct behaviours for ansiEscape():
     *
     *  - With no parameters, it is identical to calling ansiEscape('reset') and will reset *all* formatting up until
     *    that point.
     *  - With the single, first parameter, it will return the open-ended ANSI escape codes and nothing else.  You will
     *    need to follow it with some text, as well as manage the resetting/negating the formatting afterwards.
     *  - With both parameters specified, it will return the wrapAround parameter, wrapped in the ANSI escape sequence
     *    and a reset, so that you do not have to manage resetting it yourself.  This is the simplest way to use
     *    ansiEscape().
     *
     * The first parameter may be either a comma- and/or semi-colon delimited list of shortcuts, or it also may be an
     * array with one shortcut per element.  For example, "text/blue, bg/red; bold" and ['text/blue', 'bg/red', 'bold']
     * will produce exactly equivalent results.
     *
     * *TEXT & BACKGROUND COLOR SHORTCUT CHEAT SHEET:*
     *
     * Text and bg colors both use the same set of colors:
     *
     * - black  white  red  green  blue  yellow  magenta  cyan
     * - and "default" to negate (text/default and bg/default, respectively)
     *
     * If you can remember these colors, then just use 'text/(color)' to change the text color and 'bg/(color)' to
     * change the background color.  Remember, once you change either, you can use 'negative' to flip them or 'default'
     * to go back to the user's terminal default.  Of course, a full reset will also return to default (along with
     * any other text decorations or formatting you may have done).
     *
     * Also, if you are writing a widely used application, you should set the background color if setting the text to
     * a high contrast color like black or white -- some users may have very light or very dark default terminal bg
     * colors.
     *
     * *COMMON, WELL-SUPPORTED TEXT DECORATION:*
     *
     * - bold          (~bold to negate)
     * - underline     (~underline to negate)
     * - strikethrough (~strikethrough to negate, may be abbrv. 'strikethru' or 'crossed')
     * - faint         (~faint to negate)
     * - negative      (~negative or 'positive' to negate -- flips text/bg color)
     * - conceal       (~conceal or 'reveal' to negate -- hides text until negated)
     *
     * @param string|array $format     The desired format using the documented shortcuts.  To specify more than one
     *                                 type of formatting at once, you may pass several formats as a comma or semi-colon
     *                                 separated list, or even as an array (with one 'shortcut' per element).
     *
     * @param string $wrapAround       Optional parameter that indicates the text to wrap in the supplied formatting.
     *                                 If supplied, the output will be <Formatting><Text Supplied><Reset>.  If omitted,
     *                                 then an open-ended code sequence will be given instead and you will need to manage
     *                                 the resetting/negating yourself.
     *
     * @return string                  The ANSI escape code sequence, and if $wrap around is supplied, followed by the
     *                                 supplied text and a ANSI escape reset.
     */
    static public function ansiEscape($format = 'reset', $wrapAround = null)
    {
        $preprocessReplacementKeys = array_keys(self::$preprocessReplace);

        // If it's not an array already, convert it to one
        if(!is_array($format)) {
            // Allow the use of a comma OR a semi-colon as a delimiter.
            $format = str_replace(',', ';', $format);
            $format = explode(';', $format);
        }

        foreach($format as &$f) {
            $f = str_replace($preprocessReplacementKeys, self::$preprocessReplace, trim($f));
            $f = self::$ansiMap[$f];
        }

        if(!empty($wrapAround)) {
            $wrapAround .= self::ansiEscape();
        }

        // This is basically all there is to it.
        return "\033[" . implode(';', $format) . "m$wrapAround";
    }
}

/**
 * Returns the human-readable '$format' of shortcuts as actual ANSI escape sequences to beautify your console.
 *
 * ansi_esc() is a sugar-syntax helper function to call the SimpleAnsiEscape::ansiEscape() statically.  Refer to the
 * documentation for SimpleAnsiEscape::ansiEscape() for much more detailed documentation.
 *
 * @param string|array $format      The format, as a comma/semi-colon delimited list, or as an array with one shortcut
 *                                  code (such as 'text/blue') each element.
 *
 * @param string       $wrapAround  If specified, changes the behaviour of AnsiEscape() so that the formatting is
 *                                  wrapped-around the $wrapAround string.  Otherwise, an open-ended sequence is
 *                                  returned that you will likely need to reset/negate manually.
 *
 * @return string                   The ANSI escape code sequence as a string -- and perhaps, the $wrapAround string
 *                                  followed by a ANSI reset code, if you specified the $wrapAround parameter.
 */
function ansi_esc($format = 'reset', $wrapAround = '') {
    return SimpleAnsiEscape::ansiEscape($format, $wrapAround);
}