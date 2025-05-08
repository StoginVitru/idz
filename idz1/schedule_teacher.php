<?php
/** @var PDO $pdo */
include 'db_connect.php';
require_once 'log_request.php';

$teacher = $_GET['teacher'] ?? null;

log_request('schedule_teacher.php', ['teacher' => $teacher]);

if (isset($_POST['teacher'])) {
    $teacherId = $_POST['teacher'];

    try {
        $stmt = $pdo->prepare("SELECT name FROM teacher WHERE ID_Teacher = ?");
        $stmt->execute([$teacherId]);
        $teacherName = $stmt->fetchColumn();

        $sql = "
            SELECT l.week_day, l.lesson_number, l.auditorium, l.disciple, l.type, g.title as group_title
            FROM lesson l
            JOIN lesson_teacher lt ON l.ID_Lesson = lt.FID_Lesson1
            JOIN lesson_groups lg ON l.ID_Lesson = lg.FID_Lesson2
            JOIN groups g ON lg.FID_Groups = g.ID_Groups
            WHERE lt.FID_Teacher = ?
            ORDER BY l.week_day, l.lesson_number
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$teacherId]);
        $schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h1>Розклад для викладача: $teacherName</h1>";
        if ($schedule) {
            echo "<table>
                    <tr>
                        <th>День тижня</th>
                        <th>Пара</th>
                        <th>Аудиторія</th>
                        <th>Дисципліна</th>
                        <th>Тип заняття</th>
                        <th>Група</th>
                    </tr>";

            foreach ($schedule as $row) {
                echo "<tr>
                        <td>{$row['week_day']}</td>
                        <td>{$row['lesson_number']}</td>
                        <td>{$row['auditorium']}</td>
                        <td>{$row['disciple']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['group_title']}</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Для цього викладача немає запланованих занять.</p>";
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
