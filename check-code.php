<?php
    if (isset($_GET['code'])) {
        $email = $_GET['email'];
        $code = (int) $_GET['code'];
        $modeIndex = (int) $_GET['modeIndex'];

        $connection = new mysqli('127.0.0.1', 'root', 'root', 'users-db');
        $users = $connection -> query(
            "SELECT * FROM `temp-users` WHERE `email` = '$email' AND `code` = '$code'"
        );

        if (( $user = $users -> fetch_assoc() )) {
            $id = $user['id'];
            $username = $user['username'];
            $hashedPassword = $user['password'];

            $connection -> query(
                "DELETE FROM `temp-users` WHERE `id` = '$id'"
            );

            if ($modeIndex == 0) {
                $symbols = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
                $user = true;

                while ($user) {
                    $secretId = '';
                    for ($i = 0; $i < 22; $i++) {
                        $secretId .= $symbols[rand(0, 61)];
                    }

                    $users = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$secretId'");
                    $user = $users -> fetch_assoc();
                }

                $connection -> query(
                    "INSERT INTO `users` (`id`, `secret-id`, `username`, `email`, `password`) VALUES (
                        '$id', '$secretId', '$username', '$email', '$hashedPassword'
                    )"
                );
            }

            else if ($modeIndex == 1) {
                $users = $connection -> query(
                    "SELECT * FROM `users` WHERE `id` = '$id'"
                );

                $user = $users -> fetch_assoc();
                $secretId = $user['secret-id'];
            }

            setcookie('id', $secretId, time() + 3600, '/');
            ?>

            <form method='post' style='display: none;'>
                <input name='type' value="User">
                <input name="email" value="<?= $email ?>">
                <input name='alert'>
                <input name='button-text' value='Continue'>
                <input name='url' value='/?id=<?= $id ?>'>
                <button type='submit'></button>
            </form>

            <script>
                const form = document.querySelector('form')
                const alert = form.querySelector('input[name="alert"]')
                const modeIndex = <?= $modeIndex ?>

                if (modeIndex == 0) {
                    form.action = '/sign-up.php'
                    alert.value = 'Congratulations! You signed up your account.'
                }

                else {
                    form.action = '/sign-in.php'
                    alert.value = 'Congratulations! You signed in to your account.'
                }

                const button = form.querySelector('button')
                button.click()
            </script>

            <?php
        }

        else {
            $users = $connection -> query(
                "SELECT * FROM `temp-users` WHERE `email` = '$email'"
            );

            $user = $users -> fetch_assoc();
            $attempts = $user['attempts'];

            if ($attempts == 1) {
                $connection -> query(
                    "DELETE FROM `temp-users` WHERE `email` = '$email'"
                )

                ?>

                <form method='post' style='display: none;'>
                    <input name='type' value="User">
                    <input name="email" value="<?= $email ?>">
                    <input name='alert' value="You have spent the attempts of entering verification codes. You can only re-login to your account.">
                    <input name='button-text' value='Re-login'>
                    <input name='url' value='/login.php'>
                    <button type='submit'></button>
                </form>

                <script>
                    const form = document.querySelector('form')
                    const modeIndex = <?= $modeIndex ?>
                
                    if (modeIndex == 0) {form.action = '/sign-up.php'}
                    else {form.action = '/sign-in.php'}
                    
                    const button = form.querySelector('button')
                    button.click()
                </script>

                <?php
            }

            else {
                $attempts--;
                $connection -> query(
                    "UPDATE `temp-users` SET `attempts` = '$attempts' WHERE `email` = '$email'"
                );
            }

            ?>

            <form class='code-error' method='post' style='display: none;'>
                <input name="code-error">
                <input name="email" value="<?= $email ?>">
                <input name="modeIndex" value="<?= $modeIndex ?>">
                <button type='submit'></button>
            </form>
            
            <script>
                const form = document.querySelector('form')
                const modeIndex = <?= $modeIndex ?>
            
                if (modeIndex == 0) {form.action = '/sign-up.php'}
                else {form.action = '/sign-in.php'}

                const button = document.querySelector('form.code-error button')
                button.click()
            </script>

            <?php
        }
    }
?>

<title>Regastration</title>