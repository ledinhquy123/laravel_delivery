@extends('admin.masterlayout')

@section('title', 'Cập nhật đơn hàng')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Cập nhật đơn hàng - #{{$order->id}}</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <form method="POST" action="{{route('admin.orders.handle-order-update', ['order' => $order->id])}}">
        @csrf
        @isset($users)
        <div class="form-group">
            <label for="user_name">Tên người đặt</label>
            <select id="user_name" name="user_id" class="form-control">
                @foreach ($users as $user)
                @if ($user->phone_number != '8888888888')
                <option value="{{$user->id}}" {{$user->id == $order->user_id ? 'selected' : null}}>{{$user->name}}</option>   
                @endif
                @endforeach
            </select>
            @error('user_id')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        @endisset

        @isset($drivers)
        <div class="form-group">
            <label for="driver_name">Tên tài xế</label>
            <select id="driver_name" name="driver_id" class="form-control">
                @foreach ($drivers as $driver)
                @if ($driver->phone_number != '0000000000')
                <option value="{{$driver->id}}" {{$driver->id == $order->driver_id ? 'selected' : null}}>{{$driver->name}}</option>
                @endif
                @endforeach
            </select>
            @error('driver_id')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        @endisset

        <div class="form-group">
            <label for="items">Sản phẩm (Người dùng tự thêm)</label>
            <input type="text" class="form-control" id="items" name="items" placeholder="Nhập sản phẩm" value="{{old('items', $order->items)}}">
            @error('items')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="receiver">Người nhận</label>
            <input type="text" class="form-control" id="receiver" name="receiver" placeholder="Nhập người nhận" value="{{old('receiver', $order->receiver)}}">
            @error('receiver')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="shipping_cost">Giá tiền (VNĐ)</label>
            <input type="text" class="form-control" id="shipping_cost" name="shipping_cost" placeholder="Nhập giá tiền" value="{{old('shipping_cost', $order->shipping_cost)}}">
            @error('shipping_cost')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        @isset($orderStatuses)
        <div class="form-group">
            <label for="order_status_name">Trạng thái đơn hàng (Tài xế tự cập nhật)</label>
            <select id="order_status_name" name="order_status_id" class="form-control">
                @foreach ($orderStatuses as $orderStatus)
                    <option value="{{$orderStatus->id}}" {{$orderStatus->id == $order->order_status_id ? 'selected' : null}}>{{$orderStatus->status_name}}</option>
                @endforeach
            </select>
            @error('order_status_id')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        @endisset

        <div class="form-group">
            <label for="driver_rate">Đánh giá tài xế</label>
            <input type="text" class="form-control" id="driver_rate" name="driver_rate" placeholder="Đánh giá" value="{{old('driver_rate', $order->driver_rate)}}">
            @error('driver_rate')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="distance">Khoảng cách (KM)</label>
            <input type="text" class="form-control" id="distance" name="distance" placeholder="Nhập khoảng cách" value="{{old('distance', $order->distance)}}">
            @error('distance')
                <span>
                    <strong class="text-red">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
  </div>
  <!-- /.container-fluid -->
</div>
@endsection