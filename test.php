<?php
/*
    Copyright (c) 2013, Armond B. Carroll ben@hl9.net
    All rights reserved.

    Distributed under the BSD License, which can be found here:
    http://opensource.org/licenses/BSD-2-Clause
*/

    include 'ansi_esc.php';

    ansi_esc_test();



    echo "\n----\n";

    echo ansi_esc('text/blue,bold,blink') . " ... And it was a " . ansi_esc('negative,~blink') . "great" . ansi_esc('~negative,blink') . " day\n" . ansi_esc();

    echo ansi_esc('text/pink') . "Many thanks to the Wikipedia Foundation & The Contributers to the Wikipedia ANSI Escape Codes Wiki Page" . ansi_esc();

    echo "\n----\n";
    echo "This is an " . ansi_esc('uline') . 'incomplete' . ansi_esc('~uline') . " test.\n";
