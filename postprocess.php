<?php 
session_start();
$host = 'sql207.move.pk';
$name = 'mov_25165757';
$pass = '496tayyab';
$dbname = 'mov_25165757_shughal';

//saves current loged in user to session storage
$cUser = $_REQUEST['user'];
// $userSearch = "'$cUser'";
//process for new posts
if($_REQUEST["ins"] !== "loadposts"){

//connection with database
$dsn = 'mysql:host='.$host.';dbname='.$dbname;
$pdo = new PDO($dsn,$name,$pass);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

//validation of current User
$stmt = $pdo->prepare('SELECT * FROM users WHERE username LIKE :cUser');
$stmt->execute(['cUser'=>$cUser]);
$res = $stmt->fetch(PDO::FETCH_ASSOC);

if(count($res) > 0){
    if(strtolower($res['username']) == $cUser){
        $userID = $res['id'];
        //send post to server
        $stmt = $pdo->prepare('INSERT INTO posts(id,body) VALUES(:userID,:pbody)');
        $stmt->execute(['userID'=>$userID,'pbody'=> $_REQUEST['pbody']]);
        $res = $stmt->fetch();

        echo "ok";
    }
}
}
//load published posts from database
if($_REQUEST['ins'] == "loadposts"){

//connection with database
$dsn = 'mysql:host='.$host.';dbname='.$dbname;
$pdo = new PDO($dsn,$name,$pass);

//validation of current User
$stmt = $pdo->prepare('SELECT * FROM posts');
$stmt->execute();
// $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $tst = $pdo->prepare('SELECT username FROM users WHERE id = :userID');
    $tst->execute(['userID'=>$row['id']]);
    $myres = $tst->fetch();

    echo $myres['username'].":".$row['body'] . ',';
}

}

?>