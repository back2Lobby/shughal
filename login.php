<?php 
session_start();

$host = 'sql207.move.pk';
$name = 'mov_25165757';
$pass = '496tayyab';
$dbname = 'mov_25165757_shughal';

$currentUser = "";

//connection with database
$dsn = 'mysql:host='.$host.';dbname='.$dbname;
$pdo = new PDO($dsn,$name,$pass);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

//Search if username already exists


if($_REQUEST['subType'] == 'Login'){

    $nametoSearch = strtolower($_REQUEST['name']);
    $enteredpass = strtolower($_REQUEST['password']);
    
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username LIKE :nametoSearch AND userpassword LIKE :enteredpass');
    $stmt->execute(['nametoSearch' => $nametoSearch,'enteredpass' => $enteredpass]);

    $res = $stmt->fetchAll();

    //creates response for the user
    if(count($res) > 0){
    foreach($res as $item){
        if(strtolower($item->username) == $nametoSearch){
            if(strtolower($item->userpassword) == $enteredpass){
                //on successfull Login
            
                $_SESSION['username'] = strtolower($item->username);

             echo $_SESSION['username'];
            }else{
                echo "Error: username or password is incorrect";
            }
        }else{
            echo "Error: No user found with this name. Please Create an account";
        }
    }
    }else{
        echo "Error: No user found with this name. Please Create an account";
    }

}elseif($_REQUEST['subType'] == 'Register'){
    $nametoSearch = strtolower($_REQUEST['name']);
    $enteredpass = strtolower($_REQUEST['password']);
    
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :nametoSearch');
    $stmt->execute(['nametoSearch' => $nametoSearch]);


    $res = $stmt->fetchAll();

        //creates response for the user
            if(count($res) > 0){
            foreach($res as $item){
                if(strtolower($item->username) == $nametoSearch){
                        //if a user already exist with the same username
                     die("Error: A user with this name already exists");
                }
            }
        }
                 //on successfull Registration
                 $stmt = $pdo->prepare('INSERT INTO users (username, userpassword) VALUES (:nametoSearch, :enteredpass)');
                 $stmt->execute(['nametoSearch'=>$nametoSearch,'enteredpass'=>$enteredpass]);

                $res = $stmt->fetchAll();
                //  echo $_SESSION['username'];
                $_SESSION['username'] = strtolower($nametoSearch);
                echo $_SESSION['username'];
            }
        // }




?>