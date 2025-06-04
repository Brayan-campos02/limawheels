<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
        $this->assertEquals(4, 2 + 2);
        $this->assertNotEmpty("Hello World");
    }
    
    public function testMath()
    {
        $result = 5 + 3;
        $this->assertEquals(8, $result);
    }
}