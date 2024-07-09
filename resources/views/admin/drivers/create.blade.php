@extends('admin.masterlayout')

@section('title', 'Thêm tài xế')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Thêm tài xế</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <form method="POST" action="" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Tên Tài Xế</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên tài xế" value="{{old('name')}}">
            @error('name')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone_number">Số Điện Thoại</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Nhập số điện thoại" value="{{old('phone_number')}}">
            @error('phone_number')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" value="{{old('password')}}">
            @error('password')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirmation">Nhập lại mật khẩu</label>
            <input type="password" class="form-control" id="password-confirmation" name="password_confirmation" placeholder="Nhập mật khẩu" value="{{old('password-confirmation')}}">
            @error('password-confirmation')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="avatar">Ảnh đại diện(Nên để tài tài để theo ý)</label>
            <input type="file" class="form-control" id="avatar" name="avatar"">
            @error('avatar')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm Mới</button>
    </form>
  </div>
  <!-- /.container-fluid -->
</div>
@endsection