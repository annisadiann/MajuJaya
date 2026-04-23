<!DOCTYPE html>
<html>
<head>
  <title>Daftar Barang - Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 1050px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; margin-bottom: 20px; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
    .tab-nav { display: flex; gap: 0; margin-bottom: 25px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; }
    .tab-btn { flex: 1; padding: 12px 20px; border: none; cursor: pointer; font-size: 15px; font-weight: bold; transition: all .2s; }
    .tab-btn.pembelian { background: #f8f9fa; color: #888; border-right: 1px solid #ddd; }
    .tab-btn.penjualan { background: #f8f9fa; color: #888; }
    .tab-btn.pembelian.active { background: #2980b9; color: white; }
    .tab-btn.penjualan.active { background: #27ae60; color: white; }
    .tab-btn:hover:not(.active) { background: #e9ecef; }
    table { width: 100%; border-collapse: collapse; }
    th { padding: 12px; text-align: center; color: white; font-size: 14px; }
    .th-beli { background: #2980b9; }
    .th-jual { background: #27ae60; }
    td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: middle; }
    tr:hover td { background: #f8f9fa; }
    tr.row-aktif td { background: #eaf4fb !important; }
    .btn-tambah { display: inline-block; background: #e67e22; color: white; padding: 8px 18px; border-radius: 5px; text-decoration: none; font-size: 14px; }
    .btn-hapus { background: #e74c3c; color: white; padding: 5px 12px; border-radius: 5px; border: none; cursor: pointer; font-size: 13px; width: 100%; }
    .btn-hapus:hover { background: #c0392b; }
    .btn-tracking { background: #438b57; color: white; padding: 8px 18px; border-radius: 5px; text-decoration: none; font-size: 14px; }
    .btn-simpan-beli { background: #2980b9; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 15px; }
    .btn-simpan-beli:hover { background: #2471a3; }
    .btn-proses-jual { background: #27ae60; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 15px; }
    .btn-proses-jual:hover { background: #1e8449; }
    .link-riwayat { color: #3498db; text-decoration: none; font-weight: bold; font-size: 14px; }
    .link-riwayat:hover { text-decoration: underline; }
    .total-box { background: #f8f9fa; border-radius: 8px; padding: 12px 18px; text-align: right; margin-top: 15px; font-size: 15px; }
    .total-box span { font-size: 20px; font-weight: bold; color: #27ae60; }
    input[type=number] { width: 90px; padding: 6px 8px; border: 1px solid #ddd; border-radius: 5px; text-align: center; font-size: 14px; }
    .stok-low { color: #e74c3c; font-weight: bold; }
    .footer-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; flex-wrap: wrap; gap: 10px; }
    .pagination { display: flex; gap: 5px; margin-top: 15px; flex-wrap: wrap; }
    .pagination a { padding: 8px 14px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #3498db; font-size: 14px; }
    .pagination a:hover { background: #3498db; color: white; }
    .pagination a.active { background: #3498db; color: white; border-color: #3498db; font-weight: bold; }
    .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .alert-error   { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .harga-info { font-size: 12px; color: #555; }
    .harga-info span { color: #2980b9; font-weight: bold; }
    .harga-info span.jual { color: #27ae60; }
    .harga-inputs { display: none; margin-top: 8px; }
    .harga-inputs.visible { display: block; }
    .harga-inputs .input-row { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
    .harga-inputs .input-group { text-align: center; }
    .harga-inputs .input-group label { font-size: 11px; color: #888; display: block; margin-bottom: 3px; }
    .harga-inputs .input-group input { width: 110px; }
    .info-banner { background: #eaf4fb; border-left: 4px solid #2980b9; padding: 10px 14px; border-radius: 5px; margin-bottom: 18px; font-size: 13px; color: #2c3e50; }
  </style>
</head>
<body>
<div class="container">

  <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px; margin-bottom:10px;">
    <div style="background:#f0f2f5; border:1px solid #dde; border-radius:20px; padding:6px 14px; font-size:13px; color:#555;">
      <strong style="color:#2c3e50;"><?= htmlspecialchars($nama) ?></strong>
      <span style="background:#3498db; color:white; border-radius:10px; padding:2px 8px; font-size:11px; margin-left:5px;"><?= $role ?></span>
    </div>
    <a href="<?= site_url('auth/logout') ?>" style="background:#e74c3c; color:white; padding:7px 15px; border-radius:20px; text-decoration:none; font-size:13px;">Logout</a>
  </div>

  <h2>Daftar Barang Toko Maju Jaya</h2>

  <?php if ($this->input->get('success')): ?>
    <div class="alert-success"><?= $this->input->get('success') ?></div>
  <?php endif; ?>
  <?php if ($this->input->get('error')): ?>
    <div class="alert-error"><?= $this->input->get('error') ?></div>
  <?php endif; ?>

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
    <a href="<?= site_url('barang/tambah') ?>" class="btn-tambah">+ Tambah Barang</a>
    <a href="<?= site_url('stok') ?>" class="btn-tracking">Tracking Stok</a>
  </div>

  <div class="tab-nav">
    <button class="tab-btn pembelian active" onclick="gantiTab('pembelian')">Pembelian (Restok dari Supplier)</button>
    <button class="tab-btn penjualan" onclick="gantiTab('penjualan')">Penjualan ke Pelanggan</button>
  </div>

  <div id="tab-pembelian">
    <div class="info-banner">
      Isi <strong>Jumlah Dibeli</strong> untuk menampilkan input harga. Kosongkan harga jika tidak ada perubahan.
    </div>
    <form action="<?= site_url('barang/simpan_pembelian') ?>" method="POST">
    <table>
      <tr>
        <th class="th-beli" style="text-align:left;">Nama Barang</th>
        <th class="th-beli">Stok Saat Ini</th>
        <th class="th-beli">Jumlah Dibeli</th>
        <th class="th-beli">Harga Beli & Jual</th>
        <th class="th-beli">Aksi</th>
      </tr>
      <?php foreach ($rows as $idx => $row): ?>
      <tr id="row-<?= $idx ?>">
        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
        <td style="text-align:center;" class="<?= $row['stok'] < 10 ? 'stok-low' : '' ?>">
          <?= $row['stok'] ?>
        </td>
        <td style="text-align:center;">
          <input type="number" name="jumlah[<?= $row['id_barang'] ?>]"
                 min="0" value="0" class="input-jumlah"
                 data-idx="<?= $idx ?>" oninput="toggleHarga(this)">
        </td>
        <td style="text-align:center;">
          <div class="harga-info">
            Beli: <span>Rp <?= number_format($row['harga_beli'] ?? 0, 0, ',', '.') ?></span>
            &nbsp;·&nbsp;
            Jual: <span class="jual">Rp <?= number_format($row['harga_jual'] ?? 0, 0, ',', '.') ?></span>
          </div>
          <div class="harga-inputs" id="harga-<?= $idx ?>">
            <div class="input-row">
              <div class="input-group">
                <label>Harga Beli Baru</label>
                <input type="number" name="harga_beli[<?= $row['id_barang'] ?>]"
                       min="0" value="" placeholder="Kosongkan jika sama">
              </div>
              <div class="input-group">
                <label>Harga Jual Baru</label>
                <input type="number" name="harga_jual[<?= $row['id_barang'] ?>]"
                       min="0" value="" placeholder="Kosongkan jika sama">
              </div>
            </div>
          </div>
        </td>
        <td style="text-align:center;">
          <button type="button" class="btn-hapus"
                  onclick="konfirmasiHapus(<?= $row['id_barang'] ?>, '<?= htmlspecialchars($row['nama_barang']) ?>')">
            Hapus
          </button>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <div class="footer-bar">
      <a class="link-riwayat" href="<?= site_url('pembelian') ?>">Lihat Riwayat Pembelian</a>
      <button type="submit" class="btn-simpan-beli">Simpan Pembelian</button>
    </div>
    </form>
  </div>

  <div id="tab-penjualan" style="display:none;">
    <form action="<?= site_url('barang/proses_penjualan') ?>" method="POST">
    <table>
      <tr>
        <th class="th-jual" style="text-align:left;">Nama Barang</th>
        <th class="th-jual">Harga Jual</th>
        <th class="th-jual">Stok Tersedia</th>
        <th class="th-jual">Jumlah Dijual</th>
        <th class="th-jual">Subtotal</th>
      </tr>
      <?php foreach ($rows as $i => $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
        <td style="text-align:center;">Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
        <td style="text-align:center;" class="<?= $row['stok'] < 10 ? 'stok-low' : '' ?>"><?= $row['stok'] ?></td>
        <td style="text-align:center;">
          <input type="number" name="jumlah[<?= $row['id_barang'] ?>]"
                 min="0" max="<?= $row['stok'] ?>" value="0"
                 data-harga="<?= $row['harga_jual'] ?>"
                 class="input-jual" id="jual-<?= $i ?>"
                 oninput="hitungTotal()">
        </td>
        <td style="text-align:right;" id="sub-<?= $i ?>">-</td>
      </tr>
      <?php endforeach; ?>
    </table>
    <div class="total-box">
      Total Transaksi: <span id="total-display">Rp 0</span>
    </div>
    <div class="footer-bar">
      <a class="link-riwayat" href="<?= site_url('riwayat') ?>">Lihat Riwayat Transaksi</a>
      <button type="submit" class="btn-proses-jual">Proses Penjualan</button>
    </div>
    </form>
  </div>

  <?php if ($totalHalaman > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="?page=<?= $page-1 ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
      <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalHalaman): ?>
      <a href="?page=<?= $page+1 ?>">Next »</a>
    <?php endif; ?>
  </div>
  <span style="color:#888; font-size:13px; margin-top:8px; display:block;">Halaman <?= $page ?> dari <?= $totalHalaman ?></span>
  <?php endif; ?>

  <div id="popup-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;">
    <div style="background:white; border-radius:10px; padding:30px; max-width:400px; margin:150px auto; text-align:center; box-shadow:0 5px 20px rgba(0,0,0,0.3);">
      <h3 style="color:#2c3e50; margin-bottom:10px;">Hapus Barang?</h3>
      <p style="color:#666; margin-bottom:20px;">Yakin ingin menghapus <strong id="popup-nama"></strong>?<br>Data tidak bisa dikembalikan.</p>
      <form method="POST" action="<?= site_url('barang/hapus') ?>">
        <input type="hidden" name="id_barang" id="popup-id">
        <button type="submit" style="background:#e74c3c; color:white; padding:10px 25px; border:none; border-radius:5px; cursor:pointer; font-size:15px; margin-right:10px;">Ya, Hapus</button>
        <button type="button" onclick="tutupPopup()" style="background:#95a5a6; color:white; padding:10px 25px; border:none; border-radius:5px; cursor:pointer; font-size:15px;">Batal</button>
      </form>
    </div>
  </div>

</div>
<script>
  function toggleHarga(input) {
    const idx = input.dataset.idx;
    const jumlah = parseInt(input.value) || 0;
    const hargaEl = document.getElementById('harga-' + idx);
    const row = document.getElementById('row-' + idx);
    if (jumlah > 0) { hargaEl.classList.add('visible'); row.classList.add('row-aktif'); }
    else { hargaEl.classList.remove('visible'); row.classList.remove('row-aktif'); }
  }
  function gantiTab(tab) {
    document.getElementById('tab-pembelian').style.display = tab === 'pembelian' ? 'block' : 'none';
    document.getElementById('tab-penjualan').style.display = tab === 'penjualan' ? 'block' : 'none';
    document.querySelector('.tab-btn.pembelian').classList.toggle('active', tab === 'pembelian');
    document.querySelector('.tab-btn.penjualan').classList.toggle('active', tab === 'penjualan');
  }
  function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.input-jual').forEach(function(input, i) {
      const qty = parseInt(input.value) || 0;
      const harga = parseInt(input.dataset.harga) || 0;
      const sub = qty * harga;
      total += sub;
      const subEl = document.getElementById('sub-' + i);
      if (subEl) subEl.textContent = qty > 0 ? 'Rp ' + sub.toLocaleString('id-ID') : '-';
    });
    document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
  }
  function konfirmasiHapus(id, nama) {
    document.getElementById('popup-id').value = id;
    document.getElementById('popup-nama').textContent = '"' + nama + '"';
    document.getElementById('popup-overlay').style.display = 'block';
  }
  function tutupPopup() {
    document.getElementById('popup-overlay').style.display = 'none';
  }
  document.getElementById('popup-overlay').addEventListener('click', function(e) {
    if (e.target === this) tutupPopup();
  });
  const urlParams = new URLSearchParams(window.location.search);
  gantiTab(urlParams.get('tab') === 'penjualan' ? 'penjualan' : 'pembelian');
  if (window.location.search.includes('success') || window.location.search.includes('error')) {
    window.history.replaceState({}, document.title, window.location.pathname);
  }
</script>
</body>
</html>