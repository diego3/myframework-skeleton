<?php

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-07-12 at 09:19:31.
 */
class UsuarioDAOTest extends PHPUnit_Framework_TestCase {

    /**
     * @var UsuarioDAO
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new UsuarioDAO;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    public function testNovo() {
        $name = 'User ' . rand();
        $email = $name . '@test.com';
        $this->assertEquals(1, $this->object->novo($name, $email, hashit('xyaza')));
        $this->assertEquals(0, $this->object->novo($name, $email, hashit('xyaza')));
        
        //Invalid inserts
        $this->assertEquals(0, $this->object->novo('Name test', 'xyz@test.com', 'xyaza'), 'Invalid password');
        $this->assertEquals(0, $this->object->novo('Name test', 'xyz', 'xyaza'), hashit('xyaza'));
        $this->assertEquals(0, $this->object->novo('', 'xyz', 'xyz@test.com'), hashit('xyaza'));
    }

    public function testGetByEmail() {
        $data = $this->object->getByEmail('admin@admin.com');
        $this->assertNotEmpty($data);
        $this->assertEquals('admin@admin.com', $data['email']);
        
        $email = 'randname' . rand() . '@test.com';
        $this->assertEmpty($this->object->getByEmail($email));
        $this->assertEquals(1, $this->object->novo($email, $email, hashit('password')));
        $user = $this->object->getByEmail($email);
        $this->assertNotEmpty($user);
        $this->object->delete($user['id']);
        $this->assertEmpty($this->object->getByEmail($email));
    }
    
    public function testUsuarioGrupo() {
        $this->assertTrue($this->object->UsuarioGrupo() instanceof UsuarioGrupoDAO);
    }

}