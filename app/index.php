<?php
require_once "functions.php";
redirectUnauthenticated();
$errors = [];
if(isset($_GET['action'])) {
    $link = connectDB();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_GET['action'] == 'add') {
            $label = $_POST['label'];
            $description = $_POST['description'];
            $due_at = $_POST['due_at'] ?: null; 
                    
            if (empty($label)) {
                $errors[] = 'Tag is mandatory';
            }
            if (empty($description)) {
                $errors[] = 'Desc is mandatory';
            }
            if ($due_at !== null && strtotime($due_at) === false) {
                $errors[] = 'Invalid deadline';
            }
        
            if (empty($errors)) {  
                $userid = $_SESSION['userid'];
                $xss_label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
                $xss_desc = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        
                $query = $link->prepare("INSERT INTO tasks (owner_id, label, description, due_at) VALUES (:userid, :label, :description, :due_at)");
                $query->execute(['userid' => $userid, 'label' => $xss_label, 'description' => $xss_desc, 'due_at' => $due_at]);
            }
        }
    } elseif($_GET['action'] == 'del') {
        if(isset($_GET['tid'])) {
            $tid = $_GET['tid'];

            $query = $link->prepare("SELECT * FROM tasks WHERE owner_id = :userid AND id = :tid");
            $query->execute(['userid' => $_SESSION['userid'], 'tid' => $tid]);

            if ($query->rowCount() == 1) {
                $query = $link->prepare("DELETE FROM tasks WHERE id = :tid ");
                $query->execute(['tid' => $tid]);
            } else {
                $errors[] = 'This task doesn\'t exists';
            }
        } else {
            $errors[] = 'Request error';
        }
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css"> 
    <title>My tasks</title>
</head>
<body>
    <div class="form-card">
        <div>
            <h1>To-Do List</h1>
            <p>Welcome <?php echo $_SESSION['email']; ?>! <a href="logout.php">Sign out</a>.</p>
            <?php 
            if (!empty($errors)) {
                foreach ($errors as $error):
            ?>
                <div class="err-box">
                    <span><?php echo $error; ?></span>
                </div>
            <?php 
                endforeach; 
            } 
            ?>
            <h2>My tasks</h2>
            <table>
                <thead>
                <tr>
                    <th>Tag</th>
                    <th>Desc</th>
                    <th>Due</th>
                    <th>Del</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                $link = connectDB();

                $query = $link->prepare("SELECT * FROM tasks WHERE owner_id = :userid");
                $query->execute(['userid' => $_SESSION['userid']]);

                $tasks = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tasks as $task): 
                ?>
                    <tr>
                    <td><?php echo $task['label']; ?></td>
                    <td><?php echo $task['description']; ?></td>
                    <td><?php echo ($task['due_at'] ? date('d/m/Y H:i', strtotime($task['due_at'])) : ''); ?></td>
                    <td><a href="index.php?action=del&tid=<?php echo $task['id'];?>"><i data-feather="trash-2"></i></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <h2><a href='javascript:void(0);' id="collapser">Add a task <i id="chevron-right" data-feather="chevron-right"></i><i id="chevron-down" data-feather="chevron-down"></i></a></h2>
            <div id="coll-content">
                <form action="index.php?action=add" method="post">
                    <label for="label">Tag</label>
                    <input type="text" id="label" name="label">
                    <label for="description">Desc</label>
                    <textarea id="description" name="description"></textarea>
                    <label for="due_at">Due</label>
                    <input type="datetime-local" id="due_at" name="due_at">
                    <input type="submit" value="Add">
                </form>
            </div>
        </div>
    </div>
</body>
<script src="/js/feather.min.js"></script>
<script src="/js/main.js"></script>
</html>