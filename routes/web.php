<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-example', function () {

    $plus = '+';
    $minus = '-';
    $output = [];
    $rowData = '';

    for ($ctrRow = 0; $ctrRow < 5; $ctrRow++) {

        for ($ctrCol = 0; $ctrCol < 5; $ctrCol++) {

            if ($ctrRow % 2 == 0)
                $rowData .= "{$plus} ";
            else
                $rowData .= "{$minus} ";
        }

        $output[] = trim($rowData);
        $rowData = '';
    }

    return response()->json($output);
});

Route::get('/test-1', function () {
    
    $counter = 1;
    $output = [];

    for ($ctrRow = 0; $ctrRow < 5; $ctrRow++) {
        $rowData = ' ';
        for ($ctrCol = 0; $ctrCol < 5; $ctrCol++) {
            $rowData = $rowData . $counter++ . ' ';
        }

        $output[] = trim($rowData);
    }

    return response()->json($output);
});

Route::get('/test-2', function () {

    $plus = '+';
    $minus = '-';
    $output = [];
    $rowData = '';

    for ($ctrRow = 0; $ctrRow < 5; $ctrRow++) {
        $rowData = '';
        for ($ctrCol = 0; $ctrCol < 5; $ctrCol++) {

            if (($ctrRow + $ctrCol) % 2 == 0) {
                $symbol = $plus;
            } else {
                $symbol = $minus;
            }
            $rowData = $rowData . $symbol . ' ';
        }

        $output[] = trim($rowData);
        $rowData = '';
    }

    return response()->json($output);
});

Route::get('/test-3', function () {

    $output = [];

    for ($ctrRow = 0; $ctrRow < 5; $ctrRow++) {
        $rowData = ' ';

        $value = $ctrRow <= 2 ? $ctrRow : 4 - $ctrRow;
        $base = $value + 1;

        for ($ctrCol = 0; $ctrCol < 5; $ctrCol++) {
            $offset = 2 - abs(2 - $ctrCol);
            $number = $base + $offset;
            $rowData = $rowData . $number . ' ';
        }

        $output[] = trim($rowData);
    }
    // dd($output);
    return response()->json($output);
});
