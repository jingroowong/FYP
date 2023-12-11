<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(mix('css/app.css')); ?>" rel="stylesheet">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
</head>

<body class="d-flex">
    <!-- Include the sidebar -->
    <?php echo $__env->make('layouts.adminSidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Main content area -->
    <div id="app" class="flex-grow-1">
        <main class="py-4">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
        <!-- Include the footer -->
        <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/layouts/adminApp.blade.php ENDPATH**/ ?>