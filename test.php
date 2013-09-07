<?php
 /*
  * This file is a part of the simple-ansi-escape package.
  *
  * Copyright (c) 2013, Armond B. Carroll <ben@hl9.net>
  * Distributed under the BSD License.  For full license text, please
  * view the LICENSE file that was distributed with this source code.
  */




  include 'ansi_esc.php';


  /* Every Map Possible.  See ansi_esc() for the list. */
  echo "Every Map Possible, note that since some have multiple names (like positive and ~negative are equivilant), there are duplicates here:\n\n";
  foreach(SimpleAnsiEscape::$ansi_map as $k => $v) {
      echo str_pad($k, 15, ' ', STR_PAD_LEFT) . ': ';
      echo ansi_esc($k) . 'a large piece of cake was eaten by me.' . ansi_esc() . "\n";
  }

  echo "\nDemonstration of changing, removing, and resetting formatting in the same line:\n";
  echo ansi_esc('text/blue,bold,blink') . " ... And any day with cake is a " . ansi_esc('negative,~blink') . "great" . ansi_esc('~negative,blink') . " day!\n" . ansi_esc();
  echo "----\n";

  // Demonstration of one-call syntax
  echo ansi_esc("bold, color/blue", "Simple ANSI Escape: http://github.com/nezzario/simple-ansi-escape") . "\n";
  echo ansi_esc('text/pink', "Many thanks to the Wikipedia Foundation & The Contributers to the Wikipedia ANSI Escape Codes Wiki Page") . "\n";
