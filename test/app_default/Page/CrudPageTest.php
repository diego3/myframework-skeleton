<?php

namespace Application\Page;

class AbstractCrudPage extends \Application\Page\CrudPage {
    public function getTableSchema() {
        
    }

    public function setParameters() {
        
    }

}

class CrudPageTest extends \PHPUnit_Framework_TestCase {

    /**
     * 
     * @var CrudPage
     */
    protected $cp;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->cp = new AbstractCrudPage($em);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Application\Page\CrudPage::preEdit
     * @todo   Implement testPreEdit().
     */
    public function testPreEdit() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Application\Page\CrudPage::_edit
     * @todo   Implement test_edit().
     */
    public function test_edit() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Application\Page\CrudPage::preSave
     * @todo   Implement testPreSave().
     */
    public function testPreSave() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Application\Page\CrudPage::_save
     * @todo   Implement test_save().
     */
    public function test_save() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Application\Page\CrudPage::preList
     * @todo   Implement testPreList().
     */
    public function testPreList() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Application\Page\CrudPage::_list
     * @todo   Implement test_list().
     */
    public function test_list() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Application\Page\CrudPage::_delete
     * @todo   Implement test_delete().
     */
    public function test_delete() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
