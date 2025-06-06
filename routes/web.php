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
    $number = 1;
    $rowData = '';
    for ($ctrRow = 0; $ctrRow < 5; $ctrRow++) {

        for ($ctrCol = 0; $ctrCol < 5; $ctrCol++) {
            $rowData .= "{$number} ";
            $number++;
        }

        $output[] = trim($rowData);
        $rowData = '';
    }

    return response()->json($output);
});

Route::get('/test-2', function () {
    $sign = '+';
    $output = [];
    $rowData = '';

    for ($ctrRow = 0; $ctrRow < 5; $ctrRow++) {

        for ($ctrCol = 0; $ctrCol < 5; $ctrCol++) {

            $rowData .= "{$sign} ";
            if ($sign == '+')
                $sign = '-';
            else
                $sign = '+';
        }

        $output[] = trim($rowData);
        $rowData = '';
    }

    return response()->json($output);
});

Route::get('/test-3', function () {
    $number = 1;
    $output = [];
    $rowData = '';

    for ($ctrRow = 0; $ctrRow < 5; $ctrRow++) {

        for ($ctrCol = 0; $ctrCol < 5; $ctrCol++) {
            $rowData .= "{$number} ";

            if ($ctrCol > 1)
                $number--;
            else
                $number++;
        }

        $number += 2;
        if ($ctrRow > 1) {
            $number -= 2;
        }

        $output[] = trim($rowData);
        $rowData = '';
    }

    return response()->json($output);
});
