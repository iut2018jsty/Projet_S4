<?php
use PHPUnit\Framework\TestCase;

require_once 'controller/ControllerDate.php';

/**
 * ControllerDate test case.
 */
class ControllerDateTest extends TestCase
{

    /**
     *
     * @var ControllerDate
     */
    private $controllerDate;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // TODO Auto-generated ControllerDateTest::setUp()

        $this->controllerDate = new ControllerDate(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated ControllerDateTest::tearDown()
        $this->controllerDate = null;

        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests ControllerDate::formatDay()
     */
    public function testFormatDay()
    {
        // TODO Auto-generated ControllerDateTest::testFormatDay()
        $this->markTestIncomplete("formatDay test not implemented");

        ControllerDate::formatDay(/* parameters */);
    }

    /**
     * Tests ControllerDate::getWeek()
     */
    public function testGetWeek()
    {
        // TODO Auto-generated ControllerDateTest::testGetWeek()
        $this->markTestIncomplete("getWeek test not implemented");

        ControllerDate::getWeek(/* parameters */);
    }

    /**
     * Tests ControllerDate::getLundi()
     */
    public function testGetLundi()
    {
        // TODO Auto-generated ControllerDateTest::testGetLundi()
        $this->markTestIncomplete("getLundi test not implemented");

        ControllerDate::getLundi(/* parameters */);
    }
}
