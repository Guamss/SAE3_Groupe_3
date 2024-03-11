<?php
use PHPUnit\Framework\TestCase;
require_once '../src/Modeles/logsFunc.php';
require_once '../src/Modeles/User.php';
require_once '../src/Modeles/Ticket.php';
require_once '../src/Modeles/Connexion.php';

final class TestUser extends TestCase
{
    public function testcreateTicket(): void
    {
        $user = new User(2, 'user1', 'user');
        $tec = new User(1, 'technician', 'tec1');

        $ticket = new Ticket(2, '1', 1, 2, 'test3', '192.168.0.1');

        $this->assertFalse($tec->createTicket($ticket));
        $this->expectException(Exception::class);
        $user->createTicket(new Ticket(42, 25, 32, 78, 'teeeeeeeeeeest', '192.168.0.0'));
        $this->assertTrue($user->createTicket($ticket));
    }

    public function testgetLoginByUID(): void
    {
        $this->assertEquals(User::getLoginByUID(2), "user1");
        $this->assertEquals(User::getLoginByUID(67), "unknown user");
    }
}

?>