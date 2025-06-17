<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    // Danh sách người dùng
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $role = $request->input('role');

        $users = User::withTrashed()->latest()
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->orderByDesc('id')
            ->paginate(config('constants.PAGINATE'), ["*"], 'page');

        if ($request->has('page')) {
            if (intval($request->get('page')) > $users->lastPage() && $users->lastPage() > 0) {
                return redirect($users->url(1))->withInput();
            }
        }
        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu người dùng mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,host,user',
        ]);

        $data['password'] = bcrypt($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Tạo người dùng thành công');
    }

    // Hiển thị chi tiết người dùng
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Hiển thị form chỉnh sửa
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật người dùng
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,host,user',
        ]);

        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công');
    }

    // Xoá người dùng
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xoá người dùng');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')->with('success', 'Khôi phục thành công');
    }

    /**
     * Export users to Excel
     */
    public function export(Request $request)
    {
        $keyword = $request->input('keyword');
        $role = $request->input('role');

        return Excel::download(new UsersExport($keyword, $role), 'danh-sach-nguoi-dung.xlsx');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
}
