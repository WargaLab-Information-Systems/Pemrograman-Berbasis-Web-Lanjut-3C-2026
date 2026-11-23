<?php $__env->startSection('title', 'Beranda — Lentera Aksara'); ?>

<?php $__env->startSection('content'); ?>
    <section class="hero-slider">
        <div class="hero-pattern" aria-hidden="true"></div>

        <div class="container hero-inner">
            <div class="hero-stage">
                <div class="slide is-active">
                    <p class="lead-in">Selamat datang di</p>
                    <h2>Lentera Aksara</h2>
                    <p class="hero-text">
                        Kumpulan rekomendasi novel dan cerita panjang tentang
                        perjalanan, pilihan, dan keberanian
                        untuk membantumu menemukan bacaan berikutnya.
                    </p>
                    <a href="<?php echo e(route('buku.index')); ?>" class="btn" style="margin-top: 28px;">Lihat Rekomendasi</a>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\perpustakaan_250441100088\resources\views/home.blade.php ENDPATH**/ ?>