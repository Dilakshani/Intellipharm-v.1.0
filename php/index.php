<html>
    <head>
        <title>Intellipharm Application</title>
    </head>

    <body>
        <?php

        function get_data(){

             // Database configuration
           include 'config.php';

           $sql = mysqli_query($con,"select * from members order by id desc");

           //JSON array declaration
           $members = array();

           while ($row = mysqli_fetch_assoc($sql)) {
                //$members = $row ;
                $members[] = array (
                    "firstname" => $row['firstname'],
                    "surname" => $row['surname'],
                    "email" => $row['email'],
                    "gender" => $row['gender'],
                    "joined_date" => $row['joined_date']
                );
           }
            return json_encode(array('members' => $members));
        }
            $file_name = members.json;

            //if file not generated
            if (file_put_contents($file_name,get_data())){
                    echo "Success: File has been craeted successfully";
            }
            else {
                echo "Failed : File has not been added.Please find the error";
            }

        ?>
    </body>
</html>
