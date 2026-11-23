<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['nomor' => null, 'judul', 'penulis', 'tahun', 'kategori', 'url', 'sampul' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['nomor' => null, 'judul', 'penulis', 'tahun', 'kategori', 'url', 'sampul' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $adaSampul = $sampul && file_exists(public_path('images/' . $sampul));
?>

<article class="buku-card" data-kategori="<?php echo e($kategori); ?>">
    <?php if($adaSampul): ?>
        <a href="<?php echo e($url); ?>" class="card-cover">
            <img src="<?php echo e(asset('images/' . $sampul)); ?>" alt="Sampul buku <?php echo e($judul); ?>" loading="lazy">
        </a>
    <?php endif; ?>

    <div class="card-top">
        
        <span class="kategori"><?php echo e($label ?? $kategori); ?></span>

        <?php if($nomor): ?>
            <span class="card-no"><?php echo e(str_pad($nomor, 2, '0', STR_PAD_LEFT)); ?></span>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <h3><?php echo e($judul); ?></h3>
        <p class="penulis"><?php echo e($penulis); ?></p>
        <p class="tahun">Terbit <?php echo e($tahun); ?></p>

        
        <?php if($slot->isNotEmpty()): ?>
            <div class="card-extra"><?php echo e($slot); ?></div>
        <?php endif; ?>
    </div>

    <div class="card-action">
        <a href="<?php echo e($url); ?>" class="detail-btn">
            Lihat detail <span aria-hidden="true">→</span>
        </a>
    </div>
</article>
<?php /**PATH C:\laragon\www\perpustakaan_250441100088\resources\views/components/buku-card.blade.php ENDPATH**/ ?>