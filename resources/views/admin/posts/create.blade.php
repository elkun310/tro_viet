@extends('adminlte::page')

@section('content_header')
    <h1>Tạo bài đăng mới</h1>
@stop

@section('content')
    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    <form action="{{ route('admin.posts.store') }}" method="POST" id="post-form">
        @csrf

        <div class="form-group mb-3">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title') }}">
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="category_id">Danh mục</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="price">Giá thuê (nghìn VND)</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}">
        </div>

        <div class="form-group mb-3">
            <label for="area">Diện tích (m²)</label>
            <input type="number" step="0.01" name="area" id="area" class="form-control"
                value="{{ old('area') }}">
        </div>

        <div class="form-group mb-3">
            <label for="address">Địa chỉ chi tiết</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
        </div>

        <div class="form-group mb-3">
            <label for="province_id">Tỉnh/Thành phố</label>
            <select name="province_id" id="province_id" class="form-control">
                <option value="">-- Chọn tỉnh/thành phố --</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
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

        <div class="form-group mb-3">
            <label>Hình ảnh</label>
            <div class="dropzone" id="post-dropzone"></div>
        </div>


        <div class="form-check mb-3">
            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input"
                {{ old('is_featured') ? 'checked' : '' }}>
            <label for="is_featured" class="form-check-label">Đánh dấu nổi bật</label>
        </div>

        <div class="form-group mb-3">
            <label for="status">Trạng thái</label>
            <select name="status" id="status" class="form-control">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@stop
@push('js')
    <link rel="stylesheet" href="{{ asset('vendor/dropzone/dropzone.min.css') }}" />
    <script src="{{ asset('vendor/dropzone/dropzone.min.js') }}"></script>

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

                        if (selectedDistrictId) {
                            $('#district_id').trigger('change');
                        }
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

            // Khi chọn tỉnh
            $('#province_id').on('change', function() {
                const provinceId = $(this).val();
                loadDistricts(provinceId);
            });

            // Khi chọn quận
            $('#district_id').on('change', function() {
                const districtId = $(this).val();
                loadWards(districtId);
            });

            // Load lại dữ liệu nếu submit bị lỗi (giữ old input)
            const oldProvince = "{{ old('province_id') }}";
            const oldDistrict = "{{ old('district_id') }}";
            const oldWard = "{{ old('ward_id') }}";

            if (oldProvince) {
                $('#province_id').val(oldProvince);
                loadDistricts(oldProvince, oldDistrict);
            }

            if (oldDistrict) {
                // Nếu district đã có, thì cần đảm bảo load ward sau khi district select xong
                // sẽ được trigger ở loadDistricts
                loadWards(oldDistrict, oldWard);
            }
        });
    </script>

    <script>
        Dropzone.autoDiscover = false;

        const uploadedImages = [];

        const dropzone = new Dropzone("#post-dropzone", {
            url: "{{ route('admin.posts.upload-image') }}",
            paramName: "file",
            maxFilesize: 2, // MB
            acceptedFiles: 'image/*',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                uploadedImages.push(response.path);
            }
        });

        // Thêm ảnh đã upload vào form
        $('#post-form').on('submit', function() {
            uploadedImages.forEach(path => {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'images[]',
                    value: path
                }).appendTo('#post-form');
            });
        });
    </script>
@endpush
