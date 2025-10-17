<!DOCTYPE html>
<html lang=id>
<head>
    <meta charset=UTF-8>
    <meta name=viewport content=width=device-width, initial-scale=1.0>
    <title>Dashboard Pengguna</title>
    <style>
        body {
            font-family Arial, sans-serif;
            margin 0;
            background-color #f4f4f4;
        }

        .header {
            background-color #2c3e50;
            color white;
            padding 20px 40px;
            text-align center;
        }

        .dashboard {
            max-width 800px;
            margin 30px auto;
            padding 20px;
            background-color white;
            border-radius 10px;
            box-shadow 0 4px 8px rgba(0,0,0,0.1);
        }

        .dashboard h2 {
            color #333;
        }

        .user-info {
            font-size 18px;
            margin-top 10px;
            color #555;
        }

        .footer {
            text-align center;
            padding 15px;
            background-color #ecf0f1;
            color #7f8c8d;
            position fixed;
            bottom 0;
            width 100%;
        }
    style
<head>
<body>
    <div class=header
        <h1>Selamat Datang di Dashboard</h1>
    </div>
    <div class=dashboard>
        <h2>Informasi Pengguna</h2>
        <div class=user-info>
            Nama Pengguna: <strong> {{ $user['nama'] }} </strong>
        </div>
        <div class=logout>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type=submit>Logout</button>
            </form>
        </div>
    </div>
    <div class=footer>
        &copy; 2025 Dashboard dibuat oleh <code>{{ $user['nama'] }}</code>. Hak cipta dilindungi.
    </div>
</body>
</html>