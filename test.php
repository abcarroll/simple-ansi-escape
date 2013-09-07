<?php
 /*
	* This file is a part of the simple-ansi-escape package.
	*
	* Copyright (c) 2013, Armond B. Carroll <ben@hl9.net>
	* Distributed under the BSD License.  For full license text, please
	* view the LICENSE file that was distributed with this source code.
	*/

	include 'ansi_esc.php';
	
	foreach($_____ansi_map_sorted as $k => $v) {
		echo str_pad($k, 15, ' ', STR_PAD_LEFT) . ': ';
		echo ansi_esc($k) . 'a large piece of cake was eaten by me' . ansi_esc() . "\n";
	}

	echo "\n----\n";
	
	echo ansi_esc('text/blue,bold,blink') . " ... And it was a " . ansi_esc('negative,~blink') . "great" . ansi_esc('~negative,blink') . " day\n" . ansi_esc();
	
	echo ansi_esc('text/pink') . "Many thanks to the Wikipedia Foundation & The Contributers to the Wikipedia ANSI Escape Codes Wiki Page" . ansi_esc();
	
	echo "\n----\n";
	echo "This is an " . ansi_esc('uline') . 'incomplete' . ansi_esc('~uline') . " test.\n";
