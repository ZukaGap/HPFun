<?php
session_start();
    // თუ იუზერი შესული გადაამისამართოს ფორუმ გვერძე
    if (isset($_SESSION['loggedin'])) {
        header('Location: ../forum.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./login_signup.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../../nav.css" />
    <title>Forum HP</title>
    <link rel = "icon" href ="../../Home/hp.ico" type = "image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" />
</head>
<body>
    <header>
        <div class="nav" >
        <ul>
            <li class="home"><a href="../../Home/home.html">Home</a></li>
            <li class="films"><a href="../../Films/film.html">Films</a></li>
            <li class="tutorials"><a href="../../Object/object.html">Objects</a></li>
            <li class="forum"><a class="active" href="#" >Forum</a></li>
            <li class="contact"><a href="../../contact/contact.html">Contact</a></li>
        </ul>
        </div>
    </header> 
    <div class="login">
        <h1>Login</h1>
        <?php
            if(isset($_GET['password'])) {
                echo "<p>Incorect Password</p>";
            }
            else if(isset($_GET['username'])) {
                echo "<p>Incorect Username</p>";
            }
        ?>
        <form action="authentication.php" method="post">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Username" id="username" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <a href="../register/register.html" id="reg" >Registration</a>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>