# Apex Challenge - PHP Script

## Objective
- Develop a script that can automatically generate a whitelist, utilizing the Mojang API, to generate a UUID from any given valid username. 
- Store all players within a JSON file, which will be an array of different users.
- Ability to remove said user, given a username OR UUID (API only allows queries via username, so additions will be only via in-game name).

## Design a Plan:
  -   Use Curl to initially grab information from the Mojang API.
  -   Check status codes as outlined within their API to do error handling. Generally done via basic if statements, but if designed in Laravel I would do exception handling more cleanly.
  -   Give user ability to generate a whitelist or provide their own (via CLI).
  -   Give user ability to delete a player, if they exist, otherwise throw an error.
  -   Have an interface upon using the script (1, 2, 3, etc) to give the user options for ease of use, with input sanitization.


## Dependencies (Windows)
- Composer (https://getcomposer.org/download/ used v2.7.7)
- PHP 8.3.8 (https://windows.php.net/download#php-8.3)

## Usability / Full Installation

If you have Composer installed, and are running into issues, I recommend installing the Composer components, by being in the root directory and running _composer install._ This generally is only needed if running Unit Tests.

To use the script, you will simply run it with PHP, such as: php .\src\whiteListScript.php -- This is ran from the root of the Git directly. The whitelist generation will always be done in the directory where you ran the program from, unless specified.

When running with no paramaters, you will be prompted to enter in a username, if adding, or UUID, if you are choosing to remove a user. It will also prompt you accordingly that a Whitelist will be defaulted, and if not existing, created in the root directory of where you are executing.

After choosing from the options, as seen below, it will continue forward until you choose to exit, see below for said example.

            [1]: Add Selected User (UUID's disallowed) to whitelist - (duality13011)

            [2]: Remove Selected User (UUID's allowed) to whitelist - (duality13011)

            [3]: Change Current Selected User.

            [4]: Change Current Whitelist Location. - (C:\Users\13011\git\apex_challenge\whitelist.json)

            [5]: Exit Application.

### Paramaters

-  -username | Specify users name immediately instead of within script. Ex: php .\src\whiteListScript.php username="13011brett"
- -wl |  Specify whitelist location, can be done within CLI as well when using script. Ex: php .\src\whiteListScript.php -wl="C:\users\13011\desktop\minecraft_server\whitelist.json"
- -add | -a - Allows for immediate adding of user to whitelist (be sure to specify the whitelist). php .\src\whiteListScript.php username="13011brett" -a -wl="C:\users\13011\desktop\minecraft_server\whitelist.json"
- -remove | -r Allows for immediate removal of user from whitelist (be sure to specify the whitelist). php .\src\whiteListScript.php username="13011brett" -a -wl="C:\users\13011\desktop\minecraft_server\whitelist.json"
- -quick | -q Makes the script quit after completion (Note: Only works if you specify the parameters above).


## Unit Tests

All unit tests are used within PHPUnit, and are expected to run together. To run them, you can either automatically do it via PHPStorm or any IDE of your choice, or manually via the terminal. ex from root directory: ./vendor/bin/phpunit

!! Do note, that it will remove any Whitelist that is in the current directory you are working within. This is by design, to fully test its capabilites of creating the JSON every run.
