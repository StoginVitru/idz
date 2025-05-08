<?php
/** @var PDO $pdo */
include 'db_connect.php';
require_once 'log_request.php';

$auditorium = $_GET['auditorium'] ?? null;
$day = $_GET['day'] ?? null;

log_request('schedule_auditorium.php', ['auditorium' => $auditorium, 'day' => $day]);

if (isset($_POST['auditorium'])) {
    $auditorium = $_POST['auditorium'];

    try {
        $sql = "
            SELECT l.week_day, l.lesson_number, l.disciple, t.name, l.type, g.title as group_title
            FROM lesson l
            LEFT JOIN lesson_teacher lt ON l.ID_Lesson = lt.FID_Lesson1
            LEFT JOIN teacher t ON lt.FID_Teacher = t.ID_Teacher
            LEFT JOIN lesson_groups lg ON l.ID_Lesson = lg.FID_Lesson2
            LEFT JOIN groups g ON lg.FID_Groups = g.ID_Groups
            WHERE l.auditorium = ?
            ORDER BY l.week_day, l.lesson_number
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$auditorium]);
        $schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h1>Розклад для аудиторії: $auditorium</h1>";
        if ($schedule) {
            echo "<table>
                    <tr>
                        <th>День тижня</th>
                        <th>Пара</th>
                        <th>Дисципліна</th>
                        <th>Викладач</th>
                        <th>Тип заняття</th>
                        <th>Група</th>
                    </tr>";

            foreach ($schedule as $row) {
                echo "<tr>
                        <td>{$row['week_day']}</td>
                        <td>{$row['lesson_number']}</td>
                        <td>{$row['disciple']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['group_title']}</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Для цієї аудиторії немає запланованих занять.</p>";
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
