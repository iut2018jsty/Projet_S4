<?php
use PHPUnit\Framework\TestCase;
require_once './model/Model.php';
/**
 * Model test case.
 */
class ModelTest extends TestCase
{

    /**
     *
     * @var Model
     */
    private $model;
    
    /**
     * Tests Model::Init()
     */
    public function testInit()
    {
        // TODO Auto-generated ModelTest::testInit()
        $this->markTestIncomplete("Init test not implemented");
        
        assertTrue(Model::Init());
    }

    /**
     * Tests Model::getPDO()
     */
    public function testGetPDO()
    {
        // TODO Auto-generated ModelTest::testGetPDO()
        $this->markTestIncomplete("getPDO test not implemented");
        $pdo = new PDO("mysql:host=webinfo.iutmontp.univ-montp2.fr;dbname=bourdesj", "bourdesj", "Unicorn", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        Model::Init();
        $this->assertEquals($pdo,Model::getPDO());
    }

    /* On ne peut pas tester les fonctions du mod�le g�n�rique
    
     * Tests Model::selectAll()
     
    public function testSelectAll()
    {
        // TODO Auto-generated ModelTest::testSelectAll()
        $this->markTestIncomplete("selectAll test not implemented");

        Model::selectAll();
    }

    
     * Tests Model::select()
     
    public function testSelect()
    {
        // TODO Auto-generated ModelTest::testSelect()
        $this->markTestIncomplete("select test not implemented");

        Model::select(/* parameters );
    }

    
     Tests Model::delete()
     
    public function testDelete()
    {
        // TODO Auto-generated ModelTest::testDelete()
        $this->markTestIncomplete("delete test not implemented");

        Model::delete(/* parameters );
    }

    /**
     * Tests Model::update()
     
    public function testUpdate()
    {
        // TODO Auto-generated ModelTest::testUpdate()
        $this->markTestIncomplete("update test not implemented");

        Model::update(/* parameters );
    }

    /**
     * Tests Model::created()
     
    public function testCreated()
    {
        // TODO Auto-generated ModelTest::testCreated()
        $this->markTestIncomplete("created test not implemented");

        Model::created(/* parameters );
    }

    /**
     * Tests Model::selectAllWithArgs()
     
    public function testSelectAllWithArgs()
    {
        // TODO Auto-generated ModelTest::testSelectAllWithArgs()
        $this->markTestIncomplete("selectAllWithArgs test not implemented");

        Model::selectAllWithArgs(/* parameters );
    } */
}

