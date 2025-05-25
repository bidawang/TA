<div class="mb-3">
    <label>Merek</label>
    <input type="text" name="merek" class="form-control" value="{{ old('merek', $tv->merek ?? '') }}" required>
</div>
<div class="mb-3">
    <label>Ukuran (inci)</label>
    <input type="text" name="ukuran" class="form-control" value="{{ old('ukuran', $tv->ukuran ?? '') }}" required>
</div>

