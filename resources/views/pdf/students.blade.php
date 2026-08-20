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

    <h2>تقرير بيانات الطلاب</h2>

    <div style="margin-bottom: 20px;">
        <p><strong>تاريخ وتوقت الطباعة:</strong> <span dir="ltr">{{ $date ?? now()->format('Y-m-d H:i') }}</span></p>
        <p><strong>الفلاتر المستخدمة:</strong> {{ $filtersText ?? 'الكل' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الإيميل</th>
                <th>رقم الهاتف</th>
                <th>المرحلة الدراسية</th>
                <th>الصف الدراسي</th>
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
                    <td>{{ $statusLabel }}</td>
                    <td dir="ltr">{{ $student->created_at ? $student->created_at->format('Y-m-d') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
