<?php
use PHPUnit\Framework\TestCase;
require_once '../src/Modeles/Connexion.php';
require_once '../src/Modeles/LabelFunc.php';

final class TestLabelFunc extends TestCase
{
    public function testgetLabelNameById(): void
    {
        //addLabel('problème');
        $this->assertEquals(getLabelNameById(1), 'problème');
        $this->assertEquals(getLabelNameById(57), null);
    }
    public function testarchive(): void
    {
        $this->assertTrue(archive(1));
        $this->assertFalse(archive(1));
    }
}

?>