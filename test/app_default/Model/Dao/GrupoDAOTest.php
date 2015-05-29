<?php

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-07-11 at 00:26:09.
 */
class GrupoDAOTest extends PHPUnit_Framework_TestCase {

    /**
     * @var GrupoDAO
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new GrupoDAO;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    public function testGroupDAO() {
        $name = 'Teste ' . rand();
        $this->assertEquals(1, $this->object->novo($name, 'descrição'));
        $row = $this->object->listByCondition('*', array('nome' => $name));
        $this->assertNotEmpty($row);
        $result = $this->object->getById($row[0]['id']);
        $this->assertEquals($row[0], $result);
        $result2 = $this->object->getByNome($name);
        $this->assertNotEmpty($result2);
        $this->assertEquals($result, $result2);
        $this->assertEquals(1, $this->object->delete($result['id']));
        $this->assertEmpty($this->object->getByNome($name));
    }
    
    public function testUsuarioGrupo() {
        $this->assertTrue($this->object->UsuarioGrupo() instanceof UsuarioGrupoDAO);
    }
}
