<?php
    session_start();
    // თუ იუზერი არაა შესული გადაამისამართოს შესვლის გვერძე
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../login/login.php');
        exit;
    }
        
    if(isset($_POST['logout'])) {
        session_start();
        session_destroy();
        // გადაამისამართოს შესვლის გვერძე
        header('Location: ../login/login.php');
    }

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'users';

    // Create connection

	if(isset($_GET['delete'])) {
        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        $id = $_GET['delete'];

        $sqlU ="DELETE FROM user  WHERE id=$id";
        $sqlA ="DELETE FROM admin  WHERE id=$id";
        $resultU = mysqli_query($con,$sqlU);
        $resultA = mysqli_query($con,$sqlA);

        if($resultU && $resultA) {
            if($id == $_SESSION['id']) {
                session_start();
                session_destroy();
                header('Location: ../login/login.php');
            }
            header('Location: ../adminpg/admin.php');
        }
        else {
            echo "can not delete";
        }
       
	}

    if(isset($_GET['give'])) {
        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        if (mysqli_connect_errno()) exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        $id = $_SESSION['id'];
        $sql = "SELECT status FROM admin WHERE id = $id";
        $result = $con->query($sql);
       
        if ($result->num_rows > 0) {
            $checkst = $result->fetch_assoc()['status'];
            //თუ ადმინების ადმინია
            if($checkst == 'adminP') {  
                $id = $_GET['give'];
                $sql = "SELECT status FROM admin WHERE id = $id";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {                    
                    $changest = $result->fetch_assoc()['status'];
                    if($changest == 'client') {
                        $changerID = $_SESSION['id'];
                        $sqlA = "UPDATE admin SET status='admin', changerID=$changerID WHERE id=$id";
                        $sqlU = "UPDATE user SET status='admin' WHERE id=$id";
                    }  
                    if($changest == 'admin') {
                        $changerID = $_SESSION['id'];
                        $sqlA = "UPDATE admin SET status='client', changerID=$changerID WHERE id=$id";
                        $sqlU = "UPDATE user SET status='client' WHERE id=$id";
                    }

                    if (($con->query($sqlA) === TRUE) && ($con->query($sqlU) === TRUE )) {
                        echo "Record updated successfully";
                    } else {
                    echo "Error updating record: " . $con->error;
                    }        
                } else {
                    // ასეთი იუზერ აიდი არმოიძებნა ადმინ ბაზაში
                    // აქ ვამატებთ ადმინ ბაზაში იუზერაიდს და თან სტატუს ვუცვლით
                    $id = $_GET['give'];
                    $sql = "SELECT email,status FROM user WHERE id = $id";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {                    
                        $changest = $result->fetch_assoc();

                        $changerID = $_SESSION['id'];
                        $userID = $_GET['give'];
                        $userEmail = $changest['email'];
                        
                        if($changest['status'] == 'client') {
                            $sta = 'admin';
                            $sqlA = "INSERT INTO admin(id,status,email,changerID) VALUES('$userID', '$sta', '$userEmail', '$changerID')";
                            $sqlU = "UPDATE user SET status='admin' WHERE id=$id";
                        }  

                        if($changest['status'] == 'admin') {
                            $sta = 'client';
                            $sqlA = "INSERT INTO admin(id,status,email,changerID) VALUES('$userID', '$sta', '$userEmail', '$changerID')";
                            $sqlU = "UPDATE user SET status='client' WHERE id=$id";
                        }    

                        if($con->query($sqlA) === FALSE) {
                            echo "Error updating record A: " . $con->error;
                        }
                        if($con->query($sqlU) === FALSE) {
                            echo "Error updating record U: " . $con->error;
                        }
                    }                
            }
        } else {
            echo "This account can't change Users Status!";
        }

        $con->close();
        header('Location:../adminpg/admin.php');
    }
}
    

?>

<!-- HTML 5 -->

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>User Page</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="./user.css?v=<?php echo time(); ?>" />
		<link rel="stylesheet" href="../../nav.css" />
        <link rel="stylesheet" href="./admin.css?v=<?php echo time(); ?>" />
        <link rel = "icon" href ="../../Home/hp.ico" type = "image/x-icon">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
	</head>
	<body class="loggedin">
		<header>
            <div class="nav" >
            <ul>
                <li class="home"><a href="../../Home/home.html">Home</a></li>
                <li class="films"><a href="../../Films/film.html">Films</a></li>
                <li class="tutorials"><a href="../../Object/object.html">Objects</a></li>
                <li class="forum"><a href="../forum.php" >Forum</a></li>
				<li class="contact"><a href="../../contact/contact.html">Contact</a></li>
				<li class="user"><a class="active"><i class="fas fa-user"></i></a></li>
            </ul>
            </div>
        </header> 
		<div class="logout">
            <form action="" method="post" >	
                <input type="submit" name="logout" value="Log Out">
            </form>
        </div>
        <div class="users">
            <table class="hoverTable" >
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Activate</th>
                        <th>Status</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php        
                        $conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
                        // Check connection
                        if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                        }
                        
                        $id = $_SESSION['id'];
                        $sql = "SELECT id,username,email,activation_code,status FROM user";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<tr><th>' . $row['id'] . '</th>';
                                echo '<th>' . $row['username'] . '</th>';
                                echo '<th>' . $row['email'] . '</th>';
                                echo '<th>' . $row['activation_code'] . '</th>';
                                echo '<th><a class="status" href="../adminpg/admin.php?give=' . $row['id'] . '">' . $row['status'] . '</a></th>';
                                echo '<th><a class="delete" href="../adminpg/admin.php?delete=' . $row['id'] . '">' . 'Delete' . '</a></th></tr>';
                            }
                          }
                        $conn->close();  
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>