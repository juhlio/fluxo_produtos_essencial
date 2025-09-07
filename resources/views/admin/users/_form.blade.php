@csrf
<div class="form-group">
  <label>Nome*</label>
  <input name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
  @error('name') <small class="text-danger">{{ $message }}</small> @enderror
</div>
<div class="form-group">
  <label>Email*</label>
  <input name="email" type="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
  @error('email') <small class="text-danger">{{ $message }}</small> @enderror
</div>
<div class="row">
  <div class="col-md-6 form-group">
    <label>Senha{{ isset($user) ? ' (deixe em branco para manter)' : '*' }}</label>
    <input name="password" type="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
  <div class="col-md-6 form-group">
    <label>Confirmar senha{{ isset($user) ? '' : '*' }}</label>
    <input name="password_confirmation" type="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
  </div>
</div>
<div class="form-group">
  <label>Papéis</label>
  <div class="row">
    @foreach($roles as $role)
      @php $checked = isset($userRoles) && in_array($role, $userRoles); @endphp
      <div class="col-md-3">
        <label class="mb-1">
          <input type="checkbox" name="roles[]" value="{{ $role }}" {{ old('roles') ? (in_array($role, old('roles'))?'checked':'') : ($checked?'checked':'') }}>
          {{ ucfirst($role) }}
        </label>
      </div>
    @endforeach
  </div>
</div>
