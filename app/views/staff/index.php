<?php 
$pageTitle = 'Staff';
$user = $this->auth->user();
ob_start(); 
?>

<div class="page-header">
    <h2>Staff Management</h2>
    <div class="page-actions">
        <a href="/staff/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Staff
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Designation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($staff)): ?>
                    <?php foreach ($staff as $member): ?>
                        <tr>
                            <td><?= htmlspecialchars($member['staff_id'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></td>
                            <td><?= htmlspecialchars($member['email']) ?></td>
                            <td><?= htmlspecialchars($member['phone']) ?></td>
                            <td><?= ucfirst(str_replace('-', ' ', $member['staff_type'])) ?></td>
                            <td><?= htmlspecialchars($member['designation'] ?? 'N/A') ?></td>
                            <td>
                                <a href="/staff/edit/<?= $member['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No staff members found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
