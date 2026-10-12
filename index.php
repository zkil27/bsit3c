<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "db_user";

$connection = new mysqli($server, $username, $password, $database);
$newUserId = 0;
$userAdded = false;

if ($connection->connect_error){
    die("connection failed".$connection->connect_error);
}

if (isset($_POST['insert'])) {
    $fname = $_POST['first_name'];
    $mname = $_POST['middle_name'];
    $lname = $_POST['last_name'];

    $statement = $connection->prepare(
        "INSERT INTO tb_users (firstName, middleName, lastName) VALUES (?, ?, ?)"
    );
    $statement->bind_param("sss", $fname, $mname, $lname);
    $statement->execute();
    $newUserId = (int) $statement->insert_id;
    $userAdded = $statement->affected_rows === 1;
    $statement->close();
}

$sqlselect = "SELECT * FROM tb_users ORDER BY ID DESC";
$results = $connection->query($sqlselect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Directory</title>
    <style>
        :root {
            font-family: "Segoe UI", Arial, sans-serif;
            color: #4a4658;
            background: #fff7fb;
            --ease-smooth: cubic-bezier(.22, 1, .36, 1);
            --ease-press: cubic-bezier(.32, 0, .67, 0);
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
            background:
                radial-gradient(circle at 8% 8%, #fbddec 0, transparent 28%),
                radial-gradient(circle at 92% 92%, #dff4f1 0, transparent 30%),
                #fff7fb;
        }

        .page {
            width: min(100%, 1040px);
            margin: 0 auto;
            animation: fade-up .65s var(--ease-smooth) both;
        }

        .heading {
            margin: 0 auto 26px;
            text-align: center;
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
            font-size: clamp(28px, 5vw, 42px);
            letter-spacing: -.03em;
        }

        .subtitle {
            margin: 10px 0 0;
            color: #8a8496;
        }

        .card {
            border: 1px solid rgba(218, 190, 207, .6);
            border-radius: 22px;
            background: rgba(255, 255, 255, .82);
            box-shadow: 0 18px 45px rgba(105, 73, 99, .1);
            backdrop-filter: blur(8px);
        }

        .form-card {
            padding: clamp(22px, 4vw, 34px);
            animation: fade-up .65s .1s var(--ease-smooth) both;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            align-items: end;
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
            box-shadow: 0 0 0 4px rgba(214, 154, 187, .18);
            background: #ffffff;
            transform: translate3d(0, -1px, 0);
        }

        input[type="submit"] {
            min-height: 46px;
            padding: 12px 18px;
            border: 0;
            border-radius: 12px;
            background: #d98eb5;
            color: #fff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(191, 108, 155, .2);
            transition:
                background-color .3s var(--ease-smooth),
                box-shadow .3s var(--ease-smooth),
                transform .3s var(--ease-smooth);
        }

        input[type="submit"]:hover {
            background: #c97ba5;
            box-shadow: 0 11px 20px rgba(191, 108, 155, .28);
            transform: translate3d(0, -2px, 0);
        }

        input[type="submit"]:active {
            box-shadow: 0 4px 8px rgba(191, 108, 155, .18);
            transform: translate3d(0, 1px, 0);
            transition-duration: .12s;
        }

        .table-card {
            margin-top: 24px;
            overflow-x: auto;
            padding: 8px;
            animation: fade-up .65s .2s var(--ease-smooth) both;
        }

        table {
            width: 100%;
            min-width: 680px;
            border-collapse: separate;
            border-spacing: 0;
        }

        th,
        td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #f1e5ec;
        }

        th {
            color: #92859a;
            background: #fff7fb;
            font-size: 12px;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        th:first-child {
            border-radius: 14px 0 0 14px;
        }

        th:last-child {
            border-radius: 0 14px 14px 0;
        }

        tbody tr {
            transition: background-color .3s var(--ease-smooth);
        }

        tbody tr:hover {
            background: #fff9fc;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        tbody tr.new-user {
            background: #fff9fc;
        }

        tbody tr.new-user td {
            opacity: 0;
            animation: new-user-cell-in .55s var(--ease-smooth) forwards;
            will-change: opacity, transform;
        }

        tbody tr.new-user td:nth-child(1) {
            animation-delay: 0ms;
        }

        tbody tr.new-user td:nth-child(2) {
            animation-delay: 35ms;
        }

        tbody tr.new-user td:nth-child(3) {
            animation-delay: 70ms;
        }

        tbody tr.new-user td:nth-child(4) {
            animation-delay: 105ms;
        }

        tbody tr.new-user td:nth-child(5) {
            animation-delay: 140ms;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .action {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition:
                transform .3s var(--ease-smooth),
                filter .3s var(--ease-smooth),
                box-shadow .3s var(--ease-smooth);
        }

        .action:hover {
            filter: brightness(.96);
            box-shadow: 0 5px 10px rgba(105, 73, 99, .1);
            transform: translate3d(0, -2px, 0);
        }

        .action:active {
            box-shadow: none;
            transform: translate3d(0, 0, 0);
            transition-duration: .12s;
        }

        input[type="submit"]:focus-visible,
        .action:focus-visible {
            outline: 3px solid rgba(214, 154, 187, .45);
            outline-offset: 3px;
        }

        .edit {
            background: #e8f5f2;
            color: #4e978e;
        }

        .delete {
            background: #fff0f3;
            color: #c47791;
        }

        .toast {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: calc(100% - 48px);
            padding: 13px 17px;
            border: 1px solid #cce9df;
            border-radius: 13px;
            background: #f1fbf7;
            color: #4e897b;
            box-shadow: 0 12px 28px rgba(78, 137, 123, .16);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            user-select: none;
            animation: toast-in .55s var(--ease-smooth) both;
        }

        .toast.is-dismissed {
            animation: toast-out .4s var(--ease-smooth) forwards;
        }

        .toast-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #a9daca;
            color: #fff;
            font-size: 14px;
        }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translate3d(24px, -10px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes toast-out {
            from {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
            to {
                opacity: 0;
                transform: translate3d(24px, -10px, 0);
            }
        }

        @keyframes new-user-cell-in {
            from {
                opacity: 0;
                transform: translate3d(0, -8px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @media (max-width: 650px) {
            body {
                padding: 28px 14px;
            }

            .toast {
                top: 14px;
                right: 14px;
                left: 14px;
                max-width: none;
            }

            .fields {
                grid-template-columns: 1fr;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }

            .toast {
                animation: none;
            }
        }
    </style>
</head>
<body>
    <div class="toast" role="status" aria-live="polite">
        <span class="toast-icon" aria-hidden="true">&#10003;</span>
        <?= $userAdded ? 'User added successfully' : 'Database connection successful' ?>
    </div>

    <main class="page">
        <header class="heading">
            <p class="eyebrow">User directory</p>
            <h1>Manage users</h1>
            <p class="subtitle">Add and manage people in your directory.</p>
        </header>

        <form class="card form-card" action="" method="post">
            <div class="fields">
                <div>
                    <label for="fname">First name</label>
                    <input type="text" name="first_name" id="fname">
                </div>
                <div>
                    <label for="mname">Middle name</label>
                    <input type="text" name="middle_name" id="mname">
                </div>
                <div>
                    <label for="lname">Last name</label>
                    <input type="text" name="last_name" id="lname">
                </div>
                <input type="submit" value="Add user" name="insert">
            </div>
        </form>

        <section class="card table-card">
            <table>
                <tr>
                    <th>ID</th>
                    <th>First name</th>
                    <th>Middle name</th>
                    <th>Last name</th>
                    <th>Actions</th>
                </tr>
                <?php foreach($results as $row) { ?>
                <tr class="<?= (int) $row['ID'] === $newUserId ? 'new-user' : '' ?>">
                    <td><?= $row['ID'] ?> </td>
                    <td><?= $row['firstName'] ?> </td>
                    <td><?= $row['middleName'] ?> </td>
                    <td><?= $row['lastName'] ?> </td>
                    <td class="actions">
                        <a class="action edit" href="edit.php?id=<?= $row['ID'] ?>">Edit</a>
                        <a class="action delete" href="delete.php?id=<?= $row['ID'] ?>">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </section>
    </main>
    <script>
        const toast = document.querySelector('.toast');

        if (toast) {
            let dismissTimer;

            const dismissToast = () => {
                if (toast.hidden) {
                    return;
                }

                clearTimeout(dismissTimer);
                toast.classList.add('is-dismissed');
                dismissTimer = setTimeout(() => {
                    toast.hidden = true;
                }, 400);
            };

            toast.addEventListener('click', dismissToast);
            dismissTimer = setTimeout(dismissToast, 3200);
        }
    </script>
</body>
</html>
