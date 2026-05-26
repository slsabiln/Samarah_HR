@extends('layouts.app', ['pageTitle' => 'التقارير والتحليلات'])

@section('content')
<section class="page-head">
    <div>
        <h2>التقارير والتحليلات</h2>
        <p>مؤشرات مختصرة للإدارة العليا لدعم اتخاذ القرار.</p>
    </div>
</section>

<section class="stats-grid">
    @foreach($cards as $card)
        <article class="stat-card wide">
            <span>{{ $card['title'] }}</span>
            <strong>{{ $card['value'] }}</strong>
            <small>يتم احتساب المؤشر من بيانات النظام الحالية</small>
        </article>
    @endforeach
</section>
@endsection
