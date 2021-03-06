<?php

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-07-12 at 10:35:58.
 */
class UsuarioGrupoDAOTest extends PHPUnit_Framework_TestCase {

    /**
     * @var UsuarioGrupoDAO
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new UsuarioGrupoDAO;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    public function testNovo() {
        $user1 = createRandomUser();
        $grupo1 = createRandomGroup();
        $this->assertEquals(1, $this->object->novo($user1['id'], $grupo1['id']));
        $this->assertEquals(0, $this->object->novo($user1['id'], $grupo1['id']));
        $this->assertEquals(1, $this->object->deleteByUsuario($user1['id']));
        $this->assertEquals(1, $this->object->novo($user1['email'], $grupo1['nome']));
        $this->assertEquals(0, $this->object->novo($user1['email'], $grupo1['nome']));
        
        $user2 = createRandomUser();
        $grupo2 = createRandomGroup();
        $this->assertEquals(1, $this->object->novo($user1['email'], array($grupo2['id'], 'xyzaInvalid')));
        $this->assertEquals(2, $this->object->deleteByUsuario($user1['id']));
        $this->assertEquals(2, $this->object->novo($user1['email'], array($grupo1['nome'], $grupo2['id'])));
        
        //List By user
        $this->assertCount(2, $this->object->listByUsuario($user1['id']));
        $this->assertCount(2, $this->object->listByUsuario($user1['id']));
        $this->assertCount(0, $this->object->listByUsuario($user2['id']));
        $this->assertEquals(1, $this->object->novo($user2['id'], $grupo1['id']));
        $this->assertCount(1, $this->object->listByUsuario($user2['id']));
        
        //List By group
        $this->assertCount(2, $this->object->listByGrupo($grupo1['id']));
        $this->assertCount(1, $this->object->listByGrupo($grupo2['id']));
        $this->assertEquals(1, $this->object->novo($user2['id'], $grupo2['id']));
        $this->assertCount(2, $this->object->listByGrupo($grupo2['id']));
        
        //Invalid insert
        $this->assertEquals(
            0, $this->object->novo($user1['email'], array(-1, 'invalid', $grupo1['nome'], $grupo2['id']))
        );
        $this->assertEquals(0, $this->object->novo($user1['email'], array()));
        $this->assertEquals(0, $this->object->novo('emailinvalido', $grupo2['id']));
        $this->assertEquals(0, $this->object->novo(-12, $grupo2['id']));
    }
    
    public function testLists() {
        $user1 = createRandomUser();
        $user2 = createRandomUser();
        $grupo1 = createRandomGroup();
        $grupo2 = createRandomGroup();
        $grupos = array($grupo1['id'], $grupo2['id']);
        $this->assertEquals(2, $this->object->novo($user1['id'], $grupos));
        $grupo3 = createRandomGroup();
        $grupos[] = $grupo3['id'];
        $this->assertEquals(3, $this->object->novo($user2['id'], $grupos));
        
        $result1 = $this->object->listByGrupo($grupo1['id']);
        $this->assertEquals(array($user1['id'], $user2['id']), array_keys($result1));
        $this->assertEquals($user1['nome'], $result1[$user1['id']]['nome']);
        $this->assertEquals($user2['nome'], $result1[$user2['id']]['nome']);
        
        $result2 = $this->object->listByGrupo($grupo2['id'], 'usuario.nome');
        $this->assertEquals(array($user1['id'], $user2['id']), array_keys($result2));
        $this->assertEquals(array($user1['nome'], $user2['nome']), array_values($result2));
        
        $result3 = $this->object->listByUsuario($user1['email']);
        $this->assertEquals(array($grupo1['id'], $grupo2['id']), array_keys($result3));
        $this->assertEquals($grupo1['nome'], $result3[$grupo1['id']]['nome']);
        $this->assertEquals($grupo2['nome'], $result3[$grupo2['id']]['nome']);
        
        $result4 = $this->object->listByUsuario($user2['email'], 'grupo.nome');
        $this->assertEquals(array($grupo1['id'], $grupo2['id'], $grupo3['id']), array_keys($result4));
        $this->assertEquals(array($grupo1['nome'], $grupo2['nome'], $grupo3['nome']), array_values($result4));
        
        $this->assertEquals(array(), $this->object->listByUsuario('empty@mail.com'));
    }
}
