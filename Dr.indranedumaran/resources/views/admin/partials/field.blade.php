@php
$n = $f['name'];
$type = $f['type'] ?? 'text';
$id = 'f_'.$n;
$val = old($n, $record->{$n} ?? ($f['default'] ?? null));
if ($type === 'datetime' && $val instanceof \Carbon\CarbonInterface) $val = $val->format('Y-m-d\TH:i');
if ($type === 'date' && $val instanceof \Carbon\CarbonInterface) $val = $val->format('Y-m-d');
$required = str_contains((string) ($f['rules'] ?? ''), 'required');
@endphp

@if($type === 'toggle')
    <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
        <input type="hidden" name="{{ $n }}" value="0">
        <input type="checkbox" name="{{ $n }}" value="1" class="h-4 w-4 rounded text-lilac focus:ring-brand-500" @checked(old($n, $record->{$n} ?? false))>
        {{ $f['label'] }}
    </label>
@else
    <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-slate-700">{{ $f['label'] }}@if($required) <span class="text-red-500">*</span>@endif</label>

    @switch($type)
        @case('textarea')
            <textarea id="{{ $id }}" name="{{ $n }}" rows="{{ $f['rows'] ?? 4 }}" class="a-input">{{ $val }}</textarea>
            @break
        @case('richtext')
            <div x-data="richEditor(@js((string) $val))">
                <div class="rounded-lg bg-card ring-1 ring-slate-300"><div x-ref="editor"></div></div>
                <input type="hidden" name="{{ $n }}" :value="html">
            </div>
            @break
        @case('select')
            <select id="{{ $id }}" name="{{ $n }}" class="a-input">
                @if(isset($f['blank']) || !$required)<option value="">{{ $f['blank'] ?? '—' }}</option>@endif
                @foreach($f['options'] as $k => $label)<option value="{{ $k }}" @selected((string) $val === (string) $k)>{{ $label }}</option>@endforeach
            </select>
            @break
        @case('image')
            @if($record->{$n})
                <div class="mb-3 flex items-center gap-4">
                    <img src="{{ media_url($record->{$n}) }}" alt="" class="h-20 w-28 rounded-lg object-cover ring-1 ring-slate-200">
                    <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remove_{{ $n }}" value="1" class="rounded"> Remove image</label>
                </div>
            @endif
            <input id="{{ $id }}" type="file" name="{{ $n }}" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-ice-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-lilac">
            <p class="mt-1 text-xs text-slate-500">JPG, PNG or WebP, up to 5 MB. Images are optimised automatically.</p>
            @break
        @case('images')
            @if(!empty($record->{$n}))
                <div class="mb-3 grid grid-cols-3 gap-3 sm:grid-cols-5">
                    @foreach($record->{$n} as $p)
                        <label class="block text-center text-xs text-slate-500"><img src="{{ media_url($p) }}" alt="" class="aspect-square w-full rounded-lg object-cover ring-1 ring-slate-200"><span class="mt-1 flex items-center justify-center gap-1"><input type="checkbox" name="keep_{{ $n }}[]" value="{{ $p }}" checked class="rounded"> keep</span></label>
                    @endforeach
                </div>
            @endif
            <input id="{{ $id }}" type="file" name="{{ $n }}[]" multiple accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-ice-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-lilac">
            @break
        @case('date')
            <input id="{{ $id }}" type="date" name="{{ $n }}" value="{{ $val }}" class="a-input">
            @break
        @case('datetime')
            <input id="{{ $id }}" type="datetime-local" name="{{ $n }}" value="{{ $val }}" class="a-input">
            @break
        @case('number')
            <input id="{{ $id }}" type="number" min="0" name="{{ $n }}" value="{{ $val ?? 0 }}" class="a-input">
            @break
        @default
            <input id="{{ $id }}" type="{{ $type === 'url' ? 'url' : 'text' }}" name="{{ $n }}" value="{{ $val }}" class="a-input" @if($required) required @endif>
    @endswitch

    @if(!empty($f['help']))<p class="mt-1 text-xs text-slate-500">{{ $f['help'] }}</p>@endif
    @error($n)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    @error($n.'.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
@endif
