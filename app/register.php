<?php
require_once "functions.php";
redirectAlreadyConnected();
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);

        $link = connectDB();

        $query = $link->prepare("SELECT id FROM users WHERE email = :email");
        $query->execute(['email' => $email]);
        if ($query->rowCount() == 0) {        

            $query = $link->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            $query->execute(['email' => $email, 'password' => $hash_pass]);

            if ($query->rowCount() == 1) {
                header('Location: login.php');
            } 
        } else {
            $error = 'This username or email address is alreday used';
        }
    } else {
        $error = 'Please fill in all the fields';
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css"> 
    <title>Join To-Do List</title>
</head>
<body>
    <div class="form-card">
        <div class="card-title">
            <h2>Welcome to To-Do List!</h2>
        </div>
        <?php if ($error != '') {?>
        <div class="err-box">
            <span><?php echo $error; ?></span>
        </div>
        <?php } ?>
        <form>
            <div class="field">
                <label for="email">Username or email address</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="btn-holder">
                <button id="login-btn" class="sq-btn btn-lavande" type="submit" formmethod="POST">Sign up</button>
            </div>
        </form>
        <hr>
        <div>
            <p>Already have an account? <a href="/login.php">Sign in</a>.</p>
        </div>
    </div>
</body>
</html>