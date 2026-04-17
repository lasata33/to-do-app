<?php require 'db.php';

// Fetch dates that have tasks
$taskDates = $pdo->query("SELECT DISTINCT task_date FROM tasks")->fetchAll(PDO::FETCH_COLUMN);

// Fetch dates that have notes
$noteDates = $pdo->query("SELECT DISTINCT note_date FROM notes")->fetchAll(PDO::FETCH_COLUMN);

$activeDates = array_unique(array_merge($taskDates, $noteDates));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To Do App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f5f3ee;
        background-image: radial-gradient(#c9d4e8 1px, transparent 1px);
        background-size: 22px 22px;
        min-height: 100vh;
    }
    .main-card {
        background: #ffffffee;
        border-radius: 20px;
        border: 1.5px solid #c9d4e8;
        padding: 1.5rem;
    }
    .section-title {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #aab4c8;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    /* ── Calendar ── */
    .cal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .cal-header span {
        font-size: 17px;
        font-weight: 700;
        color: #7b9abf;
    }
    .cal-header button {
        background: none;
        border: 1.5px solid #c9d4e8;
        border-radius: 8px;
        padding: 2px 10px;
        color: #7b9abf;
        font-family: 'Nunito', sans-serif;
        font-size: 16px;
        cursor: pointer;
    }
    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        text-align: center;
    }
    .cal-day-name {
        font-size: 13px;
        font-weight: 700;
        color: #aab4c8;
        padding: 4px 0;
    }
    .cal-day {
        font-size: 15px;
        padding: 6px 4px;
        border-radius: 10px;
        cursor: pointer;
        position: relative;
        color: #5a7a9e;
        transition: background 0.15s;
    }
    .cal-day:hover { background: #e8eef8; }
    .cal-day.today { background: #7b9abf; color: white; font-weight: 700; }
    .cal-day.selected { background: #dce8f5; font-weight: 700; color: #3a6a9e; }
    .cal-day .dot {
        width: 5px; height: 5px;
        background: #7b9abf;
        border-radius: 50%;
        margin: 2px auto 0;
    }
    .cal-day.today .dot { background: white; }
    /* ── Tasks ── */
    .task-input-row { display: flex; gap: 8px; margin-bottom: 12px; }
    .task-input {
        flex: 1;
        border: 1.5px dashed #b8c9e0;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 15px;
        font-family: 'Nunito', sans-serif;
        background: #f5f8ff;
        color: #5a7a9e;
        outline: none;
    }
    .task-input::placeholder { color: #b8c9e0; }
    .btn-add {
        background: #7b9abf;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Nunito', sans-serif;
        cursor: pointer;
    }
    .task-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f5f8ff;
        border: 1.5px solid #dce8f5;
        border-radius: 14px;
        padding: 10px 14px;
        margin-bottom: 8px;
    }
    .task-item.done { background: #f0f5f0; border-color: #c5ddc5; }
    .task-title { font-size: 15px; color: #5a7a9e; font-weight: 500; }
    .task-title.done-text { text-decoration: line-through; color: #aab4c8; }
    .btn-sm-cute {
        border: none;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 13px;
        font-family: 'Nunito', sans-serif;
        cursor: pointer;
        font-weight: 600;
    }
    .btn-done-c { background: #e6f2e6; color: #6aaa6a; }
    .btn-edit-c { background: #e8eef8; color: #7b9abf; }
    .btn-del-c  { background: #fce8e8; color: #d48a8a; }
    .divider { border: none; border-top: 1.5px dashed #dce8f5; margin: 12px 0; }
    /* ── Notes ── */
    .note-item {
        background: #fffbf0;
        border: 1.5px solid #f0e6c8;
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 8px;
    }
    .note-title { font-size: 15px; font-weight: 700; color: #b8963e; margin-bottom: 4px; }
    .note-body { font-size: 14px; color: #8a7a5a; line-height: 1.6; }
    .note-input {
        width: 100%;
        border: 1.5px dashed #f0e6c8;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 15px;
        font-family: 'Nunito', sans-serif;
        background: #fffbf0;
        color: #8a7a5a;
        outline: none;
        margin-bottom: 8px;
        box-sizing: border-box;
    }
    .note-input::placeholder { color: #d4c4a0; }
    .btn-add-note {
        background: #e8c96a;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Nunito', sans-serif;
        cursor: pointer;
        width: 100%;
    }
    .empty-state { text-align: center; color: #aab4c8; font-size: 15px; padding: 1rem 0; }
</style>
</head>
<body>
<div class="container-fluid py-4 px-3 px-md-5">

    <!-- Header -->
    <div class="text-center mb-4">
        <h1 style="font-size:24px; font-weight:700; color:#7b9abf;">🪼 My To-Do</h1>
        <p style="font-size:13px; color:#aab4c8;">click a date to see your tasks & notes</p>
    </div>

    <div class="row g-4">

      <!-- LEFT: Calendar + Quote -->
<div class="col-12 col-md-4">

    <!-- Calendar Card -->
    <div class="main-card mb-4">
        <p class="section-title">📅 calendar</p>
        <div class="cal-header">
            <button onclick="changeMonth(-1)">‹</button>
            <span id="cal-month-label"></span>
            <button onclick="changeMonth(1)">›</button>
        </div>
        <div class="cal-grid" id="cal-day-names"></div>
        <div class="cal-grid mt-1" id="cal-days"></div>
    </div>

    <!-- Daily Quote Card -->
    <div class="main-card">
        <p class="section-title">✨ quote of the day</p>
        <div id="quote-box">
            <p style="font-size:13px; color:#aab4c8; text-align:center;">loading quote... 🪼</p>
        </div>
    </div>

</div>

        <!-- RIGHT: Tasks + Notes -->
        <div class="col-12 col-md-8">

            <!-- Tasks -->
            <div class="main-card mb-4">
                <p class="section-title">📝 tasks for <span id="selected-date-label" style="color:#7b9abf;"></span></p>

                <!-- Add Task -->
                <form action="add.php" method="POST" class="task-input-row">
                    <input type="hidden" name="date" id="task-date-input" value="">
                    <input type="text" name="title" class="task-input" placeholder="add a task..." required>
                    <button type="submit" class="btn-add">+ add</button>
                </form>

                <!-- Pending Tasks -->
                <div id="pending-tasks"></div>

                <hr class="divider">
                <p class="section-title">✅ completed</p>

                <!-- Completed Tasks -->
                <div id="completed-tasks"></div>
            </div>

            <!-- Notes -->
            <div class="main-card">
                <p class="section-title">🗒️ notes for <span id="selected-date-label-2" style="color:#b8963e;"></span></p>

                <!-- Add Note -->
                <form action="add_note.php" method="POST">
                    <input type="hidden" name="date" id="note-date-input" value="">
                    <input type="text" name="title" class="note-input" placeholder="note title..." required>
                    <textarea name="body" class="note-input" rows="2" placeholder="write your note here..." required></textarea>
                    <button type="submit" class="btn-add-note">+ add note</button>
                </form>

                <hr class="divider">
                <div id="notes-list"></div>
            </div>

        </div>
    </div>
</div>

<script>
const activeDates = <?= json_encode($activeDates) ?>;
let currentDate = new Date();
let selectedDate = new Date().toISOString().split('T')[0];

function formatLabel(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-US', { weekday: 'short', month: 'long', day: 'numeric' });
}

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const today = new Date().toISOString().split('T')[0];

    document.getElementById('cal-month-label').textContent =
        new Date(year, month).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    const dayNames = document.getElementById('cal-day-names');
    dayNames.innerHTML = ['Su','Mo','Tu','We','Th','Fr','Sa']
        .map(d => `<div class="cal-day-name">${d}</div>`).join('');

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const grid = document.getElementById('cal-days');
    grid.innerHTML = '';

    for (let i = 0; i < firstDay; i++) {
        grid.innerHTML += `<div></div>`;
    }

    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const isToday = dateStr === today;
        const isSelected = dateStr === selectedDate;
        const hasActivity = activeDates.includes(dateStr);
        grid.innerHTML += `
            <div class="cal-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''}"
                 onclick="selectDate('${dateStr}')">
                ${d}
                ${hasActivity ? '<div class="dot"></div>' : ''}
            </div>`;
    }
}

function changeMonth(dir) {
    currentDate.setMonth(currentDate.getMonth() + dir);
    renderCalendar();
}

function selectDate(dateStr) {
    selectedDate = dateStr;
    document.getElementById('task-date-input').value = dateStr;
    document.getElementById('note-date-input').value = dateStr;
    const label = formatLabel(dateStr);
    document.getElementById('selected-date-label').textContent = label;
    document.getElementById('selected-date-label-2').textContent = label;
    renderCalendar();
    loadTasks(dateStr);
    loadNotes(dateStr);
}

function loadTasks(dateStr) {
    fetch(`get_tasks_by_date.php?date=${dateStr}`)
        .then(r => r.json())
        .then(tasks => {
            const pending = tasks.filter(t => t.status === 'pending');
            const completed = tasks.filter(t => t.status === 'completed');

            document.getElementById('pending-tasks').innerHTML = pending.length
                ? pending.map(t => `
                    <div class="task-item">
                        <span class="task-title">${t.title}</span>
                        <div class="d-flex gap-1">
                            <a href="complete.php?id=${t.id}" class="btn-sm-cute btn-done-c">✅ done</a>
                            <a href="edit.php?id=${t.id}" class="btn-sm-cute btn-edit-c">✏️ edit</a>
                            <a href="delete.php?id=${t.id}" class="btn-sm-cute btn-del-c">🗑️</a>
                        </div>
                    </div>`).join('')
                : `<div class="empty-state">no pending tasks 🌿</div>`;

            document.getElementById('completed-tasks').innerHTML = completed.length
                ? completed.map(t => `
                    <div class="task-item done">
                        <span class="task-title done-text">${t.title}</span>
                        <a href="delete.php?id=${t.id}" class="btn-sm-cute btn-del-c">🗑️</a>
                    </div>`).join('')
                : `<div class="empty-state">nothing completed yet 🪼</div>`;
        });
}

function loadNotes(dateStr) {
    fetch(`get_notes_by_date.php?date=${dateStr}`)
        .then(r => r.json())
        .then(notes => {
            document.getElementById('notes-list').innerHTML = notes.length
                ? notes.map(n => `
                    <div class="note-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <p class="note-title">${n.title}</p>
                            <a href="delete_note.php?id=${n.id}" class="btn-sm-cute btn-del-c">🗑️</a>
                        </div>
                        <p class="note-body">${n.body}</p>
                    </div>`).join('')
                : `<div class="empty-state">no notes for this day 📭</div>`;
        });
}

// Daily Quote
function loadQuote() {
    fetch('https://api.quotable.io/random?tags=inspirational,life,success')
        .then(r => r.json())
        .then(data => {
            document.getElementById('quote-box').innerHTML = `
                <p style="font-size:14px; color:#5a7a9e; font-style:italic; line-height:1.6; margin-bottom:8px;">
                    "${data.content}"
                </p>
                <p style="font-size:11px; color:#aab4c8; text-align:right; margin:0;">
                    — ${data.author}
                </p>`;
        })
        .catch(() => {
            document.getElementById('quote-box').innerHTML =
                `<p style="font-size:13px; color:#aab4c8; text-align:center;">
                    "you're doing amazing sweetie 🌿"
                </p>`;
        });
}
loadQuote();
// Init
selectDate(selectedDate);
</script>
</body>
</html>