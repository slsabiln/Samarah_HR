<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

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

    public function exportExcel(): StreamedResponse|RedirectResponse
    {
        $records = $this->query()
            ->orderBy($this->orderBy, $this->orderDirection)
            ->get();

        if ($records->isEmpty()) {
            return redirect()
                ->route($this->routeName.'.index')
                ->with('error', 'لا توجد سجلات لتصديرها.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('البيانات');
        $sheet->setRightToLeft(true);

        foreach ($this->columns as $index => $column) {
            $columnLetter = Coordinate::stringFromColumnIndex($index + 1);

            $sheet->setCellValue($columnLetter . '1', $column['label']);
            $sheet->getColumnDimension($columnLetter)->setWidth(24);
        }

        foreach ($records as $rowIndex => $record) {
            $row = $rowIndex + 2;

            foreach ($this->columns as $columnIndex => $column) {
                $columnLetter = Coordinate::stringFromColumnIndex($columnIndex + 1);

                $sheet->setCellValue(
                    $columnLetter . $row,
                    $this->excelCellValue($record, $column)
                );
            }
        }

        $lastColumnLetter = Coordinate::stringFromColumnIndex(max(count($this->columns), 1));
        $lastRow = $records->count() + 1;

        $sheet->getStyle("A1:{$lastColumnLetter}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '613817'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("A1:{$lastColumnLetter}{$lastRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->freezePane('A2');
        $sheet->setAutoFilter("A1:{$lastColumnLetter}{$lastRow}");

        $fileName = $this->routeName . '-records-' . now()->format('Y-m-d-His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
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

    protected function excelCellValue(Model $record, array $column): string|int|float|null
    {
        $value = data_get($record, $column['key']);
        $format = $column['format'] ?? 'text';
        $options = $column['options'] ?? null;

        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($options)) {
            return $options[(string) $value] ?? (string) $value;
        }

        return match ($format) {
            'money' => is_numeric($value) ? round((float) $value, 3) : (string) $value,
            'date' => $this->formatExcelDate($value),
            'days' => function_exists('days_value') ? days_value($value) : (string) $value,
            'status' => function_exists('status_badge') ? status_badge($value) : (string) $value,
            default => $this->stringifyExcelValue($value),
        };
    }

    protected function formatExcelDate(mixed $value): ?string
    {
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (Throwable) {
            return $value === null ? null : (string) $value;
        }
    }

    protected function stringifyExcelValue(mixed $value): string|int|float|null
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value) || is_float($value) || is_string($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? 'نعم' : 'لا';
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE) ?: (string) $value;
    }
}