<?php 

session_start();
// თუ იუზერი არაა შესული გადაამისამართოს შესვლის გვერძე
if (!isset($_SESSION['loggedin'])) {
	header('Location: ./login/login.php');
	exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'users';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if( strlen($_POST['text']) != 0)
    if ($stmt = $con->prepare('INSERT INTO comments (userID, userName, body) VALUES (?, ?, ?)')) {
        $stmt->bind_param('iss', $_SESSION['id'], $_SESSION['name'], $_POST['text']);
        $stmt->execute();
        header('Location: ./forum.php');
    } else {
        echo 'Could not prepare statement!';
    }
else
    header('Location: ./forum.php');

$con->close();
?>