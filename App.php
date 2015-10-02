<?php

require_once('Breakdown.php');

if ($file = file_get_contents($argv[1])) {
    $breakdown = new Breakdown($file);
    $continue = true;
    while ($continue) {
        echo "Choose a command to run:\n (p)profile_breakdown,\n (r)responses_breakdown,\n (a)attribute_breakdown_for_response,\n (x)exit\n";
        $handle = fopen("php://stdin", "r");
        $command = trim(fgets($handle));
        switch ($command) {
            case "x":
                $continue = false;
                break;
            case "p":
                echo "\nWhat attribute would you like a breakdown for? ";
                $handle = fopen("php://stdin", "r");
                $attribute = trim(fgets($handle));
                echo $breakdown->profile($attribute) . "\n";
                break;
            case "r":
                echo "\nHow would you like to filter the result (enter as json array)? ";
                $handle = fopen("php://stdin", "r");
                $filters = json_decode(trim(fgets($handle)), true);
                echo $breakdown->responses($filters) . "\n";
                break;
            case "a":
                echo "\nWhat option would you like a breakdown for? ";
                $handle = fopen("php://stdin", "r");
                $option_id = trim(fgets($handle));
                echo $breakdown->attribute($option_id) . "\n";
                break;
            default:
                echo "\n";
                break;
        }
    };
}
fclose($handle);
exit(0);