<!DOCTYPE html>
<html>
<head>
  <title>Riwayat Transaksi - Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 960px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; margin-bottom: 20px; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
    .filter-box { background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
    .filter-box h3 { margin-bottom: 15px; color: #555; font-size: 15px; }
    .filter-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label { font-size: 13px; font-weight: bold; color: #555; }
    .filter-group input, .filter-group select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
    .btn-filter { background: #3498db; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
    .btn-reset { background: #95a5a6; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #3498db; color: white; padding: 12px; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid #eee; }
    tr:hover td { background: #f8f9fa; }
    .total-cell { font-weight: bold; color: #27ae60; }
    .summary { background: #eaf6ff; border: 1px solid #bee3f8; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; display: flex; gap: 30px; }
    .summary-item span { font-size: 13px; color: #666; display: block; }
    .summary-item strong { font-size: 18px; color: #2c3e50; }
    .pagination { display: flex; gap: 5px; margin-top: 20px; flex-wrap: wrap; }
    .pagination a { padding: 8px 14px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #3498db; font-size: 14px; }
    .pagination a:hover, .pagination a.active { background: #3498db; color: white; border-color: #3498db; }
    .btn-retur  { background: #e67e22; color: white; padding: 6px 14px; border-radius: 5px; border: none; cursor: pointer; font-size: 13px; }
    .btn-detail { background: #3498db; color: white; padding: 6px 14px; border-radius: 5px; border: none; cursor: pointer; font-size: 13px; margin-right: 5px; text-decoration: none; display: inline-block; }
    .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
  </style>
</head>
<body>
<div class="container">
  <h2>Riwayat Transaksi</h2>

  <?php if ($error): ?>
    <div class="alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="filter-box">
    <h3>Filter Transaksi</h3>
    <form method="GET" action="<?= site_url('riwayat') ?>" onsubmit="return validasiFilter()">
      <div class="filter-row">
        <div class="filter-group">
          <label>Dari Tanggal</label>
          <input type="date" name="dari" id="dari" value="<?= htmlspecialchars($dari) ?>" onchange="updateMin()">
        </div>
        <div class="filter-group">
          <label>Sampai Tanggal</label>
          <input type="date" name="sampai" id="sampai" value="<?= htmlspecialchars($sampai) ?>" onchange="updateMax()">
        </div>
        <div class="filter-group">
          <label>Nama Barang</label>
          <select name="barang">
            <option value="">-- Semua Barang --</option>
            <?php foreach ($daftar_barang as $b): ?>
              <option value="<?= htmlspecialchars($b['nama_barang']) ?>"
                <?= $barang == $b['nama_barang'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($b['nama_barang']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn-filter">Filter</button>
        <a href="<?= site_url('riwayat') ?>" class="btn-reset">✖ Reset</a>
      </div>
    </form>
  </div>

  <div class="summary">
    <div class="summary-item">
      <span>Total Transaksi</span>
      <strong><?= $total_data ?> transaksi</strong>
    </div>
    <div class="summary-item">
      <span>Total Pendapatan</span>
      <strong>Rp <?= number_format($grand_total, 0, ',', '.') ?></strong>
    </div>
  </div>

  <table>
    <tr>
      <th>No. Transaksi</th>
      <th>Tanggal</th>
      <th>Jumlah Item</th>
      <th>Total Harga</th>
      <th>Aksi</th>
    </tr>
    <?php if (empty($transaksi)): ?>
      <tr><td colspan="5" style="text-align:center; color:#888; padding:30px;">Tidak ada transaksi ditemukan.</td></tr>
    <?php else: ?>
      <?php foreach ($transaksi as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['no_transaksi']) ?></td>
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['jumlah'] ?> item</td>
        <td class="total-cell">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
        <td>
          <a href="<?= site_url('invoice?no='.urlencode($row['no_transaksi'])) ?>" class="btn-detail">Invoice</a>
          <button class="btn-detail" onclick="bukaDetail('<?= $row['no_transaksi'] ?>')">Detail</button>
          <?php if ($row['bisa_retur'] && $row['masa_berlaku']): ?>
            <button class="btn-retur" onclick="bukaRetur('<?= $row['no_transaksi'] ?>', '<?= $row['tanggal'] ?>')">Retur</button>
          <?php elseif (!$row['bisa_retur']): ?>
            <span style="color:#888; font-size:13px;">Sudah diretur</span>
          <?php else: ?>
            <span style="color:#e74c3c; font-size:13px;">Kadaluarsa</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>

  <?php if ($total_hal > 1): ?>
  <div class="pagination">
    <?php
      $q = '';
      if ($dari)   $q .= '&dari='.$dari;
      if ($sampai) $q .= '&sampai='.$sampai;
      if ($barang) $q .= '&barang='.urlencode($barang);
    ?>
    <?php if ($page > 1): ?>
      <a href="<?= site_url('riwayat?page='.($page-1).$q) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_hal; $i++): ?>
      <a href="<?= site_url('riwayat?page='.$i.$q) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $total_hal): ?>
      <a href="<?= site_url('riwayat?page='.($page+1).$q) ?>">Next »</a>
    <?php endif; ?>
  </div>
  <span style="color:#888; font-size:13px; margin-top:8px; display:block">Halaman <?= $page ?> dari <?= $total_hal ?></span>
  <?php endif; ?>

  <div style="margin-top:20px;">
    <a href="<?= site_url('barang?tab=penjualan') ?>" style="color:#3498db; text-decoration:none; font-weight:bold;">← Kembali ke Penjualan</a>
    <a href="<?= site_url('retur') ?>" style="margin-left:20px; color:#e74c3c; text-decoration:none; font-weight:bold;">Lihat Riwayat Retur</a>
  </div>
</div>

<div id="popup-detail" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;">
  <div style="background:white; border-radius:10px; padding:30px; max-width:500px; margin:100px auto; box-shadow:0 5px 20px rgba(0,0,0,0.3);">
    <h3 style="color:#2c3e50; margin-bottom:15px;">Detail Transaksi</h3>
    <p style="margin-bottom:15px; color:#888; font-size:14px;">No. Transaksi: <strong id="detail-no"></strong></p>
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <th style="background:#3498db; color:white; padding:8px; text-align:left;">Nama Barang</th>
        <th style="background:#3498db; color:white; padding:8px;">Jumlah</th>
        <th style="background:#3498db; color:white; padding:8px;">Harga Satuan</th>
        <th style="background:#3498db; color:white; padding:8px;">Subtotal</th>
      </tr>
      <tbody id="detail-body"></tbody>
    </table>
    <br>
    <button onclick="tutupDetail()" style="width:100%; background:#95a5a6; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">Tutup</button>
  </div>
</div>

<div id="popup-retur" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;">
  <div style="background:white; border-radius:10px; padding:30px; max-width:400px; margin:100px auto; box-shadow:0 5px 20px rgba(0,0,0,0.3);">
    <h3 style="color:#2c3e50; margin-bottom:15px;">Form Retur</h3>
    <form method="POST" action="<?= site_url('riwayat/simpan_retur') ?>">
      <input type="hidden" name="no_transaksi" id="retur-no">
      <input type="hidden" name="tanggal_beli" id="retur-tanggal">
      <input type="hidden" name="harga_satuan" id="retur-harga">
      <input type="hidden" name="jumlah_beli"  id="retur-maks">
      <input type="hidden" name="nama_barang"  id="retur-nama">

      <p style="margin-bottom:5px; font-weight:bold; color:#555;">Pilih Barang</p>
      <select id="retur-select" style="width:100%; padding:8px; margin-bottom:15px; border:1px solid #ddd; border-radius:5px;" onchange="updateReturInfo(this)"></select>

      <p style="margin-bottom:5px; font-weight:bold; color:#555;">Jumlah Retur <small id="retur-sisa-label" style="color:#888;"></small></p>
      <input type="number" name="jumlah_retur" id="retur-jumlah" min="1" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:5px;" required>

      <div style="display:flex; gap:10px;">
        <button type="submit" style="flex:1; background:#e67e22; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">Konfirmasi Retur</button>
        <button type="button" onclick="tutupRetur()" style="flex:1; background:#95a5a6; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">Batal</button>
      </div>
    </form>
  </div>
</div>

<script>
var detailData = <?= json_encode($detail_all) ?>;

function bukaDetail(no) {
  document.getElementById('detail-no').textContent = no;
  var items = detailData[no] || [];
  var html = '';
  items.forEach(function(item) {
    html += '<tr>' +
      '<td style="padding:8px; border-bottom:1px solid #eee;">' + item.nama_barang + '</td>' +
      '<td style="padding:8px; border-bottom:1px solid #eee; text-align:center;">' + item.jumlah + '</td>' +
      '<td style="padding:8px; border-bottom:1px solid #eee; text-align:center;">Rp ' + parseInt(item.harga_satuan).toLocaleString('id-ID') + '</td>' +
      '<td style="padding:8px; border-bottom:1px solid #eee; font-weight:bold; color:#27ae60; text-align:center;">Rp ' + parseInt(item.total_harga).toLocaleString('id-ID') + '</td>' +
      '</tr>';
  });
  document.getElementById('detail-body').innerHTML = html;
  document.getElementById('popup-detail').style.display = 'block';
}

function tutupDetail() {
  document.getElementById('popup-detail').style.display = 'none';
}

function bukaRetur(no, tanggal) {
  document.getElementById('retur-no').value      = no;
  document.getElementById('retur-tanggal').value = tanggal;

  var items  = detailData[no] || [];
  var select = document.getElementById('retur-select');
  select.innerHTML = '';
  items.forEach(function(item) {
    var sisa = item.jumlah - item.sudah_retur;
    if (sisa > 0) {
      var opt = document.createElement('option');
      opt.value = JSON.stringify({nama: item.nama_barang, harga: item.harga_satuan, maks: sisa});
      opt.textContent = item.nama_barang + ' (sisa: ' + sisa + ')';
      select.appendChild(opt);
    }
  });

  updateReturInfo(select);
  document.getElementById('popup-retur').style.display = 'block';
}

function updateReturInfo(select) {
  if (!select.value) return;
  var data = JSON.parse(select.value);
  document.getElementById('retur-nama').value  = data.nama;
  document.getElementById('retur-harga').value = data.harga;
  document.getElementById('retur-maks').value  = data.maks;
  document.getElementById('retur-jumlah').max  = data.maks;
  document.getElementById('retur-jumlah').value = '';
  document.getElementById('retur-sisa-label').textContent = '(maks: ' + data.maks + ')';
}

function tutupRetur() {
  document.getElementById('popup-retur').style.display = 'none';
}

document.getElementById('popup-detail').addEventListener('click', function(e) { if (e.target===this) tutupDetail(); });
document.getElementById('popup-retur').addEventListener('click',  function(e) { if (e.target===this) tutupRetur(); });

function updateMin() {
  var dari = document.getElementById('dari').value;
  document.getElementById('sampai').min = dari;
  if (document.getElementById('sampai').value < dari) {
    document.getElementById('sampai').value = '';
  }
}

function updateMax() {
  var sampai = document.getElementById('sampai').value;
  document.getElementById('dari').max = sampai;
  if (document.getElementById('dari').value > sampai) {
    document.getElementById('dari').value = '';
  }
}

function validasiFilter() {
  var dari = document.getElementById('dari').value;
  var sampai = document.getElementById('sampai').value;
  if (dari && sampai && dari > sampai) {
    alert('Rentang tanggal tidak valid!');
    return false;
  }
  return true;
}
</script>
</body>
</html>