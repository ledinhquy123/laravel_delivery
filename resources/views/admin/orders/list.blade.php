@extends('admin.masterlayout')

@section('title', 'Trang đơn hàng')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Danh sách đơn hàng</h1>
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
    @isset($orders)
        @if ($orders->count())
        <div class="row">
          <div class="col-12">
              <table class="table table-striped table-hover">
                  <thead style="background: rgb(130, 182, 130)">
                      <tr>
                          <th>STT</th>
                          <th>Tên người tạo</th>
                          <th>Tên tài xế</th>
                          <th>Sản phẩm</th>
                          <th>Người nhận</th>
                          <th>Ghi chú</th>
                          <th>Khoảng cách</th>
                          <th>Tổng tiền</th>
                          <th>Đánh giá tài xế</th>
                          <th>Trạng thái</th>
                          <th></th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($orders as $index => $order)
                    <tr>
                      <td>{{$index + 1}}</td>
                      <td>{{$order->user->name}}</td>
                      <td>{{$order->driver->name}}</td>
                      <td>{{$order->items}}</td>
                      <td>{{$order->receiver}}</td>
                      <td>{{$order->user_note ?? 'Chưa có'}}</td>
                      <td>{{$order->distance}}</td>
                      <td>{{$order->shipping_cost}}</td>
                      <td>{{$order->driver_rate == 0 ? 'Chưa đánh giá' : $order->driver_rate}}</td>
                      <td>{{$order->orderStatus->status_name}}</td>
                      <td>
                        {{--  --}}
                        <a href="{{route('admin.orders.order-update', ['order' => $order->id])}}" class="btn btn-warning">Sửa</a>
                      </td>
                      <td>
                        <a href="" class="btn btn-danger">Xoá</a>
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