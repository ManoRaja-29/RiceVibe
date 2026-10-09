<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\MediaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Config-driven CRUD. Child controllers describe columns() and fields();
 * validation, uploads, sanitising and cache-busting are handled here.
 */
abstract class CrudController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;

    protected string $route;      // e.g. admin.posts

    protected string $title;      // e.g. Blog posts

    protected string $singular;   // e.g. blog post

    protected array $searchable = ['title'];

    protected string $orderBy = 'id';

    protected string $orderDir = 'desc';

    abstract protected function columns(): array;

    abstract protected function fields(): array;

    protected function seo(): array
    {
        return [
            ['name' => 'meta_title', 'label' => 'SEO title', 'rules' => 'nullable|max:70', 'section' => 'SEO', 'help' => 'Leave blank to use the page title. ~60 characters.'],
            ['name' => 'meta_description', 'label' => 'Meta description', 'type' => 'textarea', 'rows' => 3, 'rules' => 'nullable|max:320', 'section' => 'SEO', 'help' => '150–160 characters works best in search results.'],
        ];
    }

    protected function filters(): array
    {
        return [];
    }

    protected function defaults(): array
    {
        return [];
    }

    protected function beforeSave(array &$data, ?Model $record): void {}

    protected function afterSave(Model $record): void {}

    public function index(Request $request)
    {
        $query = $this->model::query();
        if ($term = trim((string) $request->query('q'))) {
            $query->where(function ($w) use ($term) {
                foreach ($this->searchable as $f) {
                    $w->orWhere($f, 'like', '%'.addcslashes($term, '%_\\').'%');
                }
            });
        }
        foreach ($this->filters() as $field => $f) {
            if (($v = $request->query($field)) !== null && $v !== '') {
                $query->where($field, $v);
            }
        }
        $this->scopeIndex($query);
        $records = $query->orderBy($this->orderBy, $this->orderDir)->paginate(20)->withQueryString();

        return view('admin.crud.index', [
            'records' => $records, 'columns' => $this->columns(), 'filters' => $this->filters(),
            'title' => $this->title, 'singular' => $this->singular, 'route' => $this->route,
        ]);
    }

    protected function scopeIndex($query): void {}

    public function create()
    {
        return $this->form(new $this->model($this->defaults()));
    }

    public function edit(int|string $id)
    {
        return $this->form($this->model::findOrFail($id));
    }

    protected function form(Model $record)
    {
        return view('admin.crud.form', [
            'record' => $record, 'fields' => $this->fields(), 'title' => $this->title,
            'singular' => $this->singular, 'route' => $this->route,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = new $this->model;
        $this->persist($request, $record);

        return redirect()->route($this->route.'.index')->with('status', ucfirst($this->singular).' created.');
    }

    public function update(Request $request, int|string $id): RedirectResponse
    {
        $record = $this->model::findOrFail($id);
        $this->persist($request, $record);

        return redirect()->route($this->route.'.edit', $record)->with('status', ucfirst($this->singular).' saved.');
    }

    public function destroy(int|string $id): RedirectResponse
    {
        $this->model::findOrFail($id)->delete();
        $this->flush();

        return redirect()->route($this->route.'.index')->with('status', ucfirst($this->singular).' deleted.');
    }

    protected function persist(Request $request, Model $record): void
    {
        $fields = $this->fields();
        $rules = [];
        foreach ($fields as $f) {
            $type = $f['type'] ?? 'text';
            $r = $f['rules'] ?? 'nullable';
            if ($type === 'image') {
                $rules[$f['name']] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
            } elseif ($type === 'images') {
                $rules[$f['name']] = ['nullable', 'array', 'max:20'];
                $rules[$f['name'].'.*'] = ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
            } else {
                $rules[$f['name']] = is_callable($r) ? $r($record) : $r;
            }
        }
        $data = Validator::make($request->all(), $rules)->validate();

        $media = app(MediaService::class);
        $out = [];
        foreach ($fields as $f) {
            $n = $f['name'];
            switch ($f['type'] ?? 'text') {
                case 'image':
                    $out[$n] = $record->{$n};
                    if ($request->boolean('remove_'.$n)) {
                        $out[$n] = null;
                    }
                    if ($request->hasFile($n)) {
                        try {
                            $out[$n] = $media->store($request->file($n), $request->input($n.'_alt'))->path;
                        } catch (\Throwable $e) {
                            throw ValidationException::withMessages([$n => 'The image could not be processed.']);
                        }
                    }
                    break;
                case 'images':
                    $keep = array_values(array_intersect((array) $record->{$n}, (array) $request->input('keep_'.$n, [])));
                    foreach ((array) $request->file($n, []) as $file) {
                        try {
                            $keep[] = $media->store($file)->path;
                        } catch (\Throwable) {
                            throw ValidationException::withMessages([$n => 'An image could not be processed.']);
                        }
                    }
                    $out[$n] = $keep;
                    break;
                case 'toggle':
                    $out[$n] = $request->boolean($n);
                    break;
                case 'richtext':
                    $out[$n] = clean_html($data[$n] ?? '');
                    break;
                case 'number':
                    $out[$n] = ($data[$n] ?? '') === '' ? ($f['default'] ?? 0) : (int) $data[$n];
                    break;
                default:
                    $v = $data[$n] ?? null;
                    $out[$n] = ($v === '' || $v === null) ? null : (is_string($v) ? trim($v) : $v);
            }
        }

        $this->beforeSave($out, $record->exists ? $record : null);
        $record->fill($out)->save();
        $this->afterSave($record);
        $this->flush();
    }

    protected function flush(): void
    {
        Cache::forget('home.data');
        Cache::forget('sitemap.xml');
    }
}
