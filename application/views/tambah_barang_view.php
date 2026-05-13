<!DOCTYPE html>
<html>
<head>
  <title>Tambah Barang - Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 500px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; margin-bottom: 20px; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
    .hint { font-size: 12px; color: #888; font-weight: normal; margin-left: 5px; }
    input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 15px; font-size: 14px; }
    .btn-simpan { width: 100%; background: #3498db; color: white; padding: 12px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
    .btn-simpan:hover { background: #2980b9; }
    .error { background: #ffe0e0; color: #c0392b; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .link-back { display: inline-block; margin-top: 15px; color: #3498db; text-decoration: none; }
    .link-back:hover { text-decoration: underline; }
    .divider { border: none; border-top: 1px dashed #ddd; margin: 5px 0 18px 0; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Tambah Barang</h2>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="<?= site_url('barang/simpan') ?>" method="POST" id="form-tambah">

      <label>Nama Barang</label>
      <input type="text" name="nama_barang" placeholder="Contoh: Buku Tulis" required>

      <hr class="divider">

      <label>Harga Beli <span class="hint">(harga dari supplier)</span></label>
      <input type="text" inputmode="numeric" id="harga_beli_display" placeholder="Contoh: 4.500" required>
      <input type="hidden" name="harga_beli" id="harga_beli">

      <label>Harga Jual <span class="hint">(harga ke customer)</span></label>
      <input type="text" inputmode="numeric" id="harga_jual_display" placeholder="Contoh: 6.000" required>
      <input type="hidden" name="harga_jual" id="harga_jual">

      <hr class="divider">

      <label>Jumlah Stok Awal</label>
      <input type="text" inputmode="numeric" id="jumlah_display" placeholder="Contoh: 30" required>
      <input type="hidden" name="jumlah" id="jumlah">

      <button type="submit" class="btn-simpan">Simpan Barang</button>
    </form>
    <a class="link-back" href="<?= site_url('barang') ?>">← Kembali ke Daftar Barang</a>
  </div>

  <script>
    function formatInput(input, hiddenId) {
      let raw = input.value.replace(/\./g, '').replace(/[^0-9]/g, '');

      if (raw.length > 1 && raw[0] === '0') {
        raw = raw.replace(/^0+/, '') || '0';
      }

      document.getElementById(hiddenId).value = raw;
      input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    }

    document.getElementById('harga_beli_display').addEventListener('input', function() {
      formatInput(this, 'harga_beli');
    });

    document.getElementById('harga_jual_display').addEventListener('input', function() {
      formatInput(this, 'harga_jual');
    });

    document.getElementById('jumlah_display').addEventListener('input', function() {
      formatInput(this, 'jumlah');
    });

    document.getElementById('form-tambah').addEventListener('submit', function(e) {
      const hargaBeli = parseInt(document.getElementById('harga_beli').value) || 0;
      const hargaJual = parseInt(document.getElementById('harga_jual').value) || 0;
      const jumlah    = document.getElementById('jumlah').value;

      if (hargaBeli <= 0) {
        alert('Harga beli harus diisi!');
        e.preventDefault(); return;
      }
      if (hargaJual <= 0) {
        alert('Harga jual harus diisi!');
        e.preventDefault(); return;
      }
      if (jumlah === '') {
        alert('Jumlah stok harus diisi!');
        e.preventDefault(); return;
      }
    });
  </script>
</body>
</html>