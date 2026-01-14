<?php 
$pageTitle = 'Students';
$user = $this->auth->user();
ob_start(); 
?>

<div class="page-header">
    <h2>Student Management</h2>
    <div class="page-actions">
        <a href="/students/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Student
        </a>
        <button class="btn btn-secondary" onclick="document.getElementById('bulkUploadModal').style.display='block'">
            <i class="fas fa-upload"></i> Bulk Upload
        </button>
        <a href="/students/download-template" class="btn btn-secondary">
            <i class="fas fa-download"></i> Download Template
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Matric Number</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['matric_number']) ?></td>
                            <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td><?= htmlspecialchars($student['class_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($student['section_name'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge badge-<?= $student['status'] === 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($student['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/students/edit/<?= $student['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No students found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div id="bulkUploadModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('bulkUploadModal').style.display='none'">&times;</span>
        <h3>Bulk Upload Students</h3>
        <form method="POST" action="/students/bulk-upload" enctype="multipart/form-data">
            <div class="form-group">
                <label>CSV File</label>
                <input type="file" name="csv_file" accept=".csv" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
