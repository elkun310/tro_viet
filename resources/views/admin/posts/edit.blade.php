@extends('adminlte::page')

@section('content_header')
    <h1>Chỉnh sửa bài đăng</h1>
@stop

@section('content')
    <form action="{{ route('admin.posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="code">Mã bài đăng</label>
            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code', $post->code) }}">
            @error('code')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $post->title) }}">
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="category_id">Danh mục</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="description">Mô tả chi tiết</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $post->description) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="price">Giá thuê (nghìn VND)</label>
            <input type="number" name="price" id="price" class="form-control"
                value="{{ old('price', $post->price) }}">
        </div>

        <div class="form-group mb-3">
            <label for="area">Diện tích (m²)</label>
            <input type="number" step="0.01" name="area" id="area" class="form-control"
                value="{{ old('area', $post->area) }}">
        </div>

        <div class="form-group mb-3">
            <label for="address">Địa chỉ chi tiết</label>
            <input type="text" name="address" id="address" class="form-control"
                value="{{ old('address', $post->address) }}">
        </div>

        <div class="form-group mb-3">
            <label for="province_id">Tỉnh/Thành phố</label>
            <select name="province_id" id="province_id" class="form-control">
                <option value="">-- Chọn tỉnh/thành --</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->id }}" {{ $selectedProvince == $province->id ? 'selected' : '' }}>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="district_id">Quận/Huyện</label>
            <select name="district_id" id="district_id" class="form-control">
                <option value="">-- Chọn quận/huyện --</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="ward_id">Phường/Xã</label>
            <select name="ward_id" id="ward_id" class="form-control">
                <option value="">-- Chọn phường/xã --</option>
            </select>
        </div>


        <div class="form-check mb-3">
            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input"
                {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
            <label for="is_featured" class="form-check-label">Đánh dấu nổi bật</label>
        </div>

        <div class="form-group mb-3">
            <label for="status">Trạng thái</label>
            <select name="status" id="status" class="form-control">
                <option value="active" {{ old('status', $post->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>Pending
                </option>
                <option value="expired" {{ old('status', $post->status) == 'expired' ? 'selected' : '' }}>Expired
                </option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Đặc điểm nổi bật</label>
            <div>
                @php
                    $postFeatureIds = old('features', $post->features->pluck('id')->toArray());
                @endphp
                @foreach ($features as $feature)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="features[]" id="feature_{{ $feature->id }}"
                            value="{{ $feature->id }}" class="form-check-input"
                            {{ in_array($feature->id, $postFeatureIds) ? 'checked' : '' }}>
                        <label class="form-check-label" for="feature_{{ $feature->id }}">{{ $feature->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
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

                            if (selectedDistrictId && district.id == selectedDistrictId) {
                                $('#district_id').val(selectedDistrictId).trigger('change');
                            }
                        });
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

                        if (selectedWardId) {
                            $('#ward_id').val(selectedWardId);
                        }
                    }
                });
            }

            $('#province_id').on('change', function() {
                loadDistricts($(this).val());
            });

            $('#district_id').on('change', function() {
                loadWards($(this).val());
            });

            // Load dữ liệu cũ
            const selectedProvince = "{{ $selectedProvince }}";
            const selectedDistrict = "{{ $selectedDistrict }}";
            const selectedWard = "{{ $post->ward_id }}";

            if (selectedProvince) {
                $('#province_id').val(selectedProvince);
                loadDistricts(selectedProvince, selectedDistrict);
            }

            if (selectedDistrict) {
                loadWards(selectedDistrict, selectedWard);
            }
        });
    </script>
@endpush
