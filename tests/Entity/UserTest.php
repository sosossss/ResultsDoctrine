<?php

/**
 * tests/Entity/UserTest.php
 *
 * @category EntityTests
 * @package  MiW\Results\Tests
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Tests\Entity;

use MiW\Results\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 *
 * @package MiW\Results\Tests\Entity
 * @group   users
 */
class UserTest extends TestCase
{
    /**
     * @var User $user
     */
    private $user;

    private $username = 'test';
    private $email = 'test@test.com';
    private $password = 'test';

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->user = new User(
            $this->username,
            $this->email,
            $this->password
        );
    }

    /**
     * @covers \MiW\Results\Entity\User::__construct()
     */
    public function testConstructor(): void
    {
        $user = new User("test2", "test2@test.com", "test2");
        $this->assertEquals("test2", $user->getUserName(), "getUserName not pass testConstructor");
        $this->assertEquals("test2@test.com", $user->getEmail(), "getEmail not pass testConstructor");
        $this->assertIsBool($this->user->validatePassword("test2"), "validatePassword not pass testConstructor");
    }

    /**
     * @covers \MiW\Results\Entity\User::getId()
     */
    public function testGetId(): void
    {
        $this->assertEquals(0, $this->user->getId(), "getId not pass testGetId");
    }

    /**
     * @covers \MiW\Results\Entity\User::setUsername()
     * @covers \MiW\Results\Entity\User::getUsername()
     */
    public function testGetSetUsername(): void
    {
        $username = "test3";
        $this->user->setUsername($username);
        $this->assertEquals($username, $this->user->getUsername(), "getUsername not pass testGetSetUsername");
    }

    /**
     * @covers \MiW\Results\Entity\User::getEmail()
     * @covers \MiW\Results\Entity\User::setEmail()
     */
    public function testGetSetEmail(): void
    {
        $email = "test3@email.com";
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail(), "getEmail not pass testGetSetEmail");
    }

    /**
     * @covers \MiW\Results\Entity\User::setEnabled()
     * @covers \MiW\Results\Entity\User::isEnabled()
     */
    public function testIsSetEnabled(): void
    {
        $isEnabled = true;
        $this->user->setEnabled($isEnabled);
        $this->assertEquals($isEnabled, $this->user->isEnabled(), "isEnabled not pass testIsSetEnabled");
    }

    /**
     * @covers \MiW\Results\Entity\User::setIsAdmin()
     * @covers \MiW\Results\Entity\User::isAdmin
     */
    public function testIsSetAdmin(): void
    {
        $isAdmin = true;
        $this->user->setIsAdmin($isAdmin);
        $this->assertEquals($isAdmin, $this->user->isAdmin(), "isAdmin not pass testIsSetAdmin");
    }

    /**
     * @covers \MiW\Results\Entity\User::setPassword()
     * @covers \MiW\Results\Entity\User::validatePassword()
     */
    public function testSetValidatePassword(): void
    {
        $password = "test3";
        $this->user->setPassword($password);
        $this->assertIsBool($this->user->validatePassword($password), "validatePassword not pass testSetValidatePassword");
    }

    /**
     * @covers \MiW\Results\Entity\User::__toString()
     */
    public function testToString(): void
    {
        $this->assertNotEmpty(strval($this->user), '__toString not pass testToString');
    }

    /**
     * @covers \MiW\Results\Entity\User::jsonSerialize()
     */
    public function testJsonSerialize(): void
    {
        $this->assertNotEmpty($this->user->jsonSerialize(), 'jsonSerialize not pass testJsonSerialize');
    }
}
