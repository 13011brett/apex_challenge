<?php

use PHPUnit\Framework\TestCase;
use src\Whitelist;

// IMPORTANT - These unit tests, especially creating the Whitelist and destroying it are in conjunction with each-other. Running them separately will cause issues.

class WhitelistTest extends TestCase {

    public function testQueryMojangApiSuccess() {
        
        $whitelist = new Whitelist;
        $user = $whitelist->getUserDetails("Duality13011");
        $this->assertEquals(0, (empty($user)));
        
    }
    public function testQueryMojangApiFailure() {

        $whitelist = new Whitelist;
        $user = $whitelist->getUserDetails("TestUsernamethatwillneverequaltotrue$12399");
        $this->assertEquals(0, $user);

    }

    public function testWhiteListAddition(){
        $whitelist = new Whitelist;

        $user = $whitelist->getUserDetails("Duality13011");
        $whitelist->addUserToWhitelist($user);

        $user2 = $whitelist->getUserDetails("13011brett");
        $whitelist->addUserToWhitelist($user2);

        $this->assertTrue(
            $whitelist->queryWhitelistForUser($user)
            && $whitelist->queryWhitelistForUser($user2));
    }
    public function testWhiteListRemovalByNameAndUUID(){
        $whitelist = new Whitelist;

        $user = "duality13011";
        $whitelist->deleteUserFromWhitelist($user);

        $user2 = "9b579140a41341fb99b1e1dfb3007023";
        $whitelist->deleteUserFromWhitelist($user2);

        $this->assertFalse(
            $whitelist->queryWhitelistForUser($user)
            && $whitelist->queryWhitelistForUser($user2));

        if(file_exists('whitelist.json')) unlink('whitelist.json');


    }


}