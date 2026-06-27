<h3>Artikel Terkini</h3>
<ul class="widget-list">
    <?php if (!empty($artikel) && is_array($artikel)): ?>
        <?php foreach ($artikel as $row): ?>
            <li>
                <a href="<?= base_url('/artikel/' . $row['slug']); ?>">
                    <?= esc($row['judul']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>
            <p>Belum ada artikel terbaru.</p>
        </li>
    <?php endif; ?>
</ul>