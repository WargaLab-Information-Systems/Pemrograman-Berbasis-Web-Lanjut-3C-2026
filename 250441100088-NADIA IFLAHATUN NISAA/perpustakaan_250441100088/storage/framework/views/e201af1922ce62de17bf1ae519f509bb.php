<?php $__env->startSection('title', 'Rekomendasi Buku — Lentera Aksara'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container page">

        <div class="page-heading">
            <div class="page-heading-text">
                <span class="eyebrow">Rekomendasi</span>
                <h1>Buku yang layak kamu baca berikutnya.</h1>
                <p>
                    Pilihan buku dengan cerita tentang kehidupan,
                    dan perjalanan menemukan arah sendiri — dipilih untuk jadi referensi bacaanmu.
                </p>
            </div>

            <dl class="page-stats">
                <div>
                    <dt>Buku</dt>
                    <dd><?php echo e(count($buku)); ?></dd>
                </div>
                <div>
                    <dt>Kategori</dt>
                    <dd><?php echo e(collect($buku)->pluck('kategori')->unique()->count()); ?></dd>
                </div>
            </dl>
        </div>

        <div class="daftar-buku">
            <?php $__currentLoopData = $buku; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal39ca9bb9f2b236cda36185e35b572af6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal39ca9bb9f2b236cda36185e35b572af6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buku-card','data' => ['nomor' => $item['id'],'judul' => $item['judul'],'penulis' => $item['penulis'],'tahun' => $item['tahun'],'kategori' => $item['kategori'],'sampul' => $item['sampul'] ?? null,'url' => route('buku.show', $item['id'])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buku-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['nomor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['id']),'judul' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['judul']),'penulis' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['penulis']),'tahun' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['tahun']),'kategori' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['kategori']),'sampul' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['sampul'] ?? null),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('buku.show', $item['id']))]); ?>
                     <?php $__env->slot('label', null, []); ?> <?php echo e($item['kategori']); ?> <?php $__env->endSlot(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal39ca9bb9f2b236cda36185e35b572af6)): ?>
<?php $attributes = $__attributesOriginal39ca9bb9f2b236cda36185e35b572af6; ?>
<?php unset($__attributesOriginal39ca9bb9f2b236cda36185e35b572af6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal39ca9bb9f2b236cda36185e35b572af6)): ?>
<?php $component = $__componentOriginal39ca9bb9f2b236cda36185e35b572af6; ?>
<?php unset($__componentOriginal39ca9bb9f2b236cda36185e35b572af6); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\perpustakaan_250441100088\resources\views/buku/index.blade.php ENDPATH**/ ?>