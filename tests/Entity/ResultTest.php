<?php

/**
 * tests/Entity/ResultTest.php
 *
 * @category EntityTests
 * @package  MiW\Results\Tests
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Tests\Entity;

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\Utils;
use PHPUnit\Framework\TestCase;

/**
 * Class ResultTest
 *
 * @package MiW\Results\Tests\Entity
 */
class ResultTest extends TestCase
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * @var Result $result
     */
    private $result;

    private const USERNAME = 'uSeR ñ¿?Ñ';
    private const POINTS = 2018;

    /**
     * @var \DateTime $time
     */
    private $time;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->user = new User();
        $this->user->setUsername(self::USERNAME);
        $this->time = new \DateTime('now');
        $this->result = new Result(
            self::POINTS,
            $this->user,
            $this->time
        );
    }

    /**
     * Implement testConstructor
     *
     * @covers \MiW\Results\Entity\Result::__construct()
     * @covers \MiW\Results\Entity\Result::getId()
     * @covers \MiW\Results\Entity\Result::getResult()
     * @covers \MiW\Results\Entity\Result::getUser()
     * @covers \MiW\Results\Entity\Result::getTime()
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $user = new User();
        $now = new \DateTime('now');
        $result = new Result(
            15,
            $user,
            $now
        );
        $this->assertEquals(0, $result->getId(), "getId not pass testConstructor");
        $this->assertEquals(15, $result->getResult(), "getResult not pass testConstructor");
        $this->assertEquals($user, $result->getUser(), "getUser not pass testConstructor");
        $this->assertEquals($now->format('Y-m-d H:i:s'), $result->getTime(), "getTime not pass testConstructor");
    }

    /**
     * Implement testGet_Id().
     *
     * @covers \MiW\Results\Entity\Result::getId()
     * @return void
     */
    public function testGetId():void
    {
        $this->assertEquals(0, $this->result->getId(), "getId not pass testGetId");
    }

    /**
     * Implement testUsername().
     *
     * @covers \MiW\Results\Entity\Result::setResult
     * @covers \MiW\Results\Entity\Result::getResult
     * @return void
     */
    public function testResult(): void
    {
        $this->result->setResult(10);
        $this->assertEquals(10, $this->result->getResult(), "getResult not pass testResult");
    }

    /**
     * Implement testUser().
     *
     * @covers \MiW\Results\Entity\Result::setUser()
     * @covers \MiW\Results\Entity\Result::getUser()
     * @return void
     */
    public function testUser(): void
    {
        $user = new User();
        $this->result->setUser($user);
        $this->assertEquals($user, $this->result->getUser(), "getUser not pass testUser");
    }

    /**
     * Implement testTime().
     *
     * @covers \MiW\Results\Entity\Result::setTime
     * @covers \MiW\Results\Entity\Result::getTime
     * @return void
     */
    public function testTime(): void
    {
        $now = new \DateTime('now');
        $this->result->setTime($now);
        $this->assertEquals($now->format('Y-m-d H:i:s'), $this->result->getTime(), "getTime not pass testTime");
    }

    /**
     * Implement testTo_String().
     *
     * @covers \MiW\Results\Entity\Result::__toString
     * @return void
     */
    public function testToString(): void
    {
        $this->assertNotEmpty(strval($this->result), '__toString not pass testToString');
    }

    /**
     * Implement testJson_Serialize().
     *
     * @covers \MiW\Results\Entity\Result::jsonSerialize
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $this->assertNotEmpty($this->result->jsonSerialize(), 'jsonSerialize not pass testJsonSerialize');
    }
}
