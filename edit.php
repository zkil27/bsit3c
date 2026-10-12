<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "db_user";

$connection = new mysqli($server, $username, $password, $database);
$id = $_GET['id'];
$sqlselect = "SELECT * FROM tb_users WHERE id=$id";
$results = $connection->query($sqlselect)->fetch_assoc();

if (isset($_POST['update'])) {
    $fname = $_POST['first_name'];
    $sqlupdate = "UPDATE tb_users SET first_name='$fname' WHERE id=$id";
    $connection->query($sqlupdate);

    header('location: index.php');
}

if (isset($_POST['update'])) {
    $fname = $_POST['middle_name'];
    $sqlupdate = "UPDATE tb_users SET middle_name='$fname' WHERE id=$id";
    $connection->query($sqlupdate);

    header('location: index.php');
}

if (isset($_POST['update'])) {
    $fname = $_POST['last_name'];
    $sqlupdate = "UPDATE tb_users SET last_name='$fname' WHERE id=$id";
    $connection->query($sqlupdate);

    header('location: index.php');
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        :root {
            font-family: "Segoe UI", Arial, sans-serif;
            color: #4a4658;
            background: #fff7fb;
            --ease-smooth: cubic-bezier(.22, 1, .36, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 28px 20px;
            background:
                radial-gradient(circle at 8% 8%, #fbddec 0, transparent 28%),
                radial-gradient(circle at 92% 92%, #dff4f1 0, transparent 30%),
                #fff7fb;
        }

        .card {
            width: min(100%, 720px);
            padding: clamp(24px, 5vw, 40px);
            border: 1px solid rgba(218, 190, 207, .6);
            border-radius: 22px;
            background: rgba(255, 255, 255, .86);
            box-shadow: 0 18px 45px rgba(105, 73, 99, .1);
            backdrop-filter: blur(8px);
            animation: card-in .65s var(--ease-smooth) both;
        }

        .eyebrow {
            margin: 0 0 10px;
            color: #bd6c9b;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            color: #373344;
            font-size: clamp(28px, 5vw, 40px);
            letter-spacing: -.03em;
        }

        .subtitle {
            margin: 10px 0 28px;
            color: #8a8496;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #665f72;
            font-size: 13px;
            font-weight: 700;
        }

        input[type="text"] {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #ead8e3;
            border-radius: 12px;
            background: #fffafd;
            color: #4a4658;
            font: inherit;
            outline: none;
            transition:
                border-color .3s var(--ease-smooth),
                box-shadow .3s var(--ease-smooth),
                transform .3s var(--ease-smooth),
                background-color .3s var(--ease-smooth);
        }

        input[type="text"]:focus {
            border-color: #d69abb;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(214, 154, 187, .18);
            transform: translate3d(0, -1px, 0);
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
        }

        .cancel,
        input[type="submit"] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 12px 20px;
            border-radius: 12px;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition:
                background-color .3s var(--ease-smooth),
                box-shadow .3s var(--ease-smooth),
                transform .3s var(--ease-smooth);
        }

        .cancel {
            color: #8a8496;
        }

        .cancel:hover {
            background: #fff2f8;
            color: #665f72;
            transform: translate3d(0, -2px, 0);
        }

        input[type="submit"] {
            border: 0;
            background: #d98eb5;
            color: #fff;
            box-shadow: 0 8px 16px rgba(191, 108, 155, .2);
        }

        input[type="submit"]:hover {
            background: #c97ba5;
            box-shadow: 0 11px 20px rgba(191, 108, 155, .28);
            transform: translate3d(0, -2px, 0);
        }

        input[type="submit"]:active,
        .cancel:active {
            transform: translate3d(0, 1px, 0);
            transition-duration: .12s;
        }

        input[type="submit"]:focus-visible,
        .cancel:focus-visible {
            outline: 3px solid rgba(214, 154, 187, .45);
            outline-offset: 3px;
        }

        @keyframes card-in {
            from {
                opacity: 0;
                transform: translate3d(0, 12px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @media (max-width: 650px) {
            .fields {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column-reverse;
            }

            .cancel,
            input[type="submit"] {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>
    <main class="card">
        <p class="eyebrow">User directory</p>
        <h1>Edit user</h1>
        <p class="subtitle">Update the user's name details below.</p>

        <form action="" method="post">
            <div class="fields">
                <div>
                    <label for="fname">First name</label>
                    <input type="text" name="first_name" id="fname" value="<?= $results['firstName'] ?>">
                </div>
                <div>
                    <label for="mname">Middle name</label>
                    <input type="text" name="middle_name" id="mname" value="<?= $results['middleName'] ?>">
                </div>
                <div>
                    <label for="lname">Last name</label>
                    <input type="text" name="last_name" id="lname" value="<?= $results['lastName'] ?>">
                </div>
            </div>

            <div class="actions">
                <a class="cancel" href="index.php">Cancel</a>
                <input type="submit" value="Update user" name="update">
            </div>
        </form>
    </main>
    
</body>
</html>
