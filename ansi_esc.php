<?php

/*
  * This file is a part of the simple-ansi-escape package.
  *
  * Copyright (c) 2013-2015 Armond B. Carroll <ben@hl9.net>
  * Distributed under the BSD License.  For full license text, please
  * view the LICENSE file that was distributed with this source code.
  *
  * The entire escape code list is available here:
  * http://en.wikipedia.org/wiki/ANSI_escape_code
  *
  * All descriptions of ANSI codes are taken DIRECTLY from this list.
  * I claim no ownership to these descriptions.
  * They are mostly unmodified, directly from Wikipedia.
  * Please help improve the 'ANSI Escape Code' Wikipedia page.
  */

class SimpleAnsiEscape {
    static public $ansi_map = [
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

        // Unsure how to handle these
        // 60 - ideogram underline or right side line - hardly ever supported
        // 61 - ideogram double underline or double line on the right side - hardly ever supported
        // 62 - ideogram overline or left side line - hardly ever supported
        // 63 - ideogram double overline or double line on the left side - hardly ever supported
        // 64 - ideogram stress marking - hardly ever supported
        // 90–99 - Set foreground color, high intensity - aixterm (not in standard)
        // 100–109 - Set background color, high intensity - aixterm (not in standard)

        // Stop ANSI Codes.
    ];


    // These are some trival shortcuts.  Note that the ',' => ';' replacement is hardcoded since it is only
    // required when $format is passed as a string, and by doing so we avoid redundant calls to str_replace()
    static public $preprocess_replace = [
        '!'             => '~', // Logical NOT style (so !bold is equiv to ~bold)
        '^'             => '~', // Regex NOT Style (so ^bold is equiv to ~bold)

        'color'         => 'text', // Very first time, I typed color/blue instead of text/blue

        'strikethrough' => 'crossed',
        'strikethru'    => 'crossed',
        'double'        => 'dbl', // Would anyone really want to type doubleunderline?
        'uline'         => 'underline', // A shortcut that I might use

        // Did women come up with these ANSI color codes?  Because ain't no man I ever heard of referred to any
        // color as "cyan" or "magenta".  That's light blue and f**cking pink.
        'lightblue'     => 'cyan',
        'pink'          => 'magenta',
        // Too bad fraktur is rarely supported
        'gothic'        => 'fraktur',
    ];

    static function AnsiEscape($format = 'reset', $wrap_around = '') {
        $preprocess_replace_keys = array_keys(self::$preprocess_replace);

        // If it's not an array already, convert it to one
        if(!is_array($format)) {
            $format = str_replace(',', ';', $format); // Allows us to use comma as delimiter, see note above
            $format = explode(';', $format);
        }

        foreach($format as &$f) {
            $f = str_replace($preprocess_replace_keys, self::$preprocess_replace, trim($f));
            $f = self::$ansi_map[ $f ];
        }

        if(!empty($wrap_around)) {
            $wrap_around .= self::AnsiEscape();
        }

        return "\033[" . implode(';', $format) . "m$wrap_around";
    }
}

function ansi_esc($format = 'reset', $wrap_around = '') {
    return SimpleAnsiEscape::AnsiEscape($format, $wrap_around);
}