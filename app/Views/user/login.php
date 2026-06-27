<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('/style.css');?>">
</head>
<body>
    <div id="login-wrapper" style="width: 300px; margin: 100px auto;">
        <h1>Sign In</h1>
        <?php if(session()->getFlashdata('flash_msg')):?>
            <div style="color: red; margin-bottom: 10px;"><?= session()->getFlashdata('flash_msg') ?></div>
        <?php endif;?>
        <form action="" method="post">
            <label>Email address</label>
            <input type="email" name="email" value="<?= set_value('email') ?>" required style="width: 100%; margin-bottom: 10px;">
            <label>Password</label>
            <input type="password" name="password" required style="width: 100%; margin-bottom: 10px;">
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>