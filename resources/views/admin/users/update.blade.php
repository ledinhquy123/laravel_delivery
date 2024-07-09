@extends('admin.masterlayout')

@section('title', 'Cập nhật người dùng')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Cập nhật người dùng - {{$user->name}}</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <form method="POST" action="{{route('admin.users.handle-user-update', ['user' => $user->id])}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Tên Người Dùng</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên người dùng" value="{{old('name', $user->name)}}">
            @error('name')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone_number">Số Điện Thoại</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Nhập số điện thoại" value="{{old('phone_number', $user->phone_number)}}">
            @error('phone_number')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="avatar">Ảnh đại diện(Nên để khách để theo ý)</label>
            <input type="file" class="form-control" id="avatar" name="avatar"">
            @error('avatar')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Cập Nhật</button>
    </form>
  </div>
  <!-- /.container-fluid -->
</div>
@endsection