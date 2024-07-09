@extends('admin.masterlayout')

@section('title', 'Thêm trạng thái đơn hàng')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Thêm trạng thái đơn hàng</h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <form method="POST" action="{{route('admin.order-status.handle-order-status-create')}}">
        @csrf
        <div class="form-group">
            <label for="status_name">Tên Trạng Thái Đơn Hàng</label>
            <input type="text" class="form-control" id="status_name" name="status_name" placeholder="Nhập tên trạng thái" value="{{old('status_name')}}">
            @error('status_name')
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