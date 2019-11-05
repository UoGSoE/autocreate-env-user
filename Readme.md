# Autocreate Admin User From Environment/Secrets

WIP!

This is a small package (specific to our setup) to allow auto-creating an admin user based on environment variables.  As we increasingly use Docker/Containers to
deploy things we wanted a way of creating an initial admin account on new deploys without having to do something like `docker exec some-container bash`
and doing things by hand.

## Usage

You can set the following environment variables :

* AUTO_CREATE_ADMIN - the username of the new admin user
* AUTO_CREATE_PASSWORD - used if the account is local/non-ldap
* AUTO_CREATE_EMAIL - email address for the user

Or there are file-based versions for use with Docker secrets :

* AUTO_CREATE_ADMIN_FILE - file containing the usersname
* AUTO_CREATE_PASSWORD_FILE - file containing the password
* AUTO_CREATE_EMAIL_FILE - file containing the email

Then as part of your deployment / entrypoint you can call :

```
php artisan autocreate:admin
```

The command will check for the environment variables and try to create a user.  The `_FILE` variables have precedence.  If the username can't
be found in LDAP (or there is no LDAP set up) it will create a local user.

Our `users` table differs from the default Laravel one.  We have :
```
username
surname
forenames
email
is_staff
is_admin
```
I'll hopefully get round to doing some kind of mapping so this package is more useful to people outside of our setup.

