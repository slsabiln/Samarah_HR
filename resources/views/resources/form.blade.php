@extends('layouts.app', ['pageTitle' => $title])

@section('content')
<section class="page-head">
    <div>
        <h2>{{ $record->exists ? 'تعديل' : 'إضافة' }} - {{ $title }}</h2>
        @if($subtitle)<p>{{ $subtitle }}</p>@endif
    </div>
    <a class="btn btn-light" href="{{ route($routeName.'.index') }}">رجوع</a>
</section>

<section class="panel">
    <form method="POST" action="{{ $record->exists ? route($routeName.'.update', $record) : route($routeName.'.store') }}" class="form-grid">
        @csrf
        @if($record->exists)
            @method('PUT')
        @endif

        @foreach($fields as $field)
            @php
                $name = $field['name'];
                $type = $field['type'] ?? 'text';
                $value = old($name, data_get($record, $name));
                if ($value instanceof \Carbon\CarbonInterface) { $value = $value->format($type === 'time' ? 'H:i' : 'Y-m-d'); }
                if (is_bool($value)) { $value = $value ? '1' : '0'; }
            @endphp
            <label class="{{ $type === 'textarea' ? 'full' : '' }}">
                <span>{{ $field['label'] }}</span>
                @if($type === 'textarea')
                    <textarea name="{{ $name }}" rows="4">{{ $value }}</textarea>
                @elseif($type === 'select')
                    <select name="{{ $name }}">
                        @foreach($field['options'] as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                @elseif($type === 'employee_select')
                    <select name="{{ $name }}" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected((string) $value === (string) $employee->id)>{{ $employee->display_name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="{{ $type === 'money' ? 'number' : $type }}" name="{{ $name }}" value="{{ $value }}" step="{{ $field['step'] ?? ($type === 'money' ? '0.001' : '1') }}">
                @endif
                @error($name)<em>{{ $message }}</em>@enderror
            </label>
        @endforeach

        <div class="form-actions full">
            <button class="btn btn-primary" type="submit">حفظ البيانات</button>
            <a class="btn btn-light" href="{{ route($routeName.'.index') }}">إلغاء</a>
        </div>
    </form>
</section>
@endsection
