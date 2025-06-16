<form action="{{ $action }}" method="POST">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    <div class="form-group">
        <label>Tiêu đề</label>
        <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Mã bài</label>
        <input type="text" name="code" value="{{ old('code', $post->code ?? '') }}" class="form-control">
    </div>

    <!-- Tương tự cho các trường: giá, diện tích, địa chỉ, danh mục, phường, đặc điểm... -->

    <div class="form-group">
        <label>Đặc điểm</label>
        @foreach($features as $feature)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="features[]" value="{{ $feature->id }}"
                    {{ isset($post) && $post->features->contains($feature->id) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $feature->name }}</label>
            </div>
        @endforeach
    </div>

    <button type="submit" class="btn btn-success">Lưu</button>
</form>
