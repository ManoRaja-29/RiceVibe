@extends('admin.layout')
@section('title', $def['label'])

@section('content')
<form method="POST" action="{{ route('admin.settings.update', $group) }}" enctype="multipart/form-data" class="mx-auto max-w-3xl space-y-5 rounded-2xl bg-card p-6 ring-1 ring-slate-200">
    @csrf @method('PUT')
    @foreach($def['fields'] as $f)
        @php $key = $f['key']; $type = $f['type'] ?? 'text'; $val = old($key, setting($key, $f['default'] ?? '')); @endphp
        <div>
            <label for="s_{{ $key }}" class="mb-1.5 block text-sm font-medium text-slate-700">{{ $f['label'] }}</label>
            @if($type === 'textarea')
                <textarea id="s_{{ $key }}" name="{{ $key }}" rows="{{ strlen((string) $val) > 220 ? 6 : 3 }}" class="a-input">{{ $val }}</textarea>
            @elseif($type === 'image')
                @if(setting($key))
                    <div class="mb-3 flex items-center gap-4"><img src="{{ media_url(setting($key)) }}" alt="" class="h-20 w-28 rounded-lg object-cover ring-1 ring-slate-200"><label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remove_{{ $key }}" value="1" class="rounded"> Remove</label></div>
                @endif
                <input id="s_{{ $key }}" type="file" name="{{ $key }}" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-ice-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-lilac">
            @else
                <input id="s_{{ $key }}" type="{{ $type === 'url' ? 'url' : 'text' }}" name="{{ $key }}" value="{{ $val }}" class="a-input">
            @endif
            @if(!empty($f['help']))<p class="mt-1 text-xs text-slate-500">{{ $f['help'] }}</p>@endif
            @error($key)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    @endforeach
    <div class="flex justify-end"><button class="a-btn a-btn-primary">Save settings</button></div>
</form>
@endsection
