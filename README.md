# simple-ansi-escape


## What it does and does not
This is a simple PHP function called ansi_esc() that uses short sequences to be able to quickly and intuitively use ANSI escape codes (color, bold) in php-cli scripts.

Included with it, is a function ansi_esc_test() and an additional file test.php to help you get started.

- Supports most standard ANSI Escape Codes and some non-standard ones

- Does not support xterm-256 extensions, but it could be added

- Tries to be intuitive, and correct you where possible, and allow for flexible syntax so "it just feels right"

## How to use

Simply call `ansi_esc( array|string $formatting, $string = '')` when ever you need colors or other ANSI formatting.  The function comes in two forms, for example:

### One Parameter

```
echo ansi_esc('text/green') . "This is how you "
  . ansi_esc('bold') . " nest " . ansi_esc('~bold') . " formatting . " . ansi_esc();
```

In this call: 
* We used the `text/green` ansi alias which makes the font green.
* We call it again with `bold` to make everything afterwards bold.
* We decide we no longer want the text to to be bold, so we use the inversion-bold `~bold` to turn it off.  
* We never closed `text/green` so the word "formatting", and anything after that still green
* We want to turn off everything, so a call to `ansi_esc()` with no parameters resets _all_ colors and formatting.

### Two parameters

The two parameter syntax is a shorthand intended, for example, single worlds, or if you want an entire block of text to be one and only one formatting.  With the two parameters, the first parameter being the format, and second parameter you want to "wrap it around".  For example:

```
echo ansi_escape('text/blue', "This entire line is blue.") . "\n";
echo ansi_escape('bold', "This entire line is bold, but not blue.'); 
echo ansi_escape('With two parameters, it calls the reset for you at the end") . "\n";
```

Another way to say it, is simply `ansi_esc()` when called with both parameters is short hand, but exactly equivalent to: 
```
echo ansi_esc('text/blue') . "This entire line is blue" . ansi_esc() . "\n";
```

### Mixing Forms & Incorrect Nesting

There is no "reset last" ANSI Escape sequence, only "reset all".  Since the function does not keep track of what formatting you use, it has no way of knowing how to revert it if you nest the two-parameter, or mix forms in a single formatting.  This is not a limitation of this library, but more of a limitation of ANSI Escape Codes.  Take for example the code:

```
echo ansi_esc('text/green', "This is NOT how you " . ansi_esc('bold', 'nest') . " properly!");
```

In the above example, the inner `ansi_esc()` will output a reset all ansi escape sequence, so you will end up "This is NOT how you" as green, "nest" as both green and bolded, and "properly" as reset completely to non-green (default color) and non-bolded.

Likewise, the following code will behave not as one would expect, exactly as the above example:

```
echo ansi_esc('text/green') . "This is NOT how you " . ansi_esc('bold', 'nest') . " properly!" . ansi_esc();
```

The same problem being, there is no reset last, or way to "wrap" formatting around only the passed input text.  The two parameter `ansi_esc()` calls a 'reset all' after it has output the text in the specified formatting.

### Always reset

One last thing to note, when you use ANSI Escape Codes, you are sending escape sequences that are interpreted directly by the shell.  The shell does not care or explicitly do a rest when a process (php-cli) ends, therefore if you do not end your formatting, the formatting will persist after your script terminates.  To test this simply call `ansi_esc('text/green')` in a script by itself.  Once your script terminates, your shell prompt and any subsequent commands will still be green. 

In development, if you forget to reset an escape sequence and are left with an ugly shell prompt, simply type `reset` at your shell to reset it.  __Do NOT__ rely on this feature for production scripts.

## Formatting Syntax, Names

Formatting is a semi-colon, or comma delimited list of ANSI Escape Code names.  This software translates them into the required numeric values.  You can also pass an array instead of a delimited string, such as `ansi_esc(['text/green', 'blink'])` 

You can use the characters `~`, `!` or `^` for negate.  For example `~bold`, `!bold` and `^bold` are all equivalent in that they remove bold formatting.  Not all sequences can be negated.  For those that do not have a negate syntax, you must simply `reset` (or call `ansi_esc()` with no parameters, which is short-hand for `ansi_esc('reset')`.

To get the complete list of names and  aliases available for use, simply look at the ``ansi_esc.php`` file.  Many sequence have aliases.  For example `negative` is equivalent exactly to saying `~positive`.  As well as, for example, `text/cyan` is equivalent to `text/lightblue`.

## TODO

* Clean up this README.md.  It is more informative than the one before it, but it's badly put together and hard to follow.  Make a table with available names & aliases.
* Namespaces
* Error handling
* Possibly more efficient way to do the replacements in `ansi_esc()`
* More support for non-standard escapes, such as xterm-256 extensions.

## License

The code is licensed under the 2-clause BSD license.