<?= $this->extend('layout/main') ?> <?= $this->section('content') ?>
    <h1><?= $title; ?></h1>
    <hr>

    <?php if($artikel): ?>
        <?php foreach($artikel as $row): ?>
            <article class="entry">
                <h2>
                    <a href="<?= base_url('/artikel/' . $row['slug']); ?>">
                        <?= $row['judul']; ?>
                    </a>
                </h2>
                <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= $row['judul']; ?>" style="width:200px;">
                <p><?= substr($row['isi'], 0, 200); ?>...</p>
            </article>
            <hr class="divider" />
        <?php endforeach; ?>
    <?php else: ?>
        <article class="entry">
            <h2>Belum ada data artikel.</h2>
        </article>
    <?php endif; ?>
    
<?= $this->endSection() ?>