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

require_once('Whitelist.php');
use src\Whitelist;


function clearScreen(){
    echo "\e[H\e[J";
}

clearScreen();



// Parse arguments, such as username and whitelist location.
if($argc>1)
    parse_str($argv[1], $arg);


if( isset($arg['username'])){
    $user_info = $arg['username'];
}
else{
    print "Please enter the users name you would like to modify (UUIDs are valid if removing).\n";
   $user_info = readline();
}

$whiteListLocation = isset($arg['wl']) ? $arg['wl'] : getcwd() . '\whitelist.json';
if(!isset($arg['wl'])) print "No Whitelist provided, defaulting to current directory." . getcwd() . "\whitelist.json\n";
//End of parsing arguments.


$whitelist = new Whitelist($whiteListLocation);

// Options below for enhanced CLI experience.

$input = null;
while($input != 5){

    print "\n\nPlease select an option.\n
    [1]: Add Selected User (UUID's disallowed) to whitelist - ($user_info)\n
    [2]: Remove Selected User (UUID's allowed) to whitelist - ($user_info)\n
    [3]: Change Current Selected User.\n
    [4]: Change Current Whitelist Location. - ($whitelist->WhiteListPath)\n
    [5]: Exit Application.\n\n";

    $input = readline();
    if(!in_array($input, [1,2,3,4,5])){
        clearScreen();
        echo "Incorrect option, please try again. \n\n\n";
        continue;
    }
    if($input == 1){

        $user = $whitelist->getUserDetails($user_info);
        if($user) {
            $whitelist->addUserToWhitelist($user);
        }
        continue;
    }

    if($input == 2){
        $user = $whitelist->deleteUserFromWhitelist($user_info);
        continue;
    }
    if($input == 3){
        print "Please enter the users name you would like to modify (UUIDs are valid if removing).\n";
        $user_info = readline();
        continue;
    }
    if($input == 4){
        print "Please enter the new whitelist location. Example: C:\\Users\\Brett\\Desktop\\Minecraft_server would be what you would input here if the proper location. \n";
        $whitelist->WhiteListPath = readline();
        $whitelist->WhiteListPath .= "\\whitelist.json";
        continue;
    }

    if($input == 5){
        print "Good luck on your Minecraft server! Goodbye. \n";
        exit();
    }


}
