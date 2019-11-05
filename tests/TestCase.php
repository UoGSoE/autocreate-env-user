<?php

namespace Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/tests/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            '\UoGSoE\UserFromEnv\AutoCreateUserProvider',
            '\Ohffs\Ldap\LdapConnectionProvider',
            '\Ohffs\Ldap\LdapServiceProvider',
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Ldap' => 'Ohffs\Ldap\LdapFacade'
        ];
    }
}
