<?php
namespace Tests;

use Diagro\Token\Model\Application;
use Diagro\Token\Model\Company;
use Diagro\Token\Model\Permission;
use Diagro\Token\Model\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    private ?User $user = null;

    protected function setUp(): void
    {
        $user = new User(1, 'Stijn', 'nl_BE', 'nld', 'Europe/Brussels', 'admin');
        $company = new Company(1, 'Diagro', 'Belgium', 'EUR');

        $app = new Application(1, 'UnitTestApp');
        $app->addPermission('testen', new Permission('crup'));
        $app->addPermission('facturatie', new Permission('ep'));

        $app2 = new Application(2, 'DocManager');
        $app2->addPermission('file', new Permission('r'));
        $app2->addPermission('folder', new Permission('rcu'));
        $app2->addPermission('facturatie', new Permission('ud'));

        $user->company($company);
        $user->addApplication($app);
        $user->addApplication($app2);

        $this->user = $user;
    }

    public function testPermissionIndividual()
    {
        $this->assertTrue($this->user->can('read', 'testen'));
    }

    public function testPermissionGrouped()
    {
        $this->assertTrue($this->user->can(['create', 'read'], 'folder'));
        $this->assertFalse($this->user->can(['create', 'read'], 'facturatie'));
    }

    public function testPermissionIndividualMultipleApps()
    {
        $this->assertFalse($this->user->can('read', ['testen', 'facturatie']));
        $this->assertTrue($this->user->can('read', ['testen', 'file']));
    }

    public function testPermissionGroupedMultipleApps()
    {
        $this->assertTrue($this->user->can(['read', 'update'], ['testen', 'folder']));
        $this->assertFalse($this->user->can(['create', 'update'], ['facturatie', 'file']));
    }

    public function testPermissionAppDotted()
    {
        $this->assertTrue($this->user->can('export', 'UnitTestApp.facturatie'));
        $this->assertFalse($this->user->can('export', 'DocManager.facturatie'));
        $this->assertFalse($this->user->can('create', 'DocManager.file'));
    }

    public function testPermissionsMixed()
    {
        $this->assertTrue($this->user->can(['read', 'update'], ['UnitTestApp.testen', 'folder']));
        $this->assertFalse($this->user->can('export', ['DocManager.facturatie', 'file']));
    }

}
