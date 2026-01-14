<?php 
$pageTitle = 'Settings';
$user = $this->auth->user();
ob_start(); 
?>

<div class="page-header">
    <h2>School Settings</h2>
</div>

<form method="POST" action="/settings/update">
    <div class="card">
        <div class="card-header">
            <h3>General Settings</h3>
        </div>
        <div class="card-body">
            <?php if (isset($settings['general'])): ?>
                <?php foreach ($settings['general'] as $setting): ?>
                    <div class="form-group">
                        <label><?= htmlspecialchars($setting['description'] ?? $setting['setting_key']) ?></label>
                        <input type="text" 
                               name="setting_<?= $setting['setting_key'] ?>" 
                               value="<?= htmlspecialchars($setting['setting_value']) ?>" 
                               class="form-control">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3>Academic Settings</h3>
        </div>
        <div class="card-body">
            <?php if (isset($settings['academic'])): ?>
                <?php foreach ($settings['academic'] as $setting): ?>
                    <div class="form-group">
                        <label><?= htmlspecialchars($setting['description'] ?? $setting['setting_key']) ?></label>
                        <?php if ($setting['setting_type'] === 'boolean'): ?>
                            <select name="setting_<?= $setting['setting_key'] ?>" class="form-control">
                                <option value="true" <?= $setting['setting_value'] === 'true' ? 'selected' : '' ?>>Enabled</option>
                                <option value="false" <?= $setting['setting_value'] === 'false' ? 'selected' : '' ?>>Disabled</option>
                            </select>
                        <?php else: ?>
                            <input type="<?= $setting['setting_type'] === 'integer' ? 'number' : 'text' ?>" 
                                   name="setting_<?= $setting['setting_key'] ?>" 
                                   value="<?= htmlspecialchars($setting['setting_value']) ?>" 
                                   class="form-control">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3>Exam Types & Weights</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Exam Type</th>
                        <th>Weight (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($examTypes)): ?>
                        <?php foreach ($examTypes as $examType): ?>
                            <tr>
                                <td><?= htmlspecialchars($examType['name']) ?></td>
                                <td><?= htmlspecialchars($examType['weight_percentage']) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3>Grading System</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Grade</th>
                        <th>Range</th>
                        <th>Remarks (English)</th>
                        <th>Remarks (Arabic)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($gradingSystem)): ?>
                        <?php foreach ($gradingSystem as $grade): ?>
                            <tr>
                                <td><?= htmlspecialchars($grade['grade']) ?></td>
                                <td><?= $grade['min_percentage'] ?>% - <?= $grade['max_percentage'] ?>%</td>
                                <td><?= htmlspecialchars($grade['remarks']) ?></td>
                                <td class="arabic-text"><?= htmlspecialchars($grade['remarks_arabic']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-actions mt-3">
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </div>
</form>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/main.php';
?>
