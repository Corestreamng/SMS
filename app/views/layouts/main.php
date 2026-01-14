<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'School Management System' ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><?= APP_NAME ?></h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="/dashboard"><i class="fas fa-dashboard"></i> Dashboard</a></li>
                    <?php if (isset($auth) && $auth->hasPermission('students.view')): ?>
                    <li><a href="/students"><i class="fas fa-user-graduate"></i> Students</a></li>
                    <?php endif; ?>
                    <?php if (isset($auth) && $auth->hasPermission('staff.view')): ?>
                    <li><a href="/staff"><i class="fas fa-chalkboard-teacher"></i> Staff</a></li>
                    <?php endif; ?>
                    <?php if (isset($auth) && $auth->hasPermission('academics.view')): ?>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-book"></i> Academics <i class="fas fa-chevron-down"></i></a>
                        <ul class="submenu">
                            <li><a href="/academics/classes">Classes</a></li>
                            <li><a href="/academics/subjects">Subjects</a></li>
                            <li><a href="/academics/timetable">Timetable</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($auth) && $auth->hasPermission('attendance.view')): ?>
                    <li><a href="/attendance"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                    <?php endif; ?>
                    <?php if (isset($auth) && $auth->hasPermission('exams.view')): ?>
                    <li><a href="/exams"><i class="fas fa-file-alt"></i> Exams</a></li>
                    <li><a href="/results"><i class="fas fa-chart-line"></i> Results</a></li>
                    <?php endif; ?>
                    <?php if (isset($auth) && $auth->hasPermission('finance.view')): ?>
                    <li><a href="/finance"><i class="fas fa-money-bill-wave"></i> Finance</a></li>
                    <?php endif; ?>
                    <li><a href="/calendar"><i class="fas fa-calendar"></i> Calendar</a></li>
                    <?php if (isset($auth) && $auth->hasPermission('settings.view')): ?>
                    <li><a href="/settings"><i class="fas fa-cog"></i> Settings</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="topbar-right">
                    <div class="user-menu">
                        <span class="user-name">
                            <?= htmlspecialchars($user['first_name'] ?? 'User') ?> 
                            <?= htmlspecialchars($user['last_name'] ?? '') ?>
                        </span>
                        <div class="dropdown">
                            <button class="dropdown-toggle"><i class="fas fa-user-circle"></i></button>
                            <ul class="dropdown-menu">
                                <li><a href="/profile">Profile</a></li>
                                <li><a href="/logout">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?= $content ?? '' ?>
            </div>
        </div>
    </div>

    <script src="/public/js/main.js"></script>
</body>
</html>
