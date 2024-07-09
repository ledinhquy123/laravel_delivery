<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng nhập Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-form {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-signin {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
    {{-- <a href="{{ route('admin.dashboard') }}">Admin Dasboard</a> --}}
    <div class="login-form">
        <form method="post" action="{{route('admin-auth.handle-login')}}" class="form-signin">
            @csrf
            <h1 class="h3 mb-3 font-weight-normal text-center">Đăng Nhập</h1>
            <div class="form-group">
                <label for="inputPhoneNumber" class="sr-only">Số điện thoại</label>
                <input type="text" id="inputPhoneNumber" name="phone_number" class="form-control" placeholder="Số điện thoại" autofocus>
                @error('phone_number')
                    <p style="color: red">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="inputPassword" class="sr-only">Mật Khẩu</label>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Mật Khẩu" >

                @error('password')
                    <p style="color: red">{{ $message }}</p>
                @enderror
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Đăng Nhập</button>
        </form>
    </div>
    
</body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>