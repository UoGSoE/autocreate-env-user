<?php

namespace Tests;

use Tests\TestCase;

class CreateUsersTest extends TestCase
{
    /** @test */
    public function we_can_create_a_user_from_environment_variables()
    {
        putenv('AUTO_CREATE_ADMIN=fred');
        putenv('AUTO_CREATE_PASSWORD=ireadthenewstodayohboy');

        $this->artisan('autocreate:user');

        $this->assertDatabaseHas('users', [
            'username' => 'fred',
            'password' => bcrypt('ireadthenewstodayohboy')
        ]);
    }

}