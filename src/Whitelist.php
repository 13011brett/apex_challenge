<?php

namespace src;

//Class could be more generalized in the future to be a whitelist class for all games. Currently only for Minecraft.
class Whitelist{

    public $WhiteListPath = "whitelist.json";
    public function __construct($whiteListPath = "whitelist.json")
    {
        $this->WhiteListPath = $whiteListPath;

    }


    public function getUserDetails($user = ""){
        
        $httpCode = "";

        if($user == ""){
            echo 'Please input your users name.';
            $user = rtrim(fgets(STDIN));
        }
        
        $ch = curl_init();
    
        $url = "https://api.mojang.com/users/profiles/minecraft/$user";
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        

        // Execute the API call (get request) to Mojang to check if valid user.

        $resp = curl_exec($ch);
        $error = curl_error($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //Close the Curl Connection before exiting.
        curl_close($ch);

        if($error){
            echo 'Curl returned error: ' . $error . "\n Please ensure your PHP / Curl is setup properly.";
            exit();
        }


        if($httpCode == 404){
            print "Invalid User! Please try again. \n\n";
            return 0;
        }
        if($httpCode == 500){
            print "Internal server error! You may be rate limited for the next 10 minutes.. \n\n";
            return 0;
        }

        if($httpCode == 200){

            return json_decode($resp);
        }
    }
    public function queryWhitelistForUser($user)
    {
        $currentWhitelist = file_get_contents($this->WhiteListPath);
        $currentWhitelistDecoded = json_decode($currentWhitelist, true);

        //Iterate through the current Allow list and see for duplicates.
        for ($x = 0; $x < count($currentWhitelistDecoded); $x++) {

            if ($user->id == $currentWhitelistDecoded[$x]['uuid']) {
                return true;
            }
        }
        return false;
    }
    
    public function deleteUserFromWhitelist($user){

        if(!is_writable($this->WhiteListPath)){
            print "A whitelist needs to be writable. Please check your permissions and ensure this file is not currently in use.";
            return false;
        }

        if(!file_exists($this->WhiteListPath)){
            echo "A whitelist needs to be provided to delete a user.";
            return false;
        }


        $currentWhitelist = file_get_contents($this->WhiteListPath);
        $currentWhitelistDecoded = json_decode($currentWhitelist, true);
        for($x = 0; $x < count($currentWhitelistDecoded); $x++){
            
            if($user == $currentWhitelistDecoded[$x]['uuid'] || $user == $currentWhitelistDecoded[$x]['name']){
                print_r($currentWhitelistDecoded[$x]['name'] . " has been removed!\n");
                unset( $currentWhitelistDecoded[$x] );
                $newWhiteList = array_values($currentWhitelistDecoded);
                $newWhitelistJson = json_encode($newWhiteList, JSON_PRETTY_PRINT);

                file_put_contents($this->WhiteListPath, $newWhitelistJson);
                return true;
            }

        }
        print "$user could not be found. Please try again. \n";
        return false;
    
    

    
    }
    public function addUserToWhitelist($user){

        if(!$user->id || !$user->name){
            print "Invalid user! Please try again. \n\n";
            return false;
            }

        if(!file_exists($this->WhiteListPath)){
            //Add the user to the Whitelist (CODE FOR IF WHITELIST DOESN'T EXIST)

            $newWhitelistArray = Array(
                "0" => Array(
                    "uuid" => $user->id,
                    "name" => $user->name
                )
            );

            $newWhitelistJson = json_encode ($newWhitelistArray, JSON_PRETTY_PRINT);

            //Finally, create the file if it doesn't exist

            file_put_contents($this->WhiteListPath, $newWhitelistJson);

            print ("Whitelist created and $user->name added!\n");
            return true;
        }

        if(!is_writable($this->WhiteListPath)){
            print "A whitelist needs to be writable. Please check your permissions and ensure this file is not currently in use.";
            return false;
        }
    

        else{
            $currentWhitelist = file_get_contents($this->WhiteListPath);
            $currentWhitelistDecoded = json_decode($currentWhitelist, true);
            
            //Iterate through the current Allow list and see for duplicates.
            for($x = 0; $x < count($currentWhitelistDecoded); $x++){
            
                if($user->id == $currentWhitelistDecoded[$x]['uuid']){
                    echo "This user has already been allowed to join the server!";
                    return false;
                }
            }
            
            $newUserAddition =
                Array(
                    "uuid" => $user->id,
                    "name" => $user->name
                );
                array_push($currentWhitelistDecoded, $newUserAddition);
            
                $newWhitelistJson = json_encode($currentWhitelistDecoded, JSON_PRETTY_PRINT);
            
                file_put_contents($this->WhiteListPath, $newWhitelistJson);
                print("$user->name has been allowed to join the server!\n");
                return true;
        }
        
    }
    
    
}




