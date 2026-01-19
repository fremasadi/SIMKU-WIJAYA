<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control"
        value="{{ old('name', $user->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control"
        value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Role</label>
    <select name="role" class="form-control" required>
        <option value="owner" {{ (old('role', $user->role ?? '') == 'owner') ? 'selected' : '' }}>
            Owner
        </option>
        <option value="admin" {{ (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' }}>
            Admin
        </option>
        <option value="kasir" {{ (old('role', $user->role ?? '') == 'kasir') ? 'selected' : '' }}>
            Kasir
        </option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control"
        {{ isset($user) ? '' : 'required' }}>
    <small class="text-muted">Kosongkan jika tidak diubah.</small>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
