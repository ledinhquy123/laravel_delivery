<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function login() {
        return view('login');
    }

    public function handleLogin(Request $request) {
        $data = $request->except('_token');
        $request->validate(
            [
                'phone_number' => 'required|string',
                'password' => 'required|string' 
            ],
            [
                'required' => ':attribute không được trống',
                'string' => ':attribute không đúng định dạng'
            ],
            [
                'phone_number' => 'Số điện thoại',
                'password' => 'Mật khầu'
            ]
        );

        $token = auth('user_jwt')->attempt($data);

        if($token) {
            return redirect()->route('admin.dashboard', ['id' => auth('user_jwt')->id()]);
        }else return back();
    }

    public function dashboard() {
        $orders = Order::select('driver_id', DB::raw('count(*) as total_orders'), DB::raw('SUM(shipping_cost) as total_price'))
        ->groupBy('driver_id')
        ->with('driver')
        ->get();
        $quantityDrivers = Driver::count();
        $quantityUsers = User::count();
        $quantityOrders = Order::count();
        

        // return $this->success($quantityOrders);
        return view(
            'admin.dashboard', 
            [
                'orders' => $orders, 
                'quantityDrivers' => $quantityDrivers, 
                'quantityUsers' => $quantityUsers, 
                'quantityOrders' => $quantityOrders
            ]
        );
    }

    public function users() {
        $users = User::all();
        return view('admin.users.list', ['users' => $users]);
    }

    public function createUser() {
        return view('admin.users.create');
    }

    public function handleCreateUser(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:10', 'max:10', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:2048'], // Validation for avatar
        ],
        [
            'required' => ':attribute không được rỗng',
            'max' => ':attribute không vượt quá :max kí tự',
            'unique' => ':attribute đã tồn tại trên hệ thống',
            'confirmed' => ':attribute xác nhận chưa thành công',
            'min' => ':attribute không nhỏ hơn :min kí tự',
        ],
        [
            'name' => 'Họ tên',
            'phone_number' => 'Số điện thoại',
            'avatar' => 'Ảnh đại diện',
            'password' => 'Mật khẩu'
        ]);

        $data = $request->except('_token');

        if ($request->hasFile('avatar')) {
            // Convert image to base64
            $avatar = $request->file('avatar');
            $avatarBase64 = base64_encode(file_get_contents($avatar->getRealPath()));
            $data['avatar'] = $this->saveImage($avatarBase64, 'users');
        }
        unset($data['password_confirmation']);
        $user = User::create($data);

        return redirect(route('admin.users.user-list'))->with('success', 'Thêm người dùng ' . $user->name . ' thành công!');
    }

    public function deleteUser(User $user) {
        $name = $user->name;
        if($user->orders()->count()) {
            return redirect(route('admin.users.user-list'))->with('error', 'Xoá ' . $name . ' không thành công!');
        }else {
            $user->delete();
            return redirect(route('admin.users.user-list'))->with('success', 'Xoá ' . $name . ' thành công!');
        }
    }

    public function updateUser(User $user) {
        return view('admin.users.update', ['user' => $user]);
    }
    
    public function handleUpdateUser(Request $request, User $user) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:10', 'max:10', 'unique:users,phone_number,' . $user->id],
            'avatar' => ['nullable', 'image', 'max:2048'], // Validation for avatar
        ],
        [
            'required' => ':attribute không được rỗng',
            'max' => ':attribute không vượt quá :max kí tự',
            'min' => ':attribute không nhỏ hơn :min kí tự',
        ],
        [
            'name' => 'Họ tên',
            'phone_number' => 'Số điện thoại',
            'avatar' => 'Ảnh đại diện'
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarBase64 = base64_encode(file_get_contents($avatar->getRealPath()));
            $user->avatar = $this->saveImage($avatarBase64, 'users');
        }

        $user->save();

        return redirect(route('admin.users.user-list'))->with('success', 'Cập nhật thông tin ' . $user->name . ' thành công!');
    }

    public function drivers() {
        $drivers = Driver::all();
        return view('admin.drivers.list', ['drivers' => $drivers]);
    }

    public function updateDriver(Driver $driver) {
        return view('admin.drivers.update', ['driver' => $driver]);
    }

    public function handleUpdateDriver(Request $request, Driver $driver) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:10', 'max:10', 'unique:users,phone_number,' . $driver->id],
            'avatar' => ['nullable', 'image', 'max:2048'], // Validation for avatar
        ],
        [
            'required' => ':attribute không được rỗng',
            'max' => ':attribute không vượt quá :max kí tự',
            'min' => ':attribute không nhỏ hơn :min kí tự',
        ],
        [
            'name' => 'Họ tên',
            'phone_number' => 'Số điện thoại',
            'avatar' => 'Ảnh đại diện'
        ]);

        $driver->name = $request->name;
        $driver->phone_number = $request->phone_number;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarBase64 = base64_encode(file_get_contents($avatar->getRealPath()));
            $driver->avatar = $this->saveImage($avatarBase64, 'users');
        }

        $driver->save();

        return redirect(route('admin.drivers.driver-list'))->with('success', 'Cập nhật thông tin ' . $driver->name . ' thành công!');
    }

    public function createDriver() {
        return view('admin.drivers.create');
    }

    public function handleCreateDriver(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:10', 'max:10', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:2048'], // Validation for avatar
        ],
        [
            'required' => ':attribute không được rỗng',
            'max' => ':attribute không vượt quá :max kí tự',
            'unique' => ':attribute đã tồn tại trên hệ thống',
            'confirmed' => ':attribute xác nhận chưa thành công',
            'min' => ':attribute không nhỏ hơn :min kí tự',
        ],
        [
            'name' => 'Họ tên',
            'phone_number' => 'Số điện thoại',
            'avatar' => 'Ảnh đại diện',
            'password' => 'Mật khẩu'
        ]);

        $data = $request->except('_token');

        if ($request->hasFile('avatar')) {
            // Convert image to base64
            $avatar = $request->file('avatar');
            $avatarBase64 = base64_encode(file_get_contents($avatar->getRealPath()));
            $data['avatar'] = $this->saveImage($avatarBase64, 'drivers');
        }
        unset($data['password_confirmation']);
        $driver = Driver::create($data);

        return redirect(route('admin.drivers.driver-list'))->with('success', 'Thêm tài xế ' . $driver->name . ' thành công!');
    }

    public function deleteDriver(Driver $driver) {
        $name = $driver->name;
        if($driver->orders()->count()) {
            return redirect(route('admin.drivers.driver-list'))->with('error', 'Xoá ' . $name . ' không thành công!');
        }else {
            $driver->delete();
            return redirect(route('admin.drivers.driver-list'))->with('success', 'Xoá ' . $name . ' thành công!');
        }
    }

    public function orderStatus() {
        $orderStatuses = OrderStatus::all();
        return view('admin.orderstatuses.list', ['orderStatuses' => $orderStatuses]);
    }

    public function createOrderStatus() {
        return view('admin.orderstatuses.create');
    }

    public function handleCreateOrderStatus(Request $request) {
        $request->validate([
            'status_name' => ['required', 'string', 'max:255']
        ],
        [
            'required' => ':attribute không được rỗng',
            'max' => ':attribute không vượt quá :max kí tự'
        ],
        [
            'status_name' => 'Tên trạng thái đơn hàng'
        ]);

        $data = $request->except('_token');
        $orderStatus = OrderStatus::create($data);

        return redirect(route('admin.order-status.order-status-list'))->with('success', 'Thêm trạng thái ' . $orderStatus->status_name . ' thành công!');
    }

    public function updateOrderStatus(OrderStatus $orderStatus) {
        return view('admin.orderstatuses.update', ['orderStatus' => $orderStatus]);
    }

    public function handleUpdateOrderStatus(Request $request, OrderStatus $orderStatus) {
        $request->validate([
            'status_name' => ['required', 'string', 'max:255']
        ],
        [
            'required' => ':attribute không được rỗng',
            'max' => ':attribute không vượt quá :max kí tự'
        ],
        [
            'status_name' => 'Tên trạng thái đơn hàng'
        ]);

        $orderStatus->status_name = $request->status_name;
        $orderStatus->save();

        return redirect(route('admin.order-status.order-status-list'))->with('success', 'Cập nhật thông tin ' . $orderStatus->status_name . ' thành công!');
    }

    public function deleteOrderStatus(OrderStatus $orderStatus) {
        $name = $orderStatus->status_name;
        if($orderStatus->orders()->count()) {
            return redirect(route('admin.order-status.order-status-list'))->with('error', 'Xoá ' . $name . ' không thành công!');
        }else {
            $orderStatus->delete();
            return redirect(route('admin.order-status.order-status-list'))->with('success', 'Xoá ' . $name . ' thành công!');
        }
    }

    public function orders() {
        $orders = Order::all();
        return view('admin.orders.list', ['orders' => $orders]);
    }

    public function updateOrder(Order $order) {
        $users = User::all();
        $drivers = Driver::all();
        $orderStatuses = OrderStatus::all();
        return view('admin.orders.update', ['order' => $order, 'users' => $users, 'drivers' => $drivers, 'orderStatuses' => $orderStatuses]);
    }
    
    public function handleUpdateOrder(Request $request, Order $order) {
        $order->user_id = $request->user_id;
        $order->driver_id = $request->driver_id;
        $order->items = $request->items;
        $order->shipping_cost = $request->shipping_cost;
        $order->user_note = $request->user_note;
        $order->receiver = $request->receiver;
        $order->driver_rate = $request->driver_rate;
        $order->distance = $request->distance;
        $order->order_status_id = $request->order_status_id;

        $order->save();

        return redirect(route('admin.orders.order-list'))->with('success', 'Cập nhật thông tin đơn #' . $order->id . ' thành công!');
    }
}