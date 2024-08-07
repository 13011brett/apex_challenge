// Version 0.1 by Brett Scarlett

//.----------------.  .----------------.  .----------------.  .----------------.   .----------------.  .----------------.  .----------------.  .----------------.  .----------------.  .----------------.  .-----------------. .----------------.  .----------------.
//| .--------------. || .--------------. || .--------------. || .--------------. | | .--------------. || .--------------. || .--------------. || .--------------. || .--------------. || .--------------. || .--------------. || .--------------. || .--------------. |
//| |      __      | || |   ______     | || |  _________   | || |  ____  ____  | | | |     ______   | || |  ____  ____  | || |      __      | || |   _____      | || |   _____      | || |  _________   | || | ____  _____  | || |    ______    | || |  _________   | |
//| |     /  \     | || |  |_   __ \   | || | |_   ___  |  | || | |_  _||_  _| | | | |   .' ___  |  | || | |_   ||   _| | || |     /  \     | || |  |_   _|     | || |  |_   _|     | || | |_   ___  |  | || ||_   \|_   _| | || |  .' ___  |   | || | |_   ___  |  | |
//| |    / /\ \    | || |    | |__) |  | || |   | |_  \_|  | || |   \ \  / /   | | | |  / .'   \_|  | || |   | |__| |   | || |    / /\ \    | || |    | |       | || |    | |       | || |   | |_  \_|  | || |  |   \ | |   | || | / .'   \_|   | || |   | |_  \_|  | |
//| |   / ____ \   | || |    |  ___/   | || |   |  _|  _   | || |    > `' <    | | | |  | |         | || |   |  __  |   | || |   / ____ \   | || |    | |   _   | || |    | |   _   | || |   |  _|  _   | || |  | |\ \| |   | || | | |    ____  | || |   |  _|  _   | |
//| | _/ /    \ \_ | || |   _| |_      | || |  _| |___/ |  | || |  _/ /'`\ \_  | | | |  \ `.___.'\  | || |  _| |  | |_  | || | _/ /    \ \_ | || |   _| |__/ |  | || |   _| |__/ |  | || |  _| |___/ |  | || | _| |_\   |_  | || | \ `.___]  _| | || |  _| |___/ |  | |
//| ||____|  |____|| || |  |_____|     | || | |_________|  | || | |____||____| | | | |   `._____.'  | || | |____||____| | || ||____|  |____|| || |  |________|  | || |  |________|  | || | |_________|  | || ||_____|\____| | || |  `._____.'   | || | |_________|  | |
//| |              | || |              | || |              | || |              | | | |              | || |              | || |              | || |              | || |              | || |              | || |              | || |              | || |              | |
//| '--------------' || '--------------' || '--------------' || '--------------' | | '--------------' || '--------------' || '--------------' || '--------------' || '--------------' || '--------------' || '--------------' || '--------------' || '--------------' |
// '----------------'  '----------------'  '----------------'  '----------------'   '----------------'  '----------------'  '----------------'  '----------------'  '----------------'  '----------------'  '----------------'  '----------------'  '----------------'


<?php

// CLI Quick Examples
// Add a user to a specified Whitelist: php .\src\whiteListScript.php username="13011brett" -add -quick (this will allow for adding and then the script quits.


// Full documentation on what parameters exist.
// -username | Specify users name immediately instead of within script. Ex: php .\src\whiteListScript.php username="13011brett"
// -wl |  Specify whitelist location, can be done within CLI as well when using script. Ex: php .\src\whiteListScript.php -wl="C:\users\13011\desktop\minecraft_server\whitelist.json"
// -add | -a - Allows for immediate adding of user to whitelist (be sure to specify the whitelist). php .\src\whiteListScript.php username="13011brett" -a -wl="C:\users\13011\desktop\minecraft_server\whitelist.json"
// -remove | -r Allows for immediate removal of user from whitelist (be sure to specify the whitelist). php .\src\whiteListScript.php username="13011brett" -a -wl="C:\users\13011\desktop\minecraft_server\whitelist.json"
// -quick | -q Makes the script quit after completion (Note: Only works if you specify the parameters above).


require_once('Whitelist.php');
use src\Whitelist;


function clearScreen(){
    echo "\e[H\e[J";
}

clearScreen();



// Parse arguments, such as username and whitelist location.
if($argc>1)
    parse_str(implode('&',array_slice($argv, 1)), $_GET);

if(isset($_GET['-add']) || isset($_GET['-a'])) $input = 1;
if(isset($_GET['-remove']) || isset($_GET['-r'])) $input = 2;

if( isset($_GET['username'])){
    $user_info = $_GET['username'];
}
else{
    print "Please enter the users name you would like to modify (UUIDs are valid if removing).\n";
   $user_info = readline();
}

$whiteListLocation = isset($_GET['wl']) ? $_GET['wl'] : getcwd() . '\whitelist.json';
if(!isset($_GET['wl'])) print "No Whitelist provided, defaulting to current directory: " . getcwd() . "\whitelist.json\n";



$whitelist = new Whitelist($whiteListLocation);

// Options below for enhanced CLI experience.

if(!isset($input)) $input = 0;
while($input != 5){

    if($input == 0) {
        print "\n\nPlease select an option.\n
            [1]: Add Selected User (UUID's disallowed) to whitelist - ($user_info)\n
            [2]: Remove Selected User (UUID's allowed) to whitelist - ($user_info)\n
            [3]: Change Current Selected User.\n
            [4]: Change Current Whitelist Location. - ($whitelist->WhiteListPath)\n
            [5]: Exit Application.\n\n";

        $input = readline();
    }



    if(!in_array($input, [1,2,3,4,5])){
        clearScreen();
        echo "Incorrect option, please try again. \n\n\n";
        $input = 0;
        continue;
    }
    if($input == 1){

        $user = $whitelist->getUserDetails($user_info);
        if($user) {
            $whitelist->addUserToWhitelist($user);
        }
        if(isset($_GET['-q']) || isset($_GET['-quick'])) $input = 5;
        else $input = 0;
        continue;
    }

    if($input == 2){
        $user = $whitelist->deleteUserFromWhitelist($user_info);
        if(isset($_GET['-q']) || isset($_GET['-quick'])) $input = 5;
        else $input = 0;

        continue;
    }
    if($input == 3){
        print "Please enter the users name you would like to modify (UUIDs are valid if removing).\n";
        $user_info = readline();
        $input = 0;
        continue;
    }
    if($input == 4){
        print "Please enter the new whitelist location. Example: C:\\Users\\Brett\\Desktop\\Minecraft_server would be what you would input here if the proper location. \n";
        $whitelist->WhiteListPath = readline();
        $whitelist->WhiteListPath .= "\\whitelist.json";
        $input = 0;
        continue;
    }

    if($input == 5){
        print "Good luck on your Minecraft server! Goodbye. \n";
        exit();
    }


}
