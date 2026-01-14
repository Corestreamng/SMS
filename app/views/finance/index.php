<?php 
$pageTitle = 'Finance';
$user = $this->auth->user();
ob_start(); 
?>

<div class="page-header">
    <h2>Finance Management</h2>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <h3>$<?= number_format($summary['monthly_revenue'] ?? 0, 2) ?></h3>
            <p>Monthly Revenue</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-content">
            <h3>$<?= number_format($summary['yearly_revenue'] ?? 0, 2) ?></h3>
            <p>Yearly Revenue</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3><?= $summary['pending_count'] ?? 0 ?></h3>
            <p>Pending Payments</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Recent Payments</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Matric Number</th>
                    <th>Fee Type</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentPayments)): ?>
                    <?php foreach ($recentPayments as $payment): ?>
                        <tr>
                            <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                            <td><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?></td>
                            <td><?= htmlspecialchars($payment['matric_number']) ?></td>
                            <td><?= htmlspecialchars($payment['fee_name']) ?></td>
                            <td>$<?= number_format($payment['amount_paid'], 2) ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?></td>
                            <td>
                                <span class="badge badge-<?= $payment['status'] === 'completed' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($payment['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No payments found</td>
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
