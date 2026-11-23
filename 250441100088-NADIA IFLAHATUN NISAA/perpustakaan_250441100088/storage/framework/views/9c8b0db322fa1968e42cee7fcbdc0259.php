<?php $__env->startSection('title', ($buku['judul'] ?? 'Detail Buku') . ' — Lentera Aksara'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container page">

        <a href="<?php echo e(route('buku.index')); ?>" class="back-link">← Kembali ke Rekomendasi</a>

        <?php if($buku): ?>
            <section class="detail-hero" data-kategori="<?php echo e($buku['kategori']); ?>">
                <div class="hero-pattern" aria-hidden="true"></div>

                <div class="detail-cover" aria-hidden="true">
                    <?php if(!empty($buku['sampul']) && file_exists(public_path('images/' . $buku['sampul']))): ?>
                        <img src="<?php echo e(asset('images/' . $buku['sampul'])); ?>" alt="">
                    <?php else: ?>
                        <span><?php echo e(Str::of($buku['judul'])->explode(' ')->map(fn($w) => Str::substr($w, 0, 1))->take(2)->implode('')); ?></span>
                    <?php endif; ?>
                </div>

                <div class="detail-hero-text">
                    <span class="badge"><?php echo e($buku['kategori']); ?></span>
                    <h1><?php echo e($buku['judul']); ?></h1>
                    <p class="detail-author"><?php echo e($buku['penulis']); ?></p>
                </div>

                <div class="detail-number">#<?php echo e(str_pad($buku['id'], 2, '0', STR_PAD_LEFT)); ?></div>
            </section>

            <section class="detail-content" data-kategori="<?php echo e($buku['kategori']); ?>">
                <div class="detail-card">
                    <span class="section-label">Tentang Buku</span>

                    <dl class="book-data">
                        <div>
                            <dt>Judul</dt>
                            <dd><?php echo e($buku['judul']); ?></dd>
                        </div>
                        <div>
                            <dt>Penulis</dt>
                            <dd><?php echo e($buku['penulis']); ?></dd>
                        </div>
                        <div>
                            <dt>Tahun Terbit</dt>
                            <dd><?php echo e($buku['tahun']); ?></dd>
                        </div>
                        <div>
                            <dt>Kategori</dt>
                            <dd><?php echo e($buku['kategori']); ?></dd>
                        </div>
                        <div>
                            <dt>ID Buku</dt>
                            <dd>#<?php echo e(str_pad($buku['id'], 3, '0', STR_PAD_LEFT)); ?></dd>
                        </div>
                    </dl>
                </div>

                <div class="detail-card synopsis-card">
                    <span class="section-label">Sinopsis</span>

                    <h2>Ringkasan cerita</h2>
                    <p><?php echo e($buku['sinopsis'] ?? 'Sinopsis untuk buku ini belum tersedia.'); ?></p>

                    <a href="<?php echo e(route('buku.index')); ?>" class="btn">Kembali ke Rekomendasi</a>
                </div>
            </section>
        <?php else: ?>
            <section class="empty-state">
                <span class="eyebrow">Buku tidak ditemukan</span>
                <h1>Data buku tidak tersedia.</h1>
                <p>Buku yang kamu cari belum ada di dalam koleksi.</p>
                <a href="<?php echo e(route('buku.index')); ?>" class="btn">Kembali ke Rekomendasi</a>
            </section>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\perpustakaan_250441100088\resources\views/buku/show.blade.php ENDPATH**/ ?>