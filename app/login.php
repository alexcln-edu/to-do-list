<?php
require_once "functions.php";
redirectAlreadyConnected();
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $link = connectDB();

        $query = $link->prepare("SELECT * FROM users WHERE email = :email");
        $query->execute(['email' => $email]);

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount() == 1) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['userid'] = $user['id'];
                $_SESSION['email'] = $email;
                header('Location: index.php');
            } else {
                $error = 'Wrong password';
            }            
        } else {
            $error = 'This account doesn\'t exits';
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
    <title>Sign in to To-Do List</title>
</head>
<body>
    <div class="form-card">
        <div class="card-title">
            <h2>Sign in to To-Do List</h2>
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
                <button id="login-btn" class="sq-btn btn-lavande" type="submit" formmethod="POST">Sign in</button>
            </div>
        </form>
        <hr>
        <div>
            <p>New to To-Do List? <a href="/register.php"> Create an account</a>.</p>
        </div>
    </div>
</body>
</html>