<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Розклад занять</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<h1>Розклад занять</h1>

<div class="form-container">
    <h2>Розклад для групи</h2>
    <form action="schedule_group.php" method="post">
        <label for="group">Оберіть групу:</label>
        <select name="group" id="group" required>
            <?php
            /** @var PDO $pdo */
            include 'db_connect.php';
            $stmt = $pdo->query("SELECT * FROM groups");
            while ($row = $stmt->fetch()) {
                echo "<option value='{$row['ID_Groups']}'>{$row['title']}</option>";
            }
            ?>
        </select>
        <button type="submit">Показати розклад</button>
    </form>
</div>

<div class="form-container">
    <h2>Розклад для викладача</h2>
    <form action="schedule_teacher.php" method="post">
        <label for="teacher">Оберіть викладача:</label>
        <select name="teacher" id="teacher" required>
            <?php
            $stmt = $pdo->query("SELECT * FROM teacher");
            while ($row = $stmt->fetch()) {
                echo "<option value='{$row['ID_Teacher']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <button type="submit">Показати розклад</button>
    </form>
</div>

<div class="form-container">
    <h2>Розклад для аудиторії</h2>
    <form action="schedule_auditorium.php" method="post">
        <label for="auditorium">Оберіть аудиторію:</label>
        <select name="auditorium" id="auditorium" required>
            <?php
            $stmt = $pdo->query("SELECT DISTINCT auditorium FROM lesson");
            while ($row = $stmt->fetch()) {
                echo "<option value='{$row['auditorium']}'>{$row['auditorium']}</option>";
            }
            ?>
        </select>
        <button type="submit">Показати розклад</button>
    </form>
</div>
</body>
</html>
