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
        #result-container { margin-top: 20px; }
        .back-link { display: block; margin-top: 20px; }
    </style>
</head>
<body>
<h1>Розклад занять</h1>

<div class="form-container">
    <h2>Розклад для групи (HTML відповідь)</h2>
    <form id="groupForm">
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
    <div id="group-result" class="result-container"></div>
</div>

<div class="form-container">
    <h2>Розклад для викладача (XML відповідь)</h2>
    <form id="teacherForm">
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
    <div id="teacher-result" class="result-container"></div>
</div>

<div class="form-container">
    <h2>Розклад для аудиторії (JSON відповідь)</h2>
    <form id="auditoriumForm">
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
    <div id="auditorium-result" class="result-container"></div>
</div>

<script>
    document.getElementById('groupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'schedule_group.php', true);
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('group-result').innerHTML = this.responseText;
            }
        };
        xhr.send(formData);
    });

    document.getElementById('teacherForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'schedule_teacher.php', true);
        xhr.onload = function() {
            if (this.status === 200) {
                const xmlDoc = this.responseXML;
                const container = document.getElementById('teacher-result');
                container.innerHTML = '';

                const header = xmlDoc.getElementsByTagName('header')[0].textContent;
                const lessons = xmlDoc.getElementsByTagName('lesson');

                const h1 = document.createElement('h1');
                h1.textContent = header;
                container.appendChild(h1);

                if (lessons.length > 0) {
                    const table = document.createElement('table');
                    const thead = document.createElement('tr');

                    ['День тижня', 'Пара', 'Аудиторія', 'Дисципліна', 'Тип заняття', 'Група'].forEach(text => {
                        const th = document.createElement('th');
                        th.textContent = text;
                        thead.appendChild(th);
                    });
                    table.appendChild(thead);

                    for (let i = 0; i < lessons.length; i++) {
                        const lesson = lessons[i];
                        const tr = document.createElement('tr');

                        ['week_day', 'lesson_number', 'auditorium', 'disciple', 'type', 'group_title'].forEach(tag => {
                            const td = document.createElement('td');
                            td.textContent = lesson.getElementsByTagName(tag)[0].textContent;
                            tr.appendChild(td);
                        });

                        table.appendChild(tr);
                    }

                    container.appendChild(table);
                } else {
                    const p = document.createElement('p');
                    p.textContent = 'Для цього викладача немає запланованих занять.';
                    container.appendChild(p);
                }

                const backLink = document.createElement('a');
                backLink.href = 'index.php';
                backLink.textContent = 'Повернутися на головну';
                backLink.className = 'back-link';
                container.appendChild(backLink);
            }
        };
        xhr.send(formData);
    });

    document.getElementById('auditoriumForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('schedule_auditorium.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('auditorium-result');
                container.innerHTML = '';

                const h1 = document.createElement('h1');
                h1.textContent = data.header;
                container.appendChild(h1);

                if (data.schedule && data.schedule.length > 0) {
                    const table = document.createElement('table');
                    const thead = document.createElement('tr');

                    ['День тижня', 'Пара', 'Дисципліна', 'Викладач', 'Тип заняття', 'Група'].forEach(text => {
                        const th = document.createElement('th');
                        th.textContent = text;
                        thead.appendChild(th);
                    });
                    table.appendChild(thead);

                    data.schedule.forEach(row => {
                        const tr = document.createElement('tr');

                        ['week_day', 'lesson_number', 'disciple', 'name', 'type', 'group_title'].forEach(key => {
                            const td = document.createElement('td');
                            td.textContent = row[key] || '';
                            tr.appendChild(td);
                        });

                        table.appendChild(tr);
                    });

                    container.appendChild(table);
                } else {
                    const p = document.createElement('p');
                    p.textContent = 'Для цієї аудиторії немає запланованих занять.';
                    container.appendChild(p);
                }

                const backLink = document.createElement('a');
                backLink.href = 'index.php';
                backLink.textContent = 'Повернутися на головну';
                backLink.className = 'back-link';
                container.appendChild(backLink);
            })
            .catch(error => console.error('Error:', error));
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const browser = navigator.userAgent;
            const timestamp = new Date().toISOString();

            fetch('log_request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    endpoint: window.location.pathname,
                    parameters: window.location.search,
                    timestamp: timestamp,
                    browser: browser,
                    latitude: latitude,
                    longitude: longitude
                })
            });
        });
    }
});
</script>

</body>
</html>