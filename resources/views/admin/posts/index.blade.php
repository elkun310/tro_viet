@extends('adminlte::page')

@section('content_header')
    <h1>Danh sách bài đăng</h1>
@stop

@section('content')
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary mb-2">Tạo bài đăng</a>

    @if (session('success'))
        <x-adminlte-alert theme="success" dismissable>{{ session('success') }}</x-adminlte-alert>
    @endif


    <form method="GET" action="{{ route('admin.posts.index') }}" class="mb-4">
        <div class="row">
            <!-- Ô tìm kiếm keyword -->
            <div class="col-md-3">
                <input type="text" name="keyword" class="form-control" placeholder="Mã hoặc tiêu đề"
                    value="{{ request('keyword') }}">
            </div>

            <!-- Danh mục -->
            <div class="col-md-2">
                <select name="category_id" class="form-control">
                    <option value="">-- Danh mục --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Khoảng giá -->
            <div class="col-md-2">
                <input type="number" name="price_min" class="form-control" placeholder="Giá từ"
                    value="{{ request('price_min') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="price_max" class="form-control" placeholder="Giá đến"
                    value="{{ request('price_max') }}">
            </div>

            <!-- Trạng thái -->
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
        </div>

        <div class="row mt-2">
            <!-- Tỉnh / Thành -->
            <div class="col-md-2">
                <select name="province_id" id="province_id" class="form-control">
                    <option value="">-- Tỉnh / Thành --</option>
                    @foreach ($provinces as $p)
                        <option value="{{ $p->id }}" {{ request('province_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quận / Huyện -->
            <div class="col-md-2">
                <select name="district_id" id="district_id" class="form-control">
                    <option value="">-- Quận / Huyện --</option>
                </select>
            </div>

            <!-- Phường / Xã -->
            <div class="col-md-2">
                <select name="ward_id" id="ward_id" class="form-control">
                    <option value="">-- Phường / Xã --</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>


    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 80px;">Mã</th>
                    <th style="width: 150px;">Tiêu đề</th>
                    <th style="width: 100px;">Giá</th>
                    <th style="width: 100px;">Diện tích</th>
                    <th style="width: 200px;">Phường</th>
                    <th style="width: 100px;">Trạng thái</th>
                    <th style="width: 180px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>{{ $post->code }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ number_format($post->price * 1000) }}đ</td>
                        <td>{{ $post->area }} m²</td>
                        <td>
                            {{ $post->ward->name ?? '' }},
                            {{ $post->ward->district->name ?? '' }},
                            {{ $post->ward->district->province->name ?? '' }}
                        </td>
                        <td>
                            @switch($post->status)
                                @case('active')
                                    <span class="btn btn-success btn-sm">Active</span>
                                @break

                                @case('pending')
                                    <span class="btn btn-warning btn-sm">Pending</span>
                                @break

                                @case('expired')
                                    <span class="btn btn-danger btn-sm">Expired</span>
                                @break

                                @default
                                    <span class="btn btn-secondary btn-sm">{{ $post->status }}</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xoá</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Không có bài đăng nào phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        {{ $posts->appends(request()->query())->links('pagination::bootstrap-4') }}
    @stop

    @push('js')
        <script>
            $(document).ready(function() {
                function loadDistricts(provinceId, selectedDistrictId = null) {
                    $('#district_id').empty().append('<option value="">-- Chọn quận/huyện --</option>');
                    $('#ward_id').empty().append('<option value="">-- Chọn phường/xã --</option>');
                    if (!provinceId) return;
                    $.get('/api/address/districts', {
                        province_id: provinceId
                    }, function(data) {
                        if (data.code === 200) {
                            $.each(data.districts, function(i, district) {
                                $('#district_id').append(
                                    $('<option>', {
                                        value: district.id,
                                        text: district.name,
                                        selected: selectedDistrictId == district.id
                                    })
                                );
                            });
                            if (selectedDistrictId) $('#district_id').trigger('change');
                        }
                    });
                }

                function loadWards(districtId, selectedWardId = null) {
                    $('#ward_id').empty().append('<option value="">-- Chọn phường/xã --</option>');
                    if (!districtId) return;
                    $.get('/api/address/wards', {
                        district_id: districtId
                    }, function(data) {
                        if (data.code === 200) {
                            $.each(data.wards, function(i, ward) {
                                $('#ward_id').append(
                                    $('<option>', {
                                        value: ward.id,
                                        text: ward.name,
                                        selected: selectedWardId == ward.id
                                    })
                                );
                            });
                        }
                    });
                }

                // Trigger when select
                $('#province_id').on('change', function() {
                    const provinceId = $(this).val();
                    loadDistricts(provinceId);
                });

                $('#district_id').on('change', function() {
                    const districtId = $(this).val();
                    loadWards(districtId);
                });

                // Tự động load lại nếu có filter
                const oldProvince = "{{ request('province_id') }}";
                const oldDistrict = "{{ request('district_id') }}";
                const oldWard = "{{ request('ward_id') }}";
                if (oldProvince) {
                    $('#province_id').val(oldProvince);
                    loadDistricts(oldProvince, oldDistrict);
                }
                if (oldDistrict) {
                    loadWards(oldDistrict, oldWard);
                }
            });
        </script>
    @endpush
