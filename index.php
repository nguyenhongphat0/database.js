<?php
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Database.js</title>
    <link rel="stylesheet" href="assets/theme.css">
    <link rel="stylesheet" href="assets/jsonview.css">
</head>
<body>
    <div id="navigation">
        <button class="btn blue" href="#databaseinfo">Database info</button>
        <button class="btn blue" href="#phpinfo">PHP info</button>
        <button class="btn blue" href="#test">Test</button>
        <button class="btn blue" href="#phpinfo">Admin profile</button>
        <button class="btn blue" href="#phpinfo">Logout</button>

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
    </div>
    <script src="assets/jquery.js"></script>
    <script src="assets/phplogo.js"></script>
    <script src="assets/jsonview.js"></script>
    <script>
        $('.btn').prepend('<div class="hover"><span></span><span></span><span></span><span></span><span></span></div>');
        function changeTab(e) {
            const href = e.target.attributes.href.value;
            $('.page').addClass('hidden');
            $(href).removeClass('hidden');
        }
        $('#navigation button').click(e => changeTab(e));
        $('#navigation button').first().click();
        $('#test #query').keyup((event) => {
            if (event.keyCode == 13) {
                query = event.target.value;
                url = `database.php?sql=${query}`;
                fetch(url)
                .then(res => res.text())
                .then(data => {
                    target = '#test #result';
                    $(target).append($('<p class="queried">').text(query));
                    jsonView.format(data, target);
                });
            }
        });
    </script>
</body>
</html>