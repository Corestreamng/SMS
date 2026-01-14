<?php 
$pageTitle = 'Dashboard';
$user = $this->auth->user();
ob_start(); 
?>

<!-- Welcome Card - Full Width -->
<div class="welcome-card">
    <div class="welcome-content">
        <h1>Welcome back, <?= htmlspecialchars($user['first_name']) ?>! 👋</h1>
        <p>Here's what's happening with your school today.</p>
    </div>
    <div class="welcome-meta">
        <div class="meta-item">
            <span class="meta-label">Available</span>
            <span class="meta-value"><?= $currentDate ?? date('l, F d, Y') ?></span>
        </div>
        <div class="meta-item">
            <span class="meta-time"><?= $currentTime ?? date('h:i A') ?></span>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-content">
            <h3><?= number_format($stats['total_students'] ?? 0) ?></h3>
            <p>Total Students</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-content">
            <h3><?= number_format($stats['total_staff'] ?? 0) ?></h3>
            <p>Total Staff</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-school"></i>
        </div>
        <div class="stat-content">
            <h3><?= number_format($stats['total_classes'] ?? 0) ?></h3>
            <p>Total Classes</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <h3><?= number_format($stats['today_attendance'] ?? 0) ?></h3>
            <p>Present Today</p>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="dashboard-row">
    <div class="dashboard-col-8">
        <div class="card">
            <div class="card-header">
                <h3>Recent Activities</h3>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-description">
                                        <strong><?= htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']) ?></strong>
                                        <?= htmlspecialchars($activity['description']) ?>
                                    </p>
                                    <span class="activity-time"><?= date('M d, Y H:i', strtotime($activity['created_at'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No recent activities</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-col-4">
        <div class="card">
            <div class="card-header">
                <h3>Upcoming Events</h3>
            </div>
            <div class="card-body">
                <div class="events-list">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <div class="event-item">
                                <div class="event-date">
                                    <span class="event-day"><?= date('d', strtotime($event['start_date'])) ?></span>
                                    <span class="event-month"><?= date('M', strtotime($event['start_date'])) ?></span>
                                </div>
                                <div class="event-content">
                                    <h4><?= htmlspecialchars($event['title']) ?></h4>
                                    <?php if (!empty($event['title_arabic'])): ?>
                                        <p class="arabic-text"><?= htmlspecialchars($event['title_arabic']) ?></p>
                                    <?php endif; ?>
                                    <span class="event-type"><?= ucfirst($event['event_type']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No upcoming events</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3>SMS System</h3>
            </div>
            <div class="card-body">
                <div class="system-info">
                    <p><strong>Version:</strong> <?= APP_VERSION ?></p>
                    <p><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                    <p><strong>School:</strong> <?= htmlspecialchars($GLOBALS['school_name'] ?? 'Sample School') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
