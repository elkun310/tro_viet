@extends('adminlte::page')

@section('content_header')
    <h1>Chỉnh sửa bài đăng</h1>
@stop

@section('content')
    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    <form action="{{ route('admin.posts.update', $post) }}" method="POST" id="post-form">
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
            <label>Đặc điểm nổi bật</label>
            <div>
                @foreach ($features as $feature)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="features[]" id="feature_{{ $feature->id }}"
                            value="{{ $feature->id }}" class="form-check-input"
                            {{ (is_array(old('features')) && in_array($feature->id, old('features'))) ||
                            (isset($post) && $post->features->contains($feature->id))
                                ? 'checked'
                                : '' }}>
                        <label class="form-check-label" for="feature_{{ $feature->id }}">{{ $feature->name }}</label>
                    </div>
                @endforeach
            </div>
            @error('features')
                <span class="text-danger">{{ $message }}</span>
            @enderror
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
            <label>Hình ảnh</label>
            <div class="dropzone" id="post-dropzone"></div>
        </div>

        <div class="image-preview-container d-flex flex-wrap gap-3">
            @foreach ($post->images as $image)
                @php
                    $imageUrl = asset('storage/' . ltrim($image->url, '/'));
                @endphp
                <div class="image-preview text-center border rounded p-2 d-flex flex-column align-items-center"
                    data-id="{{ $image->id }}" style="width: 160px; min-height: 200px;">
                    <div class="image-wrapper mb-2"
                        style="height: 120px; width: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img src="{{ $imageUrl }}" class="img-fluid rounded"
                            style="max-height: 120px; object-fit: cover;"
                            onerror="this.style.display='none'; console.error('Image load error: {{ $imageUrl }}')">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-image mt-auto"
                        data-id="{{ $image->id }}">Xoá</button>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@stop

@push('js')
    <link rel="stylesheet" href="{{ asset('vendor/dropzone/dropzone.min.css') }}" />
    <script src="{{ asset('vendor/dropzone/dropzone.min.js') }}"></script>

    <script>
        Dropzone.autoDiscover = false;
        const uploadedImages = [];

        const dropzone = new Dropzone("#post-dropzone", {
            url: "{{ route('admin.posts.upload-image') }}",
            paramName: "file",
            maxFilesize: 2,
            acceptedFiles: 'image/*',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                uploadedImages.push(response.path);
            }
        });

        // Thêm ảnh mới vào form
        $('#post-form').on('submit', function() {
            uploadedImages.forEach(path => {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'new_images[]',
                    value: path
                }).appendTo('#post-form');
            });
        });

        // Xử lý xóa ảnh
        $('.remove-image').on('click', function() {
            const imageId = $(this).data('id');
            const container = $(this).closest('.image-preview');

            if (confirm('Bạn có chắc muốn xoá ảnh này không?')) {
                $.ajax({
                    url: '{{ route('admin.posts.delete-image') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: imageId
                    },
                    success: function() {
                        container.remove();
                    },
                    error: function() {
                        alert('Lỗi xoá ảnh');
                    }
                });
            }
        });
    </script>

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
