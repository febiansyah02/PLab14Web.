<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<form action="" method="post">
    <p>
        <label for="judul">Judul</label><br>
        <input type="text" name="judul" id="judul" value="<?= $artikel['judul']; ?>" 
               style="width: 100%; padding: 8px; margin-top: 5px;">
    </p>

    <p>
        <label for="id_kategori">Kategori</label><br>
        <select name="id_kategori" id="id_kategori" required 
                style="width: 100%; padding: 8px; margin-top: 5px;">
            <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id_kategori']; ?>" 
                    <?= ($artikel['id_kategori'] == $k['id_kategori']) ? 'selected' : ''; ?>>
                    <?= $k['nama_kategori']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label for="isi">Isi Artikel</label><br>
        <textarea name="isi" id="isi" cols="50" rows="10" 
                  style="width: 100%; padding: 8px; margin-top: 5px;"><?= $artikel['isi']; ?></textarea>
    </p>

    <p>
        <input type="submit" value="Update" class="btn btn-large" 
               style="padding: 10px 20px; background-color: #2c71b8; color: white; border: none; cursor: pointer;">
    </p>
</form>

<?= $this->include('template/admin_footer'); ?>