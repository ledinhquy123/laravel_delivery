@extends('admin.masterlayout')

@section('title', 'Trang người dùng')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Danh sách người dùng</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger">
          {{ session('error') }}
      </div>
    @endif
    @isset($users)
        @if ($users->count())
        <div class="row">
          <div class="col-12">
              <table class="table table-striped table-hover">
                  <thead style="background: rgb(130, 182, 130)">
                      <tr>
                          <th>STT</th>
                          <th>Ảnh đại diện</th>
                          <th>Họ tên</th>
                          <th>Số điện thoại</th>
                          <th></th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $index => $user)
                      @if ($user->phone_number != '8888888888')
                      <tr>
                        <td>{{$index + 1}}</td>
                        <td>
                          <img src="{{$user->avatar}}" width="60" height="60">
                        </td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->phone_number}}</td>
                        <td>
                          <a href="{{route('admin.users.user-update', ['user' => $user->id])}}" class="btn btn-warning">Sửa</a>
                        </td>
                        <td>
                          <a href="{{route('admin.users.delete-user', ['user' => $user->id])}}" class="btn btn-danger">Xoá</a>
                        </td>
                      </tr>
                      @endif
                     @endforeach
                  </tbody>
              </table>
          </div>
        @else
        <div class="row">
          <div class="col-12">
              <p class="text-center text-xl" style="color: red">Hiện không có dữ liệu chi tiết</p>
          </div>
        @endif
    @endisset
  </div>
  </div>
  <!-- /.container-fluid -->
</div>
@endsection