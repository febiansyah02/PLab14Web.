<?= $this->include('template/header'); ?>

<div style="max-width: 900px; margin: 20px auto; padding: 20px; font-family: sans-serif;">
    <h1 style="border-bottom: 2px solid #5b9bd5; padding-bottom: 10px;"><?= $title; ?></h1>

    <?php if($artikel): foreach($artikel as $row): ?>
        <article style="display: flex; gap: 20px; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; align-items: flex-start;">
            
            <div style="flex: 0 0 250px;"> <?php if (!empty($row['gambar'])): ?>
                    <img src="<?= base_url('gambar/' . $row['gambar']); ?>" 
                         style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <?php else: ?>
                    <div style="width: 100%; height: 180px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #999;">
                        No Image
                    </div>
                <?php endif; ?>
            </div>

            <div style="flex: 1;">
                <h2 style="margin: 0 0 10px 0;">
                    <a href="<?= base_url('artikel/' . $row['slug']); ?>" style="text-decoration: none; color: #2c3e50;">
                        <?= $row['judul']; ?>
                    </a>
                </h2>
                <small style="background: #e7f1ff; color: #0d6efd; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 11px;">
                    <?= strtoupper($row['nama_kategori'] ?? 'UMUM'); ?>
                </small>
                <p style="color: #555; line-height: 1.6; margin-top: 10px;">
                    <?= substr(strip_tags($row['isi']), 0, 180); ?>...
                </p>
                <a href="<?= base_url('artikel/' . $row['slug']); ?>" style="color: #5b9bd5; font-weight: bold; text-decoration: none;">Baca Selengkapnya &rarr;</a>
            </div>

        </article>
    <?php endforeach; else: ?>
        <p>Belum ada artikel yang diterbitkan.</p>
    <?php endif; ?>
</div>

<?= $this->include('template/footer'); ?>