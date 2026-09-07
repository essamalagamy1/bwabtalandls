<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>بيانات الطلاب</title>
    <style>
        body {
            font-family: sans-serif;
            direction: rtl;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border: none; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px;">
        <tr>
            <td style="border: none; text-align: right; width: 50%; vertical-align: top;">
                @php
                    $logoUrl = siteSetting()->getFirstMediaUrl('logo_black') ?: asset('logo.png');
                @endphp
                <img src="{{ $logoUrl }}" alt="Logo" style="height: 60px; max-width: 150px; object-fit: contain;">
                <h3 style="margin: 10px 0 0 0; font-size: 18px;">{{ siteSetting()->name ?? config('app.name', 'بوابة الأندلس') }}</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #555;">{{ config('app.url') }}</p>
            </td>
            <td style="border: none; text-align: left; width: 50%; vertical-align: bottom;">
                <h2 style="margin: 0 0 10px 0; text-align: left;">تقرير بيانات الطلاب</h2>
                <div style="font-size: 12px; color: #444; text-align: left;">
                    <p style="margin: 4px 0;"><strong>تاريخ الطباعة:</strong> <span dir="ltr">{{ $date ?? now()->format('Y-m-d H:i') }}</span></p>
                    <p style="margin: 4px 0;"><strong>طبع بواسطة:</strong> {{ auth()->user()?->name ?? 'المسؤول' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 20px; background-color: #f9f9f9; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
        <p style="margin: 0; font-size: 14px;"><strong>الفلاتر المستخدمة:</strong> {{ $filtersText ?? 'الكل' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الإيميل</th>
                <th>رقم الهاتف</th>
                <th>المرحلة الدراسية</th>
                <th>الصف الدراسي</th>
                <th>الشعبة</th>
                <th>حالة الحساب</th>
                <th>تاريخ الانضمام</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                @php
                    $statusLabel = '';
                    if ($student->status === 'active') {
                        $statusLabel = 'مفعل';
                    } elseif ($student->status === 'inactive') {
                        $statusLabel = 'غير مفعل';
                    } else {
                        $statusLabel = 'قيد الانتظار';
                    }
                @endphp
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td dir="ltr">{{ $student->full_phone ?? '-' }}</td>
                    <td>{{ $student->grade?->stage?->name ?? '-' }}</td>
                    <td>{{ $student->grade?->name ?? '-' }}</td>
                    <td>{{ $student->section?->name ?? '-' }}</td>
                    <td>{{ $statusLabel }}</td>
                    <td dir="ltr">{{ $student->created_at ? $student->created_at->format('Y-m-d') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
