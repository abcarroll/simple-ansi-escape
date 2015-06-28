Simple ANSI Escape
=======================

Using ANSI escape codes, you can add color and formatting to terminals used by php5-cli. Simple-Ansi-Escape is a simple
class to turn the obscure ANSI escape codes into friendly aliases that are easy to remember.

Simple-Ansi-Escape tries to be intuitive as possible and is very flexible in it's parameters.  As the name implies, it
is also very simple.  There is only one class definition that contains one static public method, `ansiEscape()`.
Simple-Ansi-Escape supports most standard ANSI escape codes and some nonstandard ones.  It does not, however, support
xterm-256 extensions (yet).

## Simple Usage

A separate, more in-depth and hands-on tutorial is distributed with the repository, see `extras/tutorial.php`.  Run it,
and read the source code.

There is a single class, `SimpleAnsiEscape` that defines one static public function, ``SimpleAnsiEscape::ansiEscape()``.
If you are using a PSR-4 compatible auto-loader, you can get started with some gaudy yellow blinking text with just two
lines:
 
```php
use SimpleAnsiEscape\SimpleAnsiEscape as esc;
echo esc::ansiEscape('color/yellow, blink', "I hope you enjoy Simple-Ansi-Escape!");
```

The full prototype is, in namespace `SimpleAnsiEscape`:
```php
SimpleAnsiEscape::ansiEscape( [ array|string $formatting, [ $wrapAround = null ] ] )
```

`::ansiEscape()` tries to be very flexible in what you pass it.  The first parameter can be either an array with
one or more array elements, or a string.  In it's array form, each array element should correspond to a single *escape
alias* such as "color/yellow" in the previous example.  In it's string form, you can pass comma-, space- or 
semicolon-delimited *escape aliases*.  Examples:

```php
// In the array form, one-parameter syntax:
echo esc::ansiEscape(array('color/blue', 'faint', 'underline')), "I hope you enjoy Simple-Ansi-Escape!");

// The same thing, as a string:
echo esc::ansiEscape('color/blue, faint, underline', "I hope you enjoy Simple-Ansi-Escape!");

// You can use commas, spaces, or semi-colons as well.  Although it looks poor, even
// mixing and matching will not confuse Simple-Ansi-Escape:
echo esc::ansiEscape('color/red faint; underline, bold', "I hope you enjoy Simple-Ansi-Escape!");
```

With no parameters, it is synonymous with `::ansiEscape('reset')`` and will reset all open-ended formatting.

You can also use `::ansiEscape()` with a single parameter to output only the escape codes.  When called with a single
parameter, no ANSI reset is called automatically.  While you need to call reset yourself (by calling `::ansiEscape()` 
with no parameters), but it makes nesting a lot easier:

Note that you can not nest within the same function call, but it's easy to nest using a couple of extra calls:
```php
// This is INCORRECT.  The inner escape will terminate formatting and ' properly' will be
// in the default terminal style.
echo esc::ansiEscape(
    'text/green', 
    "This is NOT how you " . esc::ansiEscape('bold', 'nest') . " properly!"
);

// This is CORRECT.  We're build the value in the proper linear order:
echo esc::ansiEscape('text/green') . "This is how you ". esc::ansiEscape('bold')
    . "properly " . esc::ansiEscape('~bold') . "next formatting." . esc::ansiEscape();
```

### Always reset

A final note, when using ANSI escape codes, you are sending tiny textual sequences that are interpreted by your
terminal.  The terminal has no knowledge when php5-cli terminates, therefore you must ensure you do not have any 
open-ended escape codes, or your formatting will spill out into the terminal, even after your script terminates.

If you have a colorful `PS1` shell environmental variable, you *may* not notice this, so please be mindful!  During
development, if you make a mess of your terminal, simply run the `reset` shell command to start fresh.

## Formatting Syntax, Names

You can use the characters `~`, `!` or `^` for negate.  For example `~bold`, `!bold` and `^bold` are all equivalent in
that they remove bold formatting.  Not all sequences can be negated.  Background and text use 'bg/default', and 
'color/default' to reset back to defaults.  For those that do not have a negate syntax, you must simply send an ANSI
reset.  

## Escape Aliases

Escape aliases are the heart of AnsiEscape.  There is a supplied script, in `extras/printEscAliases.php` which
will print a table much like the ones below, and show demo typeset instead so you can test your terminal's 
capabilities.

### Most important (compatible) escape aliases are seen in the table below.

   Escape Alias | Notes               
|--------------:|:----------------------------------------------------------------------------------------------
|         reset | All formatting reset to baseline.                                                            |
|  text/default | Change text (foreground) color to default.  Negate any previously set color.                 |
|    text/black | Change text (foreground) color to black. Alias color/black.                                  |
|     text/blue | Change text (foreground) color to blue.  Alias color/blue.                                   |
|     text/cyan | Change text (foreground) color to cyan.  Alias color/cyan, color/lightblue.                  |
|    text/green | Change text (foreground) color to green.  Alias color/green.                                 |
|  text/magenta | Change text (foreground) color to magenta.  Alias color/magenta, color/pink.                 |
|      text/red | Change text (foreground) color to red.                                                       |
|    text/white | Change text (foreground) color to white.                                                     |
|   text/yellow | Change text (foreground) color to yellow.                                                    |
|    bg/default | Change text (foreground) color default.  Negate any previously set background color.         |
|      bg/black | Change background color to black.                                                            |
|       bg/blue | Change background color to blue.                                                             |
|       bg/cyan | Change background color to cyan.  Alias bg/lightblue.                                        |
|      bg/green | Change background color to green.                                                            |
|    bg/magenta | Change background color to magenta.  Alias bg/pink.                                          |
|        bg/red | Change background color to red.                                                              |
|      bg/white | Change background color to white.                                                            |
|     bg/yellow | Change background color to yellow.                                                           |
|         blink | Sets the text to a blink.  xterm and many terminals support this, but not all.               |
|        ~blink | Blink off.                                                                                   |
|          bold | Sets the text to bold typeface.                                                              |
|         ~bold | Returns the text to normal intensity.  Same code as ~faint.                                  |
|       conceal | Conceals text.  Sets text and background to the same as used by terminal.                    |
|      ~conceal | Reveals text.  Alias for '~reveal'.                                                          |
|        reveal | Reveals text.  Alias for '~conceal'.                                                         |
|       ~reveal | Conceals text.  Alias for 'conceal'.                                                         |
|       crossed | Strike-through text.  Well supported.                                                        |
|      ~crossed | Not strike-through text.                                                                     |
|         faint | Faint / dim text.                                                                            |
|        ~faint | Returns to the text to normal intensity.  Same code as ~bold.                                |
|        italic | Italics typeface.  Not as well supported as most, but supported by xterm.                    |
|       ~italic | Not italics or fraktor.  Same code as ~fraktor.                                              |
|      negative | Inverts background and foreground (text) color.                                              |
|     ~positive | Alias for negative - negate positive.                                                        |
|     ~negative | Alias for positive - negate negative.                                                        |
|      positive | Returns background from inversion.                                                           |
|      overline | Overline over text.  Not as well supported.                                                  |
|     ~overline | Not overlined.                                                                               |
|     underline | Underline text.                                                                              |
|    ~underline | Not underline or double underline.  Use to negate double underline as well.                  |

### Lesser Supported and Non-Standard Escape Codes
These are escape codes that I've never seen work in a modern terminal.  You may have better luck.

|  Escape Alias | Notes                                                                                        |
|--------------:|:----------------------------------------------------------------------------------------------
|     blinkfast |                                                                                              |
|     blinkslow | In xterm, this will cause a blink but at the exact same rate as standard blink.              |
|        ~~bold | This is a variant for bold off (21) which isn't as widely supported.                         |
|  dblunderline |                                                                                              |
|      encircle |                                                                                              |
|     ~encircle |                                                                                              |
|        font/0 | I've never seen any of these fonts work in xterm.                                            |
|        font/1 |                                                                                              |
|        font/2 |                                                                                              |
|        font/3 |                                                                                              |
|        font/4 |                                                                                              |
|        font/5 |                                                                                              |
|        font/6 |                                                                                              |
|        font/7 |                                                                                              |
|        font/8 |                                                                                              |
|        font/9 |                                                                                              |
|  font/default | Resets font to default, if you could get it to change to begin with.                         |
|       fraktur | I had this work on one terminal before, it's a gothic style font.  Alias for gothic.         |
|      ~fraktur | Not fraktor, alias for ~gothic.  Also will stop italics.                                     |
|         frame |                                                                                              |
|        ~frame |                                                                                              |

## License

The code is licensed under the 2-clause BSD license.
