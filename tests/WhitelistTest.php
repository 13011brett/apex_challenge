<?php

class WhitelistTest extends \PHPUnit\Framework\TestCase {
    public function TestQueryMojangApiSuccess() {
        
        $whitelist = new Whitelist;
        $userInfo = $whitelist->getUserDetails("Duality13011");
        $this->assertEquals(0, (empty($userInfo)));
        
    }
    public function TestQueryMojangApiFailure() {
        
        $whitelist = new Whitelist;
        $userInfo = $whitelist->getUserDetails("TestUsernamethatwillneverequaltotrue$12399");
        $this->assertEquals(0, $userInfo);
        
    }


}