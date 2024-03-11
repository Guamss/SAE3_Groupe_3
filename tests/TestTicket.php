<?php
use PHPUnit\Framework\TestCase;
require_once '../src/Modeles/Ticket.php';
require_once '../src/Modeles/Connexion.php';
require_once '../src/Modeles/User.php';
require_once '../src/Modeles/logsFunc.php';
require_once '../src/Modeles/LabelFunc.php';
date_default_timezone_set('Europe/Paris');

final class TestTicket extends TestCase
{
    public function testsetTechnician(): void
    {

        $user = new User(2, 'user1', 'user');
        $ticket = new Ticket(2, '1', 1, 2, 'test1', '192.168.0.1');

        $user->createTicket($ticket);

        $this->assertTrue($ticket->setTechnician($user->getUid()));
        $this->expectException(Exception::class);
        $ticket->setTechnician(56);
    }

    public function testsetStatus(): void
    {
        $user = new User(2, 'user1', 'user');
        $ticket = new Ticket(2, '1', 1, 2, 'test2', '192.168.0.1');
        
        $user->createTicket($ticket);
        $ticket->setTechnician(5);
        $this->assertTrue($ticket->setStatus('Fermé'));
        $this->expectException(Exception::class);
        $ticket->setStatus('teeest');
    }
}

?>