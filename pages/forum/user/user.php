<?php
    session_start();
    // თუ იუზერი არაა შესული გადაამისამართოს შესვლის გვერძე
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../login/login.php');
        exit;
    }
    // გამოსვლა
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
    $conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $id = $_SESSION['id'];
    $sql = "SELECT username,email,activation_code,status FROM user WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows ) {
        // output data of each row
        $row = $result->fetch_assoc();
    } else {
        echo "0 results";
    }
    $conn->close();


// კლიენტის მიერ თავისი აქაუნთის წაშლა. 
    if(isset($_POST['delete'])) {        
        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        
        $id = $_SESSION['id'];

        $sql ="DELETE FROM user  WHERE id=$id";
        $result = mysqli_query($con,$sql);
        if($result) {
            session_start();
            session_destroy();
            // გადაამისამართოს შესვლის გვერძე
            header('Location: ../login/login.php');
        }
        else {
            echo "can not delete";
        }
        
    }

    function validationNewName($newName) {
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'root';
        $DATABASE_PASS = '';
        $DATABASE_NAME = 'users';
        $connetct = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        if ($connetct->connect_error) die("Connection failed: " . $connetct->connect_error);

        $sqlS = "SELECT * FROM user WHERE username LIKE '$newName'";
        $result = $connetct->query($sqlS);
        if ($result->num_rows > 0) return TRUE;
        else return FALSE;
    }

    // კლიენტის მიერ აქაუნთზე სახელის შეცვლა
    if(isset($_POST['edit'])) {
        $conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
        $id = $_SESSION['id'];
        $sql = "SELECT username, email, status, activation_code FROM user WHERE id=$id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $row = $result->fetch_assoc();

            validationNewName($username);
        
            if($username !== $row["username"] && !validationNewName($username)){
                $username = $_POST['username'];
                $sql = "UPDATE user SET username='$username' WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    echo "Record updated successfully";
                    header('Location: ../user/user.php');
                } else {
                    echo "Error updating record: " . $conn->error;
                }

                $conn->close();
            }   else header('Location: ../user/user.php?sameuser=true');
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
        <link rel="stylesheet" href="../login/login_signup.css?v=<?php echo time(); ?>" />
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
		<div class="register"> 
            <?php 
                if(isset($_GET['sameuser'])){
                    echo "<h5>This User Already Exists</h5>";
                }
            ?>
            <form action="" method="post" >	
                <label for="username">
					<i class="fas fa-user"></i>
                </label>
                <?php
                    echo '<input type="text" name="username" value="' . $row['username'] . '" id="username" />';
                ?>
				<label >
					<i class="fas fa-envelope"></i>
                </label>
                <?php
                    echo '<input type="email" name="email" value="' . $row['email'] . '" id="email" />';
                ?>
                <label for="verification">
                    <?php 
                        echo $row["activation_code"] == 'Activated' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
                    ?>
                </label>
                <?php
                    $st = $_SESSION['status'] == 'Admin' ? ' ' : 'disabled';
                    $active = $row["activation_code"] == 'Activated' ? 'Activated' : 'Not Activated';
                    echo '<input type="text" id="verification" name="verification" value="' . $active . '"' . $st . ' />';
                ?>
                <label >
                    <?php 
                       echo $row["activation_code"] == 'client' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-crown"></i>';
                    ?>    
                </label>
                <?php
                    $active = $row["status"] == 'client' ? 'Client' : $row["status"] == 'Admin' ? 'Admin' : 'Without Status';
                    echo '<input type="text" id="status" name="status" value="' . $active . '"' . $st . '/>';
                ?>
                <input type="submit" name="edit" value="Edit">
                <input type="submit" name="logout" value="Log Out">
                <input type="submit" name="delete" value="Delete Account">
            </form>
        </div>
	</body>
</html>