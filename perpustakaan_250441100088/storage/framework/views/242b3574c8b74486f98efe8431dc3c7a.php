<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $__env->yieldContent('title', 'Lentera Aksara'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
</head>
<body>

    <header class="site-header">
        <div class="container nav-wrap">
            <a href="<?php echo e(route('home')); ?>" class="brand">
                <span class="brand-mark">LA</span>
                <span class="brand-text">
                    <strong>Lentera Aksara</strong>
                    <small>Rekomendasi bacaan pilihan</small>
                </span>
            </a>

            <nav class="main-nav">
                <a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'is-active' : ''); ?>">Beranda</a>
            </nav>
        </div>
    </header>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer>
        <div class="container footer-wrap">
            <span>Lentera Aksara</span>
            <span>© 2026 &middot; Rekomendasi bacaan untuk kamu pilih berikutnya.</span>
        </div>
    </footer>

</body>
</html>
<?php /**PATH C:\laragon\www\perpustakaan_250441100088\resources\views/layouts/app.blade.php ENDPATH**/ ?>