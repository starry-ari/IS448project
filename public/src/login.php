<?php
//Creating session.
		
        session_start();
    
        #Connect to mySQL database.
        $db = mysqli_connect("studentdb-maria.gl.umbc.edu","arichar1","arichar1","arichar1");
    
        if (mysqli_connect_errno())
            exit("Error - Could not connect to MySQL");
            $username = htmlspecialchars($_POST['user']);
            $password = htmlspecialchars($_POST['psw']);
            
            $username = mysqli_real_escape_string($db,$username);
            $password = mysqli_real_escape_string($db,$password);
          
            if(empty($username)) {
                //Missing username.
                ?>
                <p> Please enter your username! </p>
                <?php
            }
            else {
                if(empty($password)) {
                    //Missing password.
                    ?>
                    <p>  Please enter your password! </p>
                    <?php
                }
                else {
                    #Construct SQL query to check database for username and password match.
                    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
                    
                    #Run constructed query.
                    $result = mysqli_query($db, $query);
                    
                    #If one result is returned, there is a match.
                    if(mysqli_num_rows($result) === 1) {
                        #Retrieving result from database.
                        $row = mysqli_fetch_assoc($result);
                        #Verifying that username and password match.
                        if($row['username'] === $username && $row['password'] === $password) {
                            
                            //Successful login.
                           
                            $_SESSION['username']=$username;
                         
                                   header("Location: ./home.php");
                                   exit();   
                     
                        }
                        else {
                            //Unsuccessful login.
                            ?>
                            <p> Invalid username and/or password. </p>
                            <?php
                        }
                    }
                    else {
                        //Unsuccessful login.
                        ?>
                        <p> Invalid username and/or password. </p>
                        <?php
                    }
                }
            }
            ?>
</body>
</html>