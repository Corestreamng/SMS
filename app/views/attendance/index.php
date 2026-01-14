<?php 
$pageTitle = 'Attendance';
$user = $this->auth->user();
ob_start(); 
?>

<div class="page-header">
    <h2>Attendance Management</h2>
</div>

<div class="card">
    <div class="card-header">
        <h3>Record Attendance</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="attendance-filter">
            <div class="form-row">
                <div class="form-group">
                    <label>Class</label>
                    <select name="class_id" class="form-control" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" value="<?= $selectedDate ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Session</label>
                    <select name="session" class="form-control" required>
                        <option value="morning" <?= $selectedSession === 'morning' ? 'selected' : '' ?>>Morning</option>
                        <option value="afternoon" <?= $selectedSession === 'afternoon' ? 'selected' : '' ?>>Afternoon</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">Load Students</button>
                </div>
            </div>
        </form>

        <?php if (!empty($students)): ?>
            <form method="POST" action="/attendance/record" class="attendance-form">
                <input type="hidden" name="class_id" value="<?= $selectedClass ?>">
                <input type="hidden" name="date" value="<?= $selectedDate ?>">
                <input type="hidden" name="session" value="<?= $selectedSession ?>">
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($students as $student): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                <td>
                                    <select name="attendance[<?= $student['id'] ?>]" class="form-control">
                                        <option value="present" <?= ($attendance[$student['id']] ?? '') === 'present' ? 'selected' : '' ?>>Present</option>
                                        <option value="absent" <?= ($attendance[$student['id']] ?? '') === 'absent' ? 'selected' : '' ?>>Absent</option>
                                        <option value="late" <?= ($attendance[$student['id']] ?? '') === 'late' ? 'selected' : '' ?>>Late</option>
                                        <option value="excused" <?= ($attendance[$student['id']] ?? '') === 'excused' ? 'selected' : '' ?>>Excused</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Attendance</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
