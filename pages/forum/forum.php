<?php
session_start();
// თუ იუზერი არაა შესული გადაამისამართოს შესვლის გვერძე
if (!isset($_SESSION['loggedin'])) {
	header('Location: ./login/login.php');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Forum</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="../nav.css" />
		<link rel="stylesheet" href="./forum.css?v=<?php echo time(); ?>" />
        <link rel = "icon" href ="../Home/hp.ico" type = "image/x-icon">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
	</head>
	<body class="loggedin">
		<header>
            <div class="nav" >
            <ul>
                <li class="home"><a href="../Home/home.html">Home</a></li>
                <li class="films"><a href="../Films/film.html">Films</a></li>
                <li class="tutorials"><a href="../Object/object.html">Objects</a></li>
                <li class="forum"><a class="active" href="#" >Forum</a></li>
				<li class="contact"><a href="../contact/contact.html">Contact</a></li>
				<?PHP 
					$url = $_SESSION['status'] == 'admin' || $_SESSION['status'] == 'adminP' ? './adminpg/admin.php' : './user/user.php';
					echo '<li class="user"><a href="' . $url . '" ><i class="fas fa-user"></i></a></li>';
				?>
            </ul>
            </div>
        </header> 
		<div class="addComment" >
			<form action="comment.php" method="post" autocomplete="off">
				<input type="text" name="text" id="txtinput" placeholder="Comment ..." />
				<input type="submit" id="send" value="SEND" />
			</form>
		</div>
		<div class="content">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname = "users";

				$conn = new mysqli($servername, $username, $password, $dbname);
				if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

				$sql = "SELECT id, userName, body FROM comments";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						echo '<div class="comment" ><p><span class="user">' . $row['userName'] . ':</span>' . $row['body'] . '</p>';
						echo '<div class="sec-center"> ';
						if($row['userName'] == $_SESSION['name'] ){
							echo '<label class="for-dropdown" for="dropdown">
									<a href="./commentDelete.php?delete=' . $row['id'];
							echo '"><i class="fas fa-trash"></i></a></label>';
						} else if(($_SESSION['status'] == 'admin') || ($_SESSION['status'] == 'adminP') ) {
							echo '<label class="for-dropdown" for="dropdown">
								<a href="#"><i class="fas fa-trash"></i></a>
								</label>';
						}
						echo '</div></div>';
					}
				}
				$conn->close();
				?>
		</div>
	</body>
</html>