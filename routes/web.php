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
    $output = ['test1'];
    

    return response()->json($output);
});

Route::get('/test-2', function () {
    $output = [];

    for ($i = 0; $i < 5; $i++) {
        $rowData = '';
        for ($j = 0; $j < 5; $j++) {
            if (($i + $j) % 2 == 0) 
                $rowData .= ' +';
            else 
                $rowData .= ' -';
        }

        $output[] = trim($rowData);
    }

    return response()->json($output);
});

Route::get('/test-3', function () {
    $output = ['test3'];

    return response()->json($output);
});
