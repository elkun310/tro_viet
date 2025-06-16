<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $keyword;

    protected $role;

    public function __construct($keyword = null, $role = null)
    {
        $this->keyword = $keyword;
        $this->role = $role;
    }

    public function collection()
    {
        return User::withTrashed()
            ->when($this->keyword, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->keyword}%")
                        ->orWhere('email', 'like', "%{$this->keyword}%");
                });
            })
            ->when($this->role, function ($query) {
                $query->where('role', $this->role);
            })
            ->orderByDesc('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên',
            'Email',
            'Số điện thoại',
            'Vai trò',
            'Ngày tạo',
            'Trạng thái',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            ucfirst($user->role),
            $user->created_at->format('d/m/Y H:i'),
            $user->trashed() ? 'Đã xóa' : 'Hoạt động',
        ];
    }
}
