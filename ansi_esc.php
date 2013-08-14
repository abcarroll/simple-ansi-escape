<?php
/*
	Copyright (c) 2013, Armond B. Carroll ben@hl9.net 
	All rights reserved.

	Distributed under the BSD License, which can be found here:
	http://opensource.org/licenses/BSD-2-Clause

	----

	The entire escape code list is available here:
	http://en.wikipedia.org/wiki/ANSI_escape_code

	All descriptions of ANSI codes are taken DIRECTLY from this list.  
	I claim no ownership to these descriptions.
	They are unmodified directly from wikipedia.
	Please help improve the ANSI escape codes wikipedia page.
*/

$_____ansi_map = [
	'reset'					=>  0, // Reset / Normal - all attributes off
	'bold'					=>  1, // Bold or increased intensity
	'faint'					=>  2, // Faint (decreased intensity) - not widely supported
	'italic'				=>  3, // Italic: on - not widely supported. Sometimes treated as inverse.
	'underline'			=>  4, // Underline: Single

	'blink'					=>  5, // Blink: Slow - less than 150 per minute
	'blinkslow'			=>  5, // Blink: Slow - less than 150 per minute

	'blinkfast'			=>  6, // Blink: Rapid - MS-DOS ANSI.SYS; 150 per minute or more; not widely supported

	'negative'			=>  7, // Image: Negative - inverse or reverse; swap foreground and background (reverse video)
	'~positive'			=>  7, // Image: Negative - inverse or reverse; swap foreground and background (reverse video)

	'conceal'				=>  8, // Conceal - not widely supported
	'~reveal'				=>  8, // Conceal - not widely supported

	'crossed'				=>  9, // Crossed-out - Characters legible, but marked for deletion. Not widely supported.

	'font/default'	=> 10, // Primary(default) font
	'font/0'				=> 10, // Primary(default) font

	// 11-19 - n-th alternate font	Select the n-th alternate font. 14 being the fourth alternate font, up to 19 being the 9th alternate font.
	'font/1'				=> 11,
	'font/2'				=> 12,
	'font/3'				=> 13,
	'font/4'				=> 14,
	'font/5'				=> 15,
	'font/6'				=> 16,
	'font/7'				=> 17,
	'font/8'				=> 18,
	'font/9'				=> 19,

	'fraktur'				=> 20, // Fraktur - hardly ever supported

	'dblunderline'	=> 21, // Bold: off or Underline: Double - bold off not widely supported, double underline hardly ever
	// Didn't know exactly how to handle this as ~bold or 22 is more widely supported
	'~~bold'				=> 21, // Bold: off or Underline: Double - bold off not widely supported, double underline hardly ever

	'~bold'					=> 22, // Normal color or intensity - neither bold nor faint
	'~faint'				=> 22, // Normal color or intensity - neither bold nor faint

	'~italic'				=> 23, // Not italic, not Fraktur
	'~fraktur'			=> 23, // Not italic, not Fraktur

	'~underline'		=> 24, // Underline: None - not singly or doubly underlined
	'~blink'				=> 25, // Blink: off

	// 26 -  Reserved

	'positive'			=> 27, // Image: Positive
	'~negative'			=> 27, // Image: Positive

	'reveal'				=> 28, // Reveal - conceal off
	'~conceal'			=> 28, // Reveal - conceal off

	'~crossed'			=> 29, // Not crossed out

	// 30–37 - Set text color	30 + x, where x is from the color table below
	// 0 Black 1 Red 2 Green 3 Yellow 4 Blue 5 Magent 6 Cyan 7 White

	'text/black'		=> 30,
	'text/red'			=> 31,
	'text/green'		=> 32,
	'text/yellow'		=> 33,
	'text/blue'			=> 34,
	'text/magenta'	=> 35,
	'text/cyan'			=> 36,
	'text/white'		=> 37,

	// Unsure how to handle these.
	// 38 - Set xterm-256 text color[dubious – discuss] - next arguments are 5;x where x is color index (0..255)

	'text/default'	=> 39, // Default text color - implementation defined (according to standard)

	// 40–47 - Set background color	40 + x, where x is from the color table below
	// 0 Black 1 Red 2 Green 3 Yellow 4 Blue 5 Magent 6 Cyan 7 White
	'bg/black'			=> 40,
	'bg/red'				=> 41,
	'bg/green'			=> 42,
	'bg/yellow'			=> 43,
	'bg/blue'				=> 44,
	'bg/magenta'		=> 45,
	'bg/cyan'				=> 46,
	'bg/white'			=> 47,

	// Unsure how to handle these
	// 48 - Set xterm-256 background color - next arguments are 5;x where x is color index (0..255)

	'bg/default'		=> 49, // Default background color - implementation defined (according to standard)

	// 50 - Reserved

	'frame'					=> 51, // Framed
	'encircle'			=> 52, // Encircled
	'overline'			=> 53, // Overlined

	'~encircle'			=> 54, // Not framed or encircled
	'~frame'				=> 54, // Not framed or encircled

	'~overline'			=> 55, // Not overlined

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

$_____ansi_map_preprocess = [
	// These are some trival shortcuts:
	',' => ';', // Allows us to use commas as the delimeter
	
	'!' => '~', // Logical NOT style (so !bold is equiv to ~bold)
	'^' => '~', // Regex NOT Style (so ^bold is equiv to ~bold)

	'color' => 'text', // Very first time, I typed color/blue instead of text/blue

	'strikethrough' => 'crossed',
	'strikethru' => 'crossed',	
	
	'double' => 'dbl', // Would anyone really want to type doubleunderline?
	'uline' => 'underline', // A shortcut that I might use
	
	// Did women come up with these ANSI color codes?  Because ain't no man I ever heard of referred to any
	// color as "cyan" or "magenta".  That's light blue and f**cking pink.
	'lightblue' => 'cyan',
	'pink' => 'magenta',
	
	// Too bad fraktur is rarely supported
	'gothic' => 'fraktur',
];

$_____ansi_map_sorted = $_____ansi_map;
$_____ansi_map_keys = array_map('strlen', array_keys($_____ansi_map));
array_multisort($_____ansi_map_keys, SORT_DESC, $_____ansi_map);

// or uksort($_____ansi_map, create_function('$a,$b', 'return strlen($a) < strlen($b);'));

function ansi_esc($input = false) {
		global $_____ansi_map, $_____ansi_map_preprocess;

		if($input === false) { 
			$input = 'reset';
		}

		$input = str_replace(array_keys($_____ansi_map_preprocess), $_____ansi_map_preprocess, $input);

		return "\033[" . str_replace(array_keys($_____ansi_map), $_____ansi_map, $input) . "m";
}

function ansi_esc_test() { 
	global $_____ansi_map_sorted;

	foreach($_____ansi_map_sorted as $k => $v) { 
		echo str_pad($k, 15, ' ', STR_PAD_LEFT) . ': ';
		echo ansi_esc($k) . 'a large piece of cake was eaten by me' . ansi_esc() . "\n";
	}
}
