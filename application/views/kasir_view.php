<!DOCTYPE html>
<html>
<head>
  <title>Kasir - Toko Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 950px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    h2 { color: #2c3e50; border-bottom: 3px solid #27ae60; padding-bottom: 10px; }
    .user-badge { background: #f0f2f5; border: 1px solid #dde; border-radius: 20px; padding: 6px 14px; font-size: 13px; color: #555; }
    .role-tag { background: #27ae60; color: white; border-radius: 10px; padding: 2px 8px; font-size: 11px; margin-left: 5px; }
    .btn-logout { background: #e74c3c; color: white; padding: 7px 15px; border-radius: 20px; text-decoration: none; font-size: 13px; margin-left: 10px; }
    .btn-logout:hover { background: #c0392b; }
    table { width: 100%; border-collapse: collapse; }
    th { padding: 12px; text-align: center; color: white; background: #27ae60; font-size: 14px; }
    td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; }
    tr:hover td { background: #f8f9fa; }
    tr.dipilih td { background: #eafaf1 !important; }
    input[type=number] { width: 90px; padding: 6px 8px; border: 1px solid #ddd; border-radius: 5px; text-align: center; font-size: 14px; }
    .stok-low { color: #e74c3c; font-weight: bold; }
    .total-box { background: #f8f9fa; border-radius: 8px; padding: 12px 18px; text-align: right; margin-top: 15px; font-size: 15px; }
    .total-box span { font-size: 20px; font-weight: bold; color: #27ae60; }
    .footer-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; }
    .btn-proses { background: #27ae60; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 15px; }
    .btn-proses:hover { background: #1e8449; }
    .alert-error   { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .link-riwayat { color: #3498db; text-decoration: none; font-weight: bold; font-size: 14px; }
    .search-box { margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
    .search-box input { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; width: 250px; }
    .search-box input:focus { outline: none; border-color: #27ae60; }
  </style>
</head>
<body>
<div class="container">
  <div class="topbar">
    <h2>Penjualan - Toko Maju Jaya</h2>
    <div style="display:flex; align-items:center; gap:10px;">
      <div class="user-badge">
        <strong><?= htmlspecialchars($this->session->userdata('nama')) ?></strong>
        <span class="role-tag"><?= $this->session->userdata('role') ?></span>
      </div>
      <a href="<?= site_url('auth/logout') ?>" class="btn-logout">Logout</a>
    </div>
  </div>

  <?php if ($error):   ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <div class="search-box">
    <input type="text" id="search-barang" placeholder="Cari nama barang..."
      oninput="liveSearch(this.value)">
    <span id="search-info" style="font-size:13px; color:#888;"></span>
  </div>

  <form action="<?= site_url('kasir/simpan') ?>" method="POST" id="form-kasir">
  <table>
    <thead>
      <tr>
        <th style="text-align:left;">Nama Barang</th>
        <th>Harga Jual</th>
        <th>Stok</th>
        <th>Jumlah Dijual</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody id="tabel-barang">
      <?php foreach ($barang as $i => $row): ?>
      <tr data-id="<?= $row['id_barang'] ?>" data-harga="<?= $row['harga_jual'] ?>" data-nama="<?= htmlspecialchars($row['nama_barang']) ?>" data-stok="<?= $row['stok'] ?>">
        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
        <td style="text-align:center;">Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
        <td style="text-align:center;" class="<?= $row['stok'] < 10 ? 'stok-low' : '' ?>"><?= $row['stok'] ?></td>
        <td style="text-align:center;">
          <input type="number" min="0" max="<?= $row['stok'] ?>" value="0"
                 class="input-jual" oninput="updateKeranjang(this)">
        </td>
        <td style="text-align:right;" class="subtotal-cell">-</td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Hidden inputs untuk submit -->
  <div id="hidden-inputs"></div>

  <div class="total-box">
    Total Transaksi: <span id="total-display">Rp 0</span>
  </div>

  <div style="background:#f8f9fa; border:1px solid #ddd; border-radius:8px; padding:15px 20px; margin-top:15px;">
    <label style="font-size:13px; font-weight:bold; color:#555; display:block; margin-bottom:8px;">Pelanggan</label>
    <div>
      <label style="font-size:12px; color:#666; display:block; margin-bottom:4px;">Pilih Pelanggan</label>
      <select name="id_pelanggan" id="select-pelanggan" style="padding:7px 10px; border:1px solid #ddd; border-radius:5px; font-size:13px; min-width:220px;" onchange="toggleFormPelanggan(this)">
        <option value="">-- Tanpa Pelanggan --</option>
        <option value="baru">+ Tambah Pelanggan Baru</option>
        <?php foreach ($daftar_pelanggan as $p): ?>
          <option value="<?= $p['id_pelanggan'] ?>">
            <?= htmlspecialchars($p['kode_pelanggan'].' - '.$p['nama_pelanggan']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div id="form-pelanggan-baru" style="display:none; margin-top:12px; padding-top:12px; border-top:1px solid #eee;">
      <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <div>
          <label style="font-size:12px; color:#666; display:block; margin-bottom:4px;">Nama Pelanggan</label>
          <input type="text" name="nama_pelanggan_baru" placeholder="Nama pelanggan..."
            style="padding:7px 10px; border:1px solid #ddd; border-radius:5px; font-size:13px; width:200px;">
        </div>
        <div>
          <label style="font-size:12px; color:#666; display:block; margin-bottom:4px;">Alamat (opsional)</label>
          <input type="text" name="alamat_baru" placeholder="Alamat..."
            style="padding:7px 10px; border:1px solid #ddd; border-radius:5px; font-size:13px; width:200px;">
        </div>
      </div>
    </div>
  </div>

  <div class="footer-bar">
    <a href="<?= site_url('riwayat') ?>" class="link-riwayat">Riwayat Penjualan</a>
    <button type="submit" class="btn-proses" onclick="return siapkanSubmit()">Proses Penjualan</button>
  </div>
  </form>
</div>

<script>
const AJAX_URL = '<?= site_url('kasir/ajax_search') ?>';
const keranjang = {}; // { id_barang: { jumlah, harga, nama, stok } }

function liveSearch(val) {
  clearTimeout(window._st);
  window._st = setTimeout(function() {
    fetch(AJAX_URL + '?search=' + encodeURIComponent(val))
      .then(r => r.json())
      .then(data => renderTabel(data));
    document.getElementById('search-info').textContent = val ? '' : '';
  }, 300);
}

function renderTabel(barang) {
  const tbody = document.getElementById('tabel-barang');
  if (barang.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#888;padding:30px;">Barang tidak ditemukan.</td></tr>';
    return;
  }
  tbody.innerHTML = barang.map((row, i) => {
    const id     = row.id_barang;
    const jumlah = keranjang[id] ? keranjang[id].jumlah : 0;
    const sub    = jumlah > 0 ? 'Rp ' + (jumlah * row.harga_jual).toLocaleString('id-ID') : '-';
    const dipilih = jumlah > 0 ? 'dipilih' : '';
    const stokLow = row.stok < 10 ? 'stok-low' : '';
    return `<tr class="${dipilih}" data-id="${id}" data-harga="${row.harga_jual}" data-nama="${row.nama_barang}" data-stok="${row.stok}">
      <td>${row.nama_barang}</td>
      <td style="text-align:center;">Rp ${parseInt(row.harga_jual).toLocaleString('id-ID')}</td>
      <td style="text-align:center;" class="${stokLow}">${row.stok}</td>
      <td style="text-align:center;">
        <input type="number" min="0" max="${row.stok}" value="${jumlah}"
               class="input-jual" oninput="updateKeranjang(this)">
      </td>
      <td style="text-align:right;" class="subtotal-cell">${sub}</td>
    </tr>`;
  }).join('');
  hitungTotal();
}

function updateKeranjang(input) {
  const tr     = input.closest('tr');
  const id     = tr.dataset.id;
  const harga  = parseInt(tr.dataset.harga);
  const nama   = tr.dataset.nama;
  const stok   = parseInt(tr.dataset.stok);
  const jumlah = Math.min(parseInt(input.value) || 0, stok);
  input.value  = jumlah;

  if (jumlah > 0) {
    keranjang[id] = { jumlah, harga, nama, stok };
    tr.classList.add('dipilih');
  } else {
    delete keranjang[id];
    tr.classList.remove('dipilih');
  }
  hitungTotal();
}

function hitungTotal() {
  let total = 0;
  document.querySelectorAll('#tabel-barang tr').forEach(tr => {
    const id    = tr.dataset.id;
    const input = tr.querySelector('.input-jual');
    const subEl = tr.querySelector('.subtotal-cell');
    if (!input || !subEl) return;
    const jumlah = parseInt(input.value) || 0;
    const harga  = parseInt(tr.dataset.harga) || 0;
    const sub    = jumlah * harga;
    total += sub;
    subEl.textContent = jumlah > 0 ? 'Rp ' + sub.toLocaleString('id-ID') : '-';
  });
  // tambah dari keranjang yang tidak tampil
  Object.keys(keranjang).forEach(id => {
    const adaDiTabel = document.querySelector(`#tabel-barang tr[data-id="${id}"]`);
    if (!adaDiTabel) total += keranjang[id].jumlah * keranjang[id].harga;
  });
  document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function siapkanSubmit() {
  if (Object.keys(keranjang).length === 0) {
    alert('Pilih minimal 1 barang!');
    return false;
  }
  const container = document.getElementById('hidden-inputs');
  container.innerHTML = '';
  Object.keys(keranjang).forEach(id => {
    const input = document.createElement('input');
    input.type  = 'hidden';
    input.name  = 'jumlah[' + id + ']';
    input.value = keranjang[id].jumlah;
    container.appendChild(input);
  });
  return true;
}

function toggleFormPelanggan(select) {
  document.getElementById('form-pelanggan-baru').style.display =
    select.value === 'baru' ? 'block' : 'none';
}
</script>
</body>
</html>