<?php
class Whitelist{
    public function getUserDetails($user = ""){
        
        $httpCode = "";

        if($user == ""){
            $user = rtrim(fgets(STDIN));
        }
        
        $ch = curl_init();
    
        $url = "https://api.mojang.com/users/profiles/minecraft/$user";
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        
        
        
    
        
        // Execute the API call (get request) to Mojang to check if valid user.
        
        $resp = curl_exec($ch);
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpCode == 404){
            echo "Invalid User! Please try again. \n\n";
            return 0;
        }
        curl_close($ch);
    
        
        if($httpCode == 200){
            return json_decode($resp);
        }
    }
    
    
    public function deleteUserFromWhitelist($user){
        if(!file_exists("whitelist.json")){
            echo "A whitelist needs to be provided to delete a user.";
            return false;
        }
        $currentWhitelist = file_get_contents("whitelist.json");
        $currentWhitelistDecoded = json_decode($currentWhitelist, true);
        for($x = 0; $x < count($currentWhitelistDecoded); $x++){
            
            if($user->id == $currentWhitelistDecoded[$x]['uuid'] || $user->name == $currentWhitelistDecoded[$x]['name']){
                unset( $currentWhitelistDecoded[$x] );
                echo "$user->name has been removed!";
            }
        }
    
    
        $newWhitelistJson = json_encode($currentWhitelistDecoded, JSON_PRETTY_PRINT);
    
        file_put_contents("whitelist.json", $newWhitelistJson);
        return true;
    
    }
    public function addUserToWhitelist($user){
    
    
        if(!file_exists("whitelist.json")){
            //Add the user to the Whitelist (CODE FOR IF WHITELIST DOESN'T EXIST)
            $newWhitelistArray = Array(
            "0" => Array(
                "uuid" => $user->id,
                "name" => $user->name
                )
            );
        
            $newWhitelistJson = json_encode ($newWhitelistArray, JSON_PRETTY_PRINT);
        
            //Finally, create the file if it doesn't exist
        
            file_put_contents("whitelist.json", $newWhitelistJson);
            return true;
        }
        else{
            $currentWhitelist = file_get_contents("whitelist.json");
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
            
                file_put_contents("whitelist.json", $newWhitelistJson);
                return true;
        }
        
    }
    
    
}




