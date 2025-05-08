<?php
/** @var PDO $pdo */
include 'db_connect.php';

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

        header('Content-Type: application/xml');
        $xml = new SimpleXMLElement('<response></response>');
        $xml->addChild('header', "Розклад для викладача: $teacherName");

        if ($schedule) {
            foreach ($schedule as $row) {
                $lesson = $xml->addChild('lesson');
                foreach ($row as $key => $value) {
                    $lesson->addChild($key, htmlspecialchars($value));
                }
            }
        }

        echo $xml->asXML();
    } catch (PDOException $e) {
        die("Помилка виконання запиту: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>