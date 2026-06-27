<?= $this->include('template/header'); ?>

<div style="padding: 20px;">
    <h1>Data Artikel (AJAX)</h1>
    <table border="1" width="100%" id="artikelTable" style="border-collapse: collapse;">
        <thead style="background: #5b9bd5; color: white;">
            <tr>
                <th width="50">ID</th>
                <th>Judul</th>
                <th width="100">Aksi</th>
            </tr>
        </thead>
        <tbody id="artikelBody">
            <tr>
                <td colspan="3" align="center">Sedang memuat data...</td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Fungsi untuk memuat data [cite: 106-107]
    function loadData() {
        $('#artikelBody').html('<tr><td colspan="3" align="center">Sedang memuat data...</td></tr>');
        
        $.ajax({
            url: "<?= base_url('ajax/getData'); ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                var html = "";
                if (data.length > 0) {
                    // Looping data JSON [cite: 121-122]
                    $.each(data, function(i, item) {
                        html += '<tr>' +
                                '<td align="center">' + item.id + '</td>' +
                                '<td>' + item.judul + '</td>' +
                                '<td align="center">' +
                                    '<span class="btn-delete" data-id="' + item.id + '" style="color:red; cursor:pointer; text-decoration:underline;">Hapus</span>' +
                                '</td>' +
                                '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="3" align="center">Data kosong.</td></tr>';
                }
                $('#artikelBody').html(html);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + error);
                $('#artikelBody').html('<tr><td colspan="3" align="center" style="color:red;">Gagal memuat data!</td></tr>');
            }
        });
    }

    // Panggil fungsi muat data
    loadData();

    // Fungsi Hapus data [cite: 141-143]
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        if (confirm("Hapus artikel ID " + id + "?")) {
            $.ajax({
                url: "<?= base_url('ajax/delete'); ?>/" + id,
                type: "DELETE",
                success: function() {
                    loadData(); // Reload tabel otomatis [cite: 152-153]
                }
            });
        }
    });
});
</script>

<?= $this->include('template/footer'); ?>