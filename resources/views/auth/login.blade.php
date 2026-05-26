@extends('layouts.app')

@section('content')
<section class="login-screen">
    <div class="login-shell">
        <div class="login-visual" aria-hidden="true">
            <div class="login-visual__header">
                <span class="brand__mark">HR</span>
                <div>
                    <strong>منصة موارد بشرية عملية</strong>
                    <small>جاهزة لإدخال بياناتك الفعلية من الصفر</small>
                </div>
            </div>

            <div class="visual-grid">
                <div class="visual-card visual-card--large">
                    <span>إدارة الموظفين</span>
                    <strong>ملفات منظمة</strong>
                    <small>بيانات وظيفية، رواتب، وثائق، وحضور</small>
                </div>
                <div class="visual-card">
                    <span>الرواتب</span>
                    <strong>KWD</strong>
                    <small>احتساب شهري</small>
                </div>
                <div class="visual-card">
                    <span>الوثائق</span>
                    <strong>تنبيهات</strong>
                    <small>قبل الانتهاء</small>
                </div>
                <div class="visual-card visual-card--accent">
                    <span>Audit Trail</span>
                    <strong>سجل تدقيق</strong>
                    <small>متابعة كل عملية</small>
                </div>
            </div>
        </div>

        <div class="login-card glass-card">
            <div class="brand brand--center">
                <span class="brand__mark">HR</span>
                <div>
                    <strong>نظام الموارد البشرية</strong>
                    <small>مستخدم النظام: نبيل السنفي</small>
                </div>
            </div>

            <h2>تسجيل الدخول</h2>
            <p class="muted">أدخل بيانات الحساب للدخول إلى النظام.</p>

            <form method="POST" action="{{ route('login.store') }}" class="form-grid one" autocomplete="on">
                @csrf
                <label>
                    <span>البريد الإلكتروني</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="example@company.com" required autofocus autocomplete="username">
                </label>
                <label>
                    <span>كلمة المرور</span>
                    <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                </label>
                <label class="check-line">
                    <input type="checkbox" name="remember" value="1">
                    <span>تذكرني</span>
                </label>
                <button class="btn btn-primary btn-block" type="submit">دخول النظام</button>
            </form>
        </div>
    </div>
</section>
@endsection
