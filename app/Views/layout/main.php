<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'My Website' ?></title> [cite: 24]
    <link rel="stylesheet" href="<?= base_url('/style.css');?>"> [cite: 25]
</head>
<body>
    <div id="container">
        <header>
            <h1>Layout Sederhana</h1> [cite: 30]
        </header>
        <nav>
            <a href="<?= base_url('/'); ?>" class="active">Home</a> [cite: 33]
            [cite_start]<a href="<?= base_url('/artikel'); ?>">Artikel</a> [cite: 34]
            [cite_start]<a href="<?= base_url('/about'); ?>">About</a> [cite: 35]
            [cite_start]<a href="<?= base_url('/contact'); ?>">Kontak</a> [cite: 36]
        </nav>
        <section id="wrapper">
            <section id="main">
                [cite_start]<?= $this->renderSection('content') ?> [cite: 43]
            </section>
            <aside id="sidebar">
                [cite_start]<?= view_cell('App\Cells\ArtikelTerkini::render') ?> [cite: 46]
                <div class="widget-box">
                    [cite_start]<h3 class="title">Widget Header</h3> [cite: 48]
                    <ul>
                        [cite_start]<li><a href="#">Widget Link</a></li> [cite: 50]
                        [cite_start]<li><a href="#">Widget Link</a></li> [cite: 51]
                    </ul>
                </div>
                <div class="widget-box">
                    [cite_start]<h3 class="title">Widget Text</h3> [cite: 57]
                    [cite_start]<p>Vestibulum lorem elit, iaculis in nisl volutpat, vestibulum mi porta, nunc pretium ac.</p> [cite: 55, 56, 58]
                </div>
            </aside>
        </section>
        <footer>
            <p>&copy; [cite_start]2021 Universitas Pelita Bangsa</p> [cite: 65]
        </footer>
    </div>
</body>
</html>