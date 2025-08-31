<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard - Cyber Infinity'); ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        /* Reset & Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Layout */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navigation */
        .navbar {
            background-color: #ffffff;
            padding: 1rem 0;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            color: #1e293b;
            font-size: 1.5rem;
            text-decoration: none;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-link {
            color: #64748b;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        /* Content Area */
        .content-area {
            padding: 0;
            background-color: #f8fafc;
            min-height: calc(100vh - 140px);
        }

        .page-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #1e293b;
        }

        /* Footer */
        .footer {
            background-color: #ffffff;
            padding: 1rem 0;
            text-align: center;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                width: 100%;
            }

            .nav-link {
                text-align: center;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-content">
            <a href="<?php echo e(url('/')); ?>" class="nav-brand">Cyber Infinity</a>
            <div class="nav-links">
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link">Dashboard</a>
                <a href="<?php echo e(route('workshops.index')); ?>" class="nav-link">Workshops</a>
                <a href="<?php echo e(route('challenges.index')); ?>" class="nav-link">Challenges</a>
                <a href="<?php echo e(route('ctf.index')); ?>" class="nav-link">CTF Events</a>
                <?php if(auth()->user()->role === 'admin'): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link">Admin</a>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="nav-link" style="border: none; background: none; cursor: pointer;">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <?php echo $__env->yieldContent('content'); ?>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo e(date('Y')); ?> Cyber Infinity. All rights reserved.</p>
        </div>
    </footer>

    <!-- Add any additional scripts here -->

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\cyber-infinity\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>