<?php
/** @var PDO $pdo */
include 'db_connect.php';
require_once 'log_request.php';

$group = $_GET['group'] ?? null;

log_request('schedule_group.php', ['group' => $group]);

if (isset($_POST['group'])) {
    $groupId = $_POST['group'];

    try {
        $stmt = $pdo->prepare("SELECT title FROM groups WHERE ID_Groups = ?");
        $stmt->execute([$groupId]);
        $groupTitle = $stmt->fetchColumn();

        $sql = "
            SELECT l.week_day, l.lesson_number, l.auditorium, l.disciple, t.name, l.type 
            FROM lesson l
            JOIN lesson_groups lg ON l.ID_Lesson = lg.FID_Lesson2
            LEFT JOIN lesson_teacher lt ON l.ID_Lesson = lt.FID_Lesson1
            LEFT JOIN teacher t ON lt.FID_Teacher = t.ID_Teacher
            WHERE lg.FID_Groups = ?
            ORDER BY l.week_day, l.lesson_number
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$groupId]);
        $schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h1>Розклад для групи: $groupTitle</h1>";
        if ($schedule) {
            echo "<table>
                    <tr>
                        <th>День тижня</th>
                        <th>Пара</th>
                        <th>Аудиторія</th>
                        <th>Дисципліна</th>
                        <th>Викладач</th>
                        <th>Тип заняття</th>
                    </tr>";

            foreach ($schedule as $row) {
                echo "<tr>
                        <td>{$row['week_day']}</td>
                        <td>{$row['lesson_number']}</td>
                        <td>{$row['auditorium']}</td>
                        <td>{$row['disciple']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['type']}</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Для цієї групи немає запланованих занять.</p>";
        }
    } catch (PDOException $e) {
        die("Помилка виконання запиту: " . $e->getMessage());
    }

    echo '<p><a href="index.php">Повернутися на головну</a></p>';
} else {
    header("Location: index.php");
    exit();
}
?>
