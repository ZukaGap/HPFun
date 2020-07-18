<?php
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'users';


	if(isset($_GET['delete'])) {
        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        $id = $_GET['delete'];

        $sqlC ="DELETE FROM comments  WHERE id=$id";
        $resultC = mysqli_query($con,$sqlC);

        if($resultC) {
            header('Location: ./forum.php');
        }
        else {
            echo "can not delete";
        }
       
	}
?>