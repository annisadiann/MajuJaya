<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Toko ATK Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body { 
      font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), 
                  url('https://images.unsplash.com/photo-1568205612837-017257d2310a?q=80&w=2000&auto=format&fit=crop'); 
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      display: flex; 
      justify-content: center; 
      align-items: center; 
      min-height: 100vh; 
    }

    .card { 
      background: rgba(255, 255, 255, 0.1); 
      backdrop-filter: blur(25px); 
      -webkit-backdrop-filter: blur(25px);
      border: 1px solid rgba(255, 255, 255, 0.3); 
      border-radius: 25px; 
      padding: 50px 40px; 
      width: 400px; 
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    h2 { 
      color: #fff;
      font-size: 30px;
      font-weight: 800;
      margin-bottom: 5px;
      letter-spacing: 1px;
      text-transform: uppercase;
      text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .subtitle { 
      color: rgba(255, 255, 255, 0.9);
      font-size: 13px; 
      margin-bottom: 40px; 
      font-weight: 400;
      letter-spacing: 0.5px;
    }

    .input-group {
      text-align: left;
      margin-bottom: 25px;
    }

    label { 
      display: block; 
      font-size: 11px; 
      font-weight: 700;
      color: #fff;
      margin-bottom: 8px; 
      margin-left: 5px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    input[type=text], input[type=password] { 
      width: 100%; 
      padding: 14px 18px; 
      background: rgba(255, 255, 255, 0.2); 
      border: 1px solid rgba(255, 255, 255, 0.2); 
      border-radius: 12px; 
      font-size: 14px; 
      color: #fff;
      transition: all 0.3s ease;
    }

    input::placeholder { color: rgba(255, 255, 255, 0.6); }

    input:focus { 
      outline: none; 
      background: rgba(255, 255, 255, 0.3);
      border-color: #fff;
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
    }

    button { 
      width: 100%; 
      padding: 15px; 
      background: #fff; 
      color: #333; 
      border: none; 
      border-radius: 12px; 
      font-size: 15px; 
      font-weight: 800;
      cursor: pointer; 
      transition: all 0.3s ease;
      margin-top: 10px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    button:hover { 
      background: #f0f0f0;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .footer-text {
      margin-top: 30px;
      font-size: 10px;
      color: rgba(255, 255, 255, 0.6);
      text-transform: uppercase;
      letter-spacing: 1px;
    }
  </style>
</head>
<body>

<div class="card">
  <h2>MAJU JAYA</h2>
  <p class="subtitle">Pusat Alat Tulis Kantor & Sekolah</p>

  <!-- Login Logic CodeIgniter Kamu -->
  <form action="/MajuJaya/index.php/auth/proses_login" method="POST">
    <div class="input-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required autofocus>
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>
    </div>

    <button type="submit">LOGIN SYSTEM</button>
  </form>

  <div class="footer-text">
    &copy; 2026 Toko Maju Jaya
  </div>
</div>

</body>
</html>