<?php
/**
 * @package    ApiBundle\Tests\Controller;
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */
namespace ApiBundle\Tests\Controller;

class UserControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    public function testUserCanBeCreated()
    {
        $this->markTestSkipped();
    }

    public function testUserCreateThrowsErrorWithInvalidData()
    {
        $this->markTestIncomplete();
    }

    public function testUserCanBeDeleted()
    {
        $this->markTestIncomplete();
    }

    public function testUserDeleteThrowsErrorWithInvalidIdentifier()
    {
        $this->markTestIncomplete();
    }

    public function testUserCanBeMovedToArchive()
    {
        $this->markTestIncomplete();
    }

    public function testUserCanBeUpdated()
    {
        $this->markTestIncomplete();
    }
}