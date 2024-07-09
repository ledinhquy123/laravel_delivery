@extends('admin.masterlayout')

@section('title', 'Trang trạng thái đơn hàng')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Danh sách trạng thái đơn hàng</h1>
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
    @isset($orderStatuses)
        @if ($orderStatuses->count())
        <div class="row">
          <div class="col-12">
              <table class="table table-striped table-hover">
                  <thead style="background: rgb(130, 182, 130)">
                      <tr>
                          <th>STT</th>
                          <th>Tên trạng thái đơn hàng</th>
                          <th></th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($orderStatuses as $index => $orderStatus)
                    <tr>
                      <td>{{$index + 1}}</td>
                      <td>{{$orderStatus->status_name}}</td>
                      <td>
                        {{-- {{route('admin.order-status.handle-order-status-update', ['orderStatus' => $orderStatus->id])}} --}}
                        <a href="" class="btn btn-warning">Sửa</a>
                      </td>
                      <td>
                        <a href="{{route('admin.order-status.delete-order-status', ['orderStatus' => $orderStatus->id])}}" class="btn btn-danger">Xoá</a>
                      </td>
                    </tr>
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