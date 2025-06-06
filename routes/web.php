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
    // $output = [
    //     '1 2 3 4 5',
    //     '6 7 8 9 10',
    //     '11 12 13 14 15',
    //     '16 17 18 19 20',
    //     '21 22 23 24 25',
    // ];

    $count = 1;
    $output = [];
    $rowData = '';

    for ($column = 0; $column < 5; $column++) {
        for ($row = 0; $row < 5; $row++) {
            $rowData .= "{$count} ";
            $count++;
        }

        $output[] = trim($rowData);
        $rowData = '';
    }

    return response()->json($output);
});

Route::get('/test-2', function () {
    $plus = '+';
    $minus = '-';
    $output = [];
    $rowData = '';

    for ($ctrRow = 0; $ctrRow < 5; $ctrRow++) {
        for ($ctrCol = 0; $ctrCol < 5; $ctrCol++) {
            if ($ctrRow % 2 == 0)
                if ($ctrCol % 2 == 0)
                    $rowData .= "{$plus} ";
                else
                    $rowData .= "{$minus} ";
            else
                if ($ctrCol % 2 == 0)
                $rowData .= "{$minus} ";
            else
                $rowData .= "{$plus} ";
        }

        $output[] = trim($rowData);
        $rowData = '';
    }

    return response()->json($output);
});

Route::get('/test-3', function () {
    $output = ['test3'];

    return response()->json($output);
});
