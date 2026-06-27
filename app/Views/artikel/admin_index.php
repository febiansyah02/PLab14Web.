<?= $this->include('template/admin_header'); ?>

<div style="width: 100%; max-width: 1100px; margin: 40px auto; padding: 20px;">
    <h2>Manajemen Artikel</h2>

    <div style="background: #ffffff; padding: 20px; border-radius: 10px; border: 1px solid #e9ecef; margin-bottom: 20px;">
        <form id="search-form" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="q" id="search-box" placeholder="Cari judul..." style="flex: 1; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
            
            <select name="kategori_id" id="category-filter" style="padding: 8px; border-radius: 5px;">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
                <?php endforeach; ?>
            </select>

            <select name="sort" id="sort-filter" style="padding: 8px; border-radius: 5px;">
                <option value="id">Terbaru</option>
                <option value="judul">Judul (A-Z)</option>
            </select>

            <input type="submit" value="Cari" style="background: #5b9bd5; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer;">
        </form>
    </div>

    <div id="loading" style="display: none; text-align: center; color: #5b9bd5; margin-bottom: 10px;">Sedang mengambil data...</div>
    <div id="article-container"></div>
    <div id="pagination-container" style="text-align: center; margin-top: 20px;"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script>
$(document).ready(function() {
    const fetchData = (url) => {
        $('#loading').show();
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            success: function(data) {
                $('#loading').hide();
                renderArticles(data.artikel);
                $('#pagination-container').html(data.pager);
            }
        });
    };

    const renderArticles = (articles) => {
        let html = '<table border="1" width="100%" style="border-collapse: collapse;">';
        html += '<thead style="background: #5b9bd5; color: white;"><tr><th>ID</th><th>Judul</th><th>Kategori</th><th>Aksi</th></tr></thead><tbody>';
        
        if (articles.length > 0) {
            articles.forEach(article => {
                html += `<tr>
                    <td align="center">${article.id}</td>
                    <td>${article.judul}</td>
                    <td align="center">${article.nama_kategori}</td>
                    <td align="center">
                        <a href="/admin/artikel/edit/${article.id}">Ubah</a> | 
                        <a href="/admin/artikel/delete/${article.id}" onclick="return confirm('Hapus?')">Hapus</a>
                    </td>
                </tr>`;
            });
        } else {
            html += '<tr><td colspan="4" align="center">Data tidak ditemukan.</td></tr>';
        }
        html += '</tbody></table>';
        $('#article-container').html(html);
    };

    // Handler Filter Kategori & Sortir (Otomatis Cari)
    $('#category-filter, #sort-filter').on('change', function() {
        $('#search-form').trigger('submit');
    });

    $('#search-form').on('submit', function(e) { 
        e.preventDefault();
        const q = $('#search-box').val();
        const k = $('#category-filter').val();
        const s = $('#sort-filter').val();
        fetchData(`<?= base_url('admin/artikel'); ?>?q=${q}&kategori_id=${k}&sort=${s}`);
    });

    $(document).on('click', '#pagination-container a', function(e) {
        e.preventDefault();
        fetchData($(this).attr('href'));
    });

    fetchData('<?= base_url('admin/artikel'); ?>');
});
</script>

<?= $this->include('template/admin_footer'); ?>