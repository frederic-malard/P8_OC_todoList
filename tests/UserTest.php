<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRoleDefault()
    {
        $user = new User();

        $this->assertEquals($user->getRoles(), ["ROLE_USER"]);
    }
    public function testRoleUser()
    {
        $user = new User();

        $user->setRoles(["ROLE_USER"]);

        $this->assertEquals($user->getRoles(), ["ROLE_USER"]);
    }
    public function testRoleAdmin()
    {
        $user = new User();

        $user->setRoles(["ROLE_ADMIN"]);

        $this->assertContains("ROLE_ADMIN", $user->getRoles());
    }
}
