<?php 
$pageTitle = 'Calendar';
$user = $this->auth->user();
ob_start(); 
?>

<div class="page-header">
    <h2>School Calendar</h2>
    <?php if ($this->auth->hasPermission('settings.manage')): ?>
        <button class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Event
        </button>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="calendar-events">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <div class="event-card-date">
                            <span class="day"><?= date('d', strtotime($event['start_date'])) ?></span>
                            <span class="month"><?= date('M', strtotime($event['start_date'])) ?></span>
                        </div>
                        <div class="event-card-content">
                            <h4><?= htmlspecialchars($event['title']) ?></h4>
                            <?php if (!empty($event['title_arabic'])): ?>
                                <p class="arabic-text"><?= htmlspecialchars($event['title_arabic']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($event['description'])): ?>
                                <p><?= htmlspecialchars($event['description']) ?></p>
                            <?php endif; ?>
                            <div class="event-meta">
                                <span class="event-type badge badge-info">
                                    <?= ucfirst($event['event_type']) ?>
                                </span>
                                <span class="event-date">
                                    <?= date('M d', strtotime($event['start_date'])) ?> - 
                                    <?= date('M d, Y', strtotime($event['end_date'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center">No upcoming events</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
