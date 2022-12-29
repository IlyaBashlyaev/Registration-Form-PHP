<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link href="https://fonts.googleapis.com/css2?family=Inconsolata&display=swap" rel="stylesheet">
        <title>Accounts</title>

        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: 'Inconsolata', Arial;
            }

            a {
                text-decoration: none;
            }
        </style>
    </head>

    <body>
        <?php
            error_reporting(E_ERROR | E_PARSE);
            $connection = new mysqli('127.0.0.1', 'root', 'root', 'users-db');

            if (isset($_GET['id'])):
                $id = $_GET['id'];
                $users = $connection -> query("SELECT * FROM `users` WHERE `id` = '$id'");
                $user = $users -> fetch_assoc();

                if ($user):
                    ?>

                    <p><b>Username:&nbsp;<?= $user['username'] ?></b></p>
                    <p>Email:&nbsp;&nbsp;&nbsp;&nbsp;<?= $user['email'] ?></p>
                    <p>Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $id ?></p><br>

                    <?php
                        if (isset( $_COOKIE['id'] )) {
                            if ($_COOKIE['id'] == $user['secret-id']) {
                                echo "It's your account<br><br>";
                            }
                        }
                    ?>

                    <?php
                endif;
            ?>

        <?php
            else:
                $users = $connection -> query('SELECT * FROM `users` ORDER BY `user-id` DESC');

                while (( $user = $users -> fetch_assoc() )):
                    ?>

                    <p><b>Username:&nbsp;<?= $user['username'] ?></b></p>
                    <p>Email:&nbsp;&nbsp;&nbsp;&nbsp;<?= $user['email'] ?></p>
                    <p>Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='/?id=<?= $user['id'] ?>'><?= $user['id'] ?></a></p><br>
                    
                    <?php
                endwhile;
            endif;
        ?>

        <a href="/">Main Page</a> | <a href="/login.php">Login</a>
    </body>
</html>