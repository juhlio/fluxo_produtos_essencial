@csrf

<div class="row">
  <div class="col-md-6 form-group">
    <label>Nome</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="col-md-6 form-group">
    <label>E-mail</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
</div>

<div class="row">
  <div class="col-md-6 form-group">
    <label>Senha {{ $user->exists ? '(deixe em branco p/ manter)' : '' }}</label>
    <input type="password" name="password" class="form-control" {{ $user->exists ? '' : 'required' }}>
    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
  <div class="col-md-6 form-group">
    <label>Confirmar Senha</label>
    <input type="password" name="password_confirmation" class="form-control" {{ $user->exists ? '' : 'required' }}>
  </div>
</div>

<div class="form-group">
  <label>Papéis</label>
  @php
    $selected = old('roles', $user->roles->pluck('id')->toArray());
  @endphp
  <div class="d-flex flex-wrap gap-3">
    @foreach($roles as $role)
      <label class="mr-3">
        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
               {{ in_array($role->id, $selected) ? 'checked' : '' }}>
        {{ ucfirst($role->name) }}
      </label>
    @endforeach
  </div>
  @error('roles') <small class="text-danger">{{ $message }}</small> @enderror
</div>
