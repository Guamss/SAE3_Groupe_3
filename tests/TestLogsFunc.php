<?php
use PHPUnit\Framework\TestCase;
require_once '../src/Modeles/logsFunc.php';

final class TestLogsFunc extends TestCase
{
    public function testList_dir(): void
    {
        $path1 = 'log1/a';
        $path2 = 'log2/b';
        
        $expected1 = [];
        $expected2 = ['test.csv'];

        $output1 = list_dir($path1);
        $output2 = list_dir($path2);

        $this->assertSame($output1, $expected1);
        $this->assertTrue(in_array('test.csv', $output2));
    }
}

?>