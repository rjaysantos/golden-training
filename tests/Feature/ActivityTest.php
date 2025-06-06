<?php

use Tests\TestCase;

class ActivityTest extends TestCase
{
    public function test_example()
    {
        $response = $this->get('/test-example');

        $response->assertJson([
            '+ + + + +',
            '- - - - -',
            '+ + + + +',
            '- - - - -',
            '+ + + + +',
        ]);
    }

    // Activity

    public function test_test1()
    {
        $response = $this->get('/test-1');

        $response->assertJson([
            '1 2 3 4 5',
            '6 7 8 9 10',
            '11 12 13 14 15',
            '16 17 18 19 20',
            '21 22 23 24 25',
        ]);
    }

    public function test_test2()
    {
        $response = $this->get('/test-2');

        $response->assertJson([]);
    }

    public function test_test3()
    {
        $response = $this->get('/test-3');

        $response->assertJson([]);
    }
}
