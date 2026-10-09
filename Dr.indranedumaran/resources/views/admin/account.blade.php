@extends('admin.layout')
@section('title', 'My account')

@section('content')
<form method="POST" action="{{ route('admin.account.update') }}" class="mx-auto max-w-xl space-y-5 rounded-2xl bg-card p-6 ring-1 ring-slate-200">
    @csrf @method('PUT')
    <div><label class="mb-1.5 block text-sm font-medium" for="name">Name</label><input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="a-input" required></div>
    <div><label class="mb-1.5 block text-sm font-medium" for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="a-input" required></div>
    <hr>
    <p class="text-sm text-slate-500">Change password (leave blank to keep the current one). Minimum 10 characters.</p>
    <div><label class="mb-1.5 block text-sm font-medium" for="current_password">Current password</label><input id="current_password" type="password" name="current_password" class="a-input" autocomplete="current-password"></div>
    <div><label class="mb-1.5 block text-sm font-medium" for="password">New password</label><input id="password" type="password" name="password" class="a-input" autocomplete="new-password"></div>
    <div><label class="mb-1.5 block text-sm font-medium" for="password_confirmation">Confirm new password</label><input id="password_confirmation" type="password" name="password_confirmation" class="a-input" autocomplete="new-password"></div>
    <div class="flex justify-end"><button class="a-btn a-btn-primary">Save</button></div>
</form>
@endsection
