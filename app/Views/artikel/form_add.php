<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<form action="" method="post" enctype="multipart/form-data">
    <p>
        <label for="judul">Judul</label><br>
        <input type="text" name="judul" id="judul" required style="width: 100%; padding: 8px;">
    </p>
    
    <p>
        <label for="id_kategori">Kategori</label><br>
        <select name="id_kategori" id="id_kategori" required style="width: 100%; padding: 8px;">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label for="gambar">Upload Gambar</label><br>
        <input type="file" name="gambar" id="gambar"> </p>

    <p>
        <label for="isi">Isi Artikel</label><br>
        <textarea name="isi" id="isi" cols="50" rows="10" style="width: 100%; padding: 8px;"></textarea>
    </p>

    <p>
        <input type="submit" value="Kirim" class="btn btn-large" style="padding: 10px 20px; background-color: #2c71b8; color: white; border: none; cursor: pointer;">
    </p>
</form>

<?= $this->include('template/admin_footer'); ?>