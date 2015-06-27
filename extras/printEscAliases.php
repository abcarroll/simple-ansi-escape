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

$printAll = true;

$testText = 'Just after exclaiming how delicious it looked, A.B. quickly ate his very sizable piece of cake.';

echo str_pad('Escape Alias', 15, ' ', STR_PAD_LEFT) . " | Demo Typeset Text\n";
echo ' ' . str_repeat('-', 15) . '|' . str_repeat('-', strlen($testText)) . "\n";

$sortedMap = esc::$ansiMap;
// Sort disregarding special characters
uksort($sortedMap, function($a, $b) {
    if(substr($a, 0, 1) == '~') {
        $negate = 1;
    } else {
        $negate = 0;
    }
    $a = preg_replace('/^[~]+/', '', $a);
    $b = preg_replace('/^[~]+/', '', $b);
    return strcasecmp($a, $b) + $negate;
});
$alreadyPrinted = [];
foreach($sortedMap as $escapeAlias => $escapeInteger) {
    // Just prevents aliases from being printed more than once, has nothing to do with ansi escape codes particularly.
    if($printAll == true || !isset($alreadyPrinted[$escapeInteger])) {
        $alreadyPrinted[$escapeInteger] = true;

        echo str_pad($escapeAlias, 15, ' ', STR_PAD_LEFT) . ' | ';
        echo
            esc::ansiEscape($escapeAlias) // sets the ansi escape sequence
            . $testText  // output our text
            . esc::ansiEscape() // ends (resets) the escape sequence
            . "\n";
    }
}