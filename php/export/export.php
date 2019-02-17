<?php
require '../_modules.php';
require '../../vendor/autoload.php';
use GuzzleHttp\Client;
use \phpseclib\Net\SFTP;

//Get the program id to find the program record from the database
$program_id = $_POST['program_id'];
//Remote server login credentials
@$username = $_POST['sftp_username'];
@$password = $_POST['sftp_password'];

//Load up program database to determind if a remote server has been selected.
//Also we will use this database to fetch all records for exporting.
$program_db_file = DATA_PATH . 'program.json';
$programs_table_data = dump_table(
    array(
        'file' => $program_db_file,
        'table' => 'programs'
    )
);

for ($program_index = 0; $program_index < count($programs_table_data);
        $program_index++){

    if ($programs_table_data[$program_index]['id'] == $program_id) {

        //Check if login creditials are sent if not fire back a notification for
        //ajax to load up a prompt box asking for these details.
        if ($programs_table_data[$program_index]['remote'] == 'remote' &&
            $username == null  && $password == null) {
            echo "prompt";
        } else {

            //Include the code to compile the export of the program
            require 'compile_export.php';

            //Check if to export to remote server or stay local
            if ($programs_table_data[$program_index]['remote'] == 'remote') {

                // Use the phpseclib library to use the SFTP functions.
                $sftp = new SFTP(
                    $programs_table_data[$program_index]['host']
                );

                if (!$sftp->login($username, $password)) {
                    exit('Login Failed');
                }

                //Put the Program file up on the server
                $sftp->put($programs_table_data[$program_index]['server_path']
                    . kebab_case($program_name) . '.json',
                    DATA_PATH . kebab_case($program_name) . '.json',
                     \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE);

                //Put the Calendar ICS file up on the server
                $sftp->put($programs_table_data[$program_index]['server_path']
                    . kebab_case($program_name) . '.ics',
                    DATA_PATH . kebab_case($program_name) . '.ics',
                     \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE);

                //Put assets to server
                require 'transfer_files.php';

                echo "success";
            } else if (
                $programs_table_data[$program_index]['remote'] == 'bamboo'
            ) {
                require 'zip_files.php';

                $client = new Client();
                $request = $client->post(
                    'http://bamboo.apnic.net/rest/api/latest/queue/' .
                    $programs_table_data[$program_index]['bamboo'] .
                    '?stage&ExecuteAllStages',
                    [
                        // These credentials are not very important as this is only kept
                        // internal. I know it's bad :'(
                        'auth' => [
                            'webops-bamboo',
                            'decking.freely.heat'
                        ]
                    ]
                );

                echo "success";
            } else {
                echo "success";
            }
        }
    }
}
