<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Database.js</title>
    <link rel="stylesheet" href="assets/theme.css">
    <link rel="stylesheet" href="assets/jsonview.css">
    <script src="assets/jquery.js"></script>
    <script src="assets/phplogo.js"></script>
    <script src="assets/jsonview.js"></script>
    <script src="assets/script.js"></script>
</head>

<?php
// Authorize user
function login_form() {
    ?>
<body>
    <div id="loginform">
        <form action="" method="POST">
            <table cellspacing="0">
                <tr>
                    <th>Username</th>
                    <td><input name="ADMIN_USERNAME" type="text" value="" placeholder="username"></td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td><input name="ADMIN_PASSWORD" type="password" value="" placeholder="password"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php if (isset($GLOBALS['message']) && !isset($_SESSION['ADMIN'])) {
                            ?> <button class="btn red" ondblclick="this.remove()"><?= $GLOBALS['message'] ?></button> <?php
                        } ?>
                        <?php if (isset($_SESSION['ADMIN'])) { ?>
                            <button class="btn red" type="submit">Save</button>
                            <input type="hidden" name="LOGOUT" value="true">
                        <?php } else { ?>
                            <button class="btn green" type="submit">Login</button>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
    <?php
}
function authorize_user() {
    require_once 'admin.php';
    session_start();
    if ($_SESSION['ADMIN'] == ADMIN_USERNAME) {
        if (isset($_POST['LOGOUT'])) {
            if (isset($_POST['ADMIN_USERNAME']) && trim($_POST['ADMIN_USERNAME']) != '' && isset($_POST['ADMIN_PASSWORD']) && trim($_POST['ADMIN_PASSWORD']) != '') {
                $admin_username = $_POST['ADMIN_USERNAME'];
                $admin_password = password_hash($_POST['ADMIN_PASSWORD'], PASSWORD_DEFAULT);
                $admin = "<?php
define('ADMIN_USERNAME', '$admin_username');
define('ADMIN_PASSWORD', '$admin_password');";
                file_put_contents('admin.php', $admin);
                $GLOBALS['message'] = 'New authorization saved!';
            } else if (isset($_POST['ADMIN_USERNAME']) || isset($_POST['ADMIN_PASSWORD'])) {
                $GLOBALS['message'] = 'Username or Password must not be null';
            } else {
                $GLOBALS['message'] = ADMIN_USERNAME.' logged out successfully!';
            }
            session_unset();
        }
    }
    if ($_SESSION['ADMIN'] != ADMIN_USERNAME) {
        if (isset($_POST['ADMIN_USERNAME']) && isset($_POST['ADMIN_PASSWORD']) && !isset($_POST['LOGOUT'])) {
            if ($_POST['ADMIN_USERNAME'] == ADMIN_USERNAME && password_verify($_POST['ADMIN_PASSWORD'], ADMIN_PASSWORD)) {
                $_SESSION['ADMIN'] = ADMIN_USERNAME;
                $GLOBALS['message'] = 'Welcome '.ADMIN_USERNAME.'!';
                return;
            } else {
                $GLOBALS['message'] = 'Incorrect username or password!';
            }
        }
        login_form();
        die();
    }
}
authorize_user();
?>

<?php
// User logged in
function raw_phpinfo() {
    ob_start();
    phpinfo();
    $s = ob_get_contents();
    ob_end_clean();
    return preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $s);
}
function save_databaseinfo() {
    $host = $_POST['HOST'];
    $port = $_POST['PORT'];
    $database = $_POST['DATABASE'];
    $username = $_POST['USERNAME'];
    $password = $_POST['PASSWORD'];
    $config = "<?php
define('HOST', '$host');
define('PORT', '$port');
define('DATABASE', '$database');
define('USERNAME', '$username');
define('PASSWORD', '$password');";
    file_put_contents('config.php', $config);
    $GLOBALS['message'] = 'Database info was saved to config.php successfully!';
}
if (isset($_POST['HOST']) && isset($_POST['PORT']) && isset($_POST['DATABASE']) && isset($_POST['USERNAME']) && isset($_POST['PASSWORD'])) {
    save_databaseinfo();
}
require_once 'config.php';
?>
<body>
    <div id="navigation">
        <button class="btn blue" href="#databaseinfo">Database info</button>
        <button class="btn blue" href="#phpinfo">PHP info</button>
        <button class="btn blue" href="#test">Test</button>
        <button class="btn blue" href="#authorization">Change authorization</button>
        <form action="" method="POST">
            <button class="btn blue" type="submit" href="#logout">Logout</button>
            <input type="hidden" name="LOGOUT" value="true">
        </form>
    </div>
    <div id="pages">
        <?php if (isset($GLOBALS['message'])) {
            ?> <button class="btn green" ondblclick="this.remove()"><?= $GLOBALS['message'] ?></button> <?php
        } ?>
        <div class="page" id="databaseinfo">
            <form action="" method="POST">
                <table cellspacing="0">
                    <tr>
                        <th>Host</th>
                        <td><input name="HOST" type="text" value="<?= HOST ?>"></td>
                    </tr>
                    <tr>
                        <th>Port</th>
                        <td><input name="PORT" type="text" value="<?= PORT ?>"></td>
                    </tr>
                    <tr>
                        <th>Database</th>
                        <td><input name="DATABASE" type="text" value="<?= DATABASE ?>"></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><input name="USERNAME" type="text" value="<?= USERNAME ?>"></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><input name="PASSWORD" type="text" value="<?= PASSWORD ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button class="btn red" type="submit">Save</button></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="page" id="phpinfo">
            <?= raw_phpinfo() ?>
        </div>
        <div class="page" id="test">
            <input id="query" type="text" placeholder="some SQL queries here...">
            <div id="result"></div>
        </div>
        <div class="page" id="authorization">
            <?php login_form(); ?>
        </div>
    </div>
</body>
</html>