<?php

namespace UoGSoE\UserFromEnv;

use Faker\Generator as Faker;
use Illuminate\Support\Str;

class AutoCreateUserCommand extends \Illuminate\Console\Command
{
    protected $signature = 'autocreate:user';

    protected $description = 'Autocreate a user from env variables/secrets';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Faker $faker)
    {
        if (!(env('AUTO_CREATE_ADMIN') or env('AUTO_CREATE_ADMIN_FILE'))) {
            $this->info('Not auto-creating an admin account');
            return;
        }

        $username = env('AUTO_CREATE_ADMIN');
        if (env('AUTO_CREATE_ADMIN_FILE')) {
            $username = trim(file_get_contents(env('AUTO_CREATE_ADMIN_FILE')));
        }
        $password = env('AUTO_CREATE_PASSWORD', 'hellokitty');
        if (env('AUTO_CREATE_PASSWORD_FILE')) {
            $password = trim(file_get_contents(env('AUTO_CREATE_PASSWORD_FILE')));
        }
        $email = env('AUTO_CREATE_EMAIL', $faker->safeEmail);
        if (env('AUTO_CREATE_EMAIL_FILE')) {
            $email = trim(file_get_contents(env('AUTO_CREATE_EMAIL_FILE')));
        }

        if (env('LDAP_SERVER')) {
            try {
                $ldapUser = \Ldap::findUser($username);
                if ($ldapUser) {
                    app(config('auth.providers.users.model'))->create([
                        'username' => $ldapUser->username,
                        'password' => bcrypt(Str::random(64)),
                        'email' => $ldapUser->email,
                        'is_staff' => true,
                        'is_admin' => true,
                        'surname' => $ldapUser->surname,
                        'forenames' => $ldapUser->forenames,
                    ]);
                    $this->info("Auto created LDAP admin");
                    return;
                }
            } catch (\Exception $e) {
                $this->info('Failed to look up auto create user in LDAP');
            }
        }
        app(config('auth.providers.users.model'))->create([
            'username' => $username,
            'password' => bcrypt($password),
            'email' => $email,
            'is_staff' => true,
            'is_admin' => true,
            'surname' => $faker->lastName,
            'forenames' => $faker->firstName,
        ]);
        $this->info('Auto created local admin');
    }
}