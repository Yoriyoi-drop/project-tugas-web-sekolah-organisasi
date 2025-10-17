<div class="container" style="max-width: 400px; margin: 50px auto;">
    <h2 style="text-align:center;">Login</h2>
    <form method="POST" action="{{ route('auth') }}">
        @csrf
        <div style="margin-bottom: 15px;">
            <label for="username">Email</label>
            <input type="email" id="username" name="username" class="form-control" required autofocus style="width:100%;padding:8px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required style="width:100%;padding:8px;">
        </div>
        <button type="submit" style="width:100%;padding:10px;background:#3490dc;color:#fff;border:none;border-radius:4px;">Login</button>
    </form>
    @if ($errors->any())
        <div style="color:red;margin-top:10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
