<?php
/** @var PDO $pdo */
include 'db_connect.php';

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

        $response = [
            'header' => "Розклад для аудиторії: $auditorium",
            'schedule' => $schedule
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        die(json_encode(['error' => "Помилка виконання запиту: " . $e->getMessage()]));
    }
} else {
    header("Location: index.php");
    exit();
}
?>