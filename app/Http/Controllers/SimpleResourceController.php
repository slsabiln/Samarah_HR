<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class SimpleResourceController extends Controller
{
    protected string $model;
    protected string $routeName;
    protected string $title;
    protected string $subtitle = '';
    protected array $columns = [];
    protected array $fields = [];
    protected array $with = [];
    protected string $orderBy = 'id';
    protected string $orderDirection = 'desc';

    public function index(Request $request): View
    {
        $query = $this->query();
        $search = trim((string) $request->query('q'));

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                foreach ($this->searchableColumns() as $column) {
                    $builder->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        return view('resources.index', [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'routeName' => $this->routeName,
            'columns' => $this->columns,
            'records' => $query->orderBy($this->orderBy, $this->orderDirection)->paginate(12)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return $this->formView(new ($this->model));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $record = ($this->model)::create($this->mutateData($data, null));

        return redirect()->route($this->routeName.'.edit', $record)->with('success', 'تمت الإضافة بنجاح.');
    }

    public function edit(int|string $id): View
    {
        return $this->formView($this->findRecord($id));
    }

    public function update(Request $request, int|string $id): RedirectResponse
    {
        $record = $this->findRecord($id);
        $record->update($this->mutateData($this->validated($request), $record));

        return redirect()->route($this->routeName.'.edit', $record)->with('success', 'تم حفظ التعديلات بنجاح.');
    }

    public function destroy(int|string $id): RedirectResponse
    {
        $this->findRecord($id)->delete();
        return redirect()->route($this->routeName.'.index')->with('success', 'تم حذف السجل.');
    }

    protected function formView(Model $record): View
    {
        return view('resources.form', [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'routeName' => $this->routeName,
            'fields' => $this->fields,
            'record' => $record,
            'employees' => Employee::orderBy('name')->get(),
        ]);
    }

    protected function query(): Builder
    {
        return ($this->model)::query()->with($this->with);
    }

    protected function findRecord(int|string $id): Model
    {
        return ($this->model)::query()->findOrFail($id);
    }

    protected function rules(): array
    {
        return collect($this->fields)->mapWithKeys(fn (array $field) => [$field['name'] => $field['rules'] ?? ['nullable']])->all();
    }

    protected function validated(Request $request): array
    {
        return $request->validate($this->rules(), [], $this->attributes());
    }

    protected function attributes(): array
    {
        return collect($this->fields)->mapWithKeys(fn (array $field) => [$field['name'] => $field['label']])->all();
    }

    protected function searchableColumns(): array
    {
        return collect($this->columns)
            ->pluck('key')
            ->filter(fn ($column) => is_string($column) && ! str_contains($column, '.'))
            ->take(4)
            ->values()
            ->all();
    }

    protected function mutateData(array $data, ?Model $record): array
    {
        return $data;
    }
}
