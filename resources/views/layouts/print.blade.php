<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'تقرير' }} - {{ siteSetting()->name ?? config('app.name', 'بوابة الأندلس') }}</title>
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN for standalone print styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 8mm 10mm 8mm;
            }

            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            .break-inside-avoid {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            th, td {
                border: 1px solid #cbd5e1 !important;
                padding: 4px 6px !important;
                font-size: 11px !important;
            }
            thead {
                display: table-header-group !important;
                background-color: #f1f5f9 !important;
            }
        }
    </style>
</head>
<body class="p-4 md:p-8 min-h-screen">

    <!-- Screen Control Bar (Hidden in Print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 bg-white p-4 rounded-xl shadow-md border border-slate-200 flex flex-wrap items-center justify-between gap-4 sticky top-4 z-50">
        <div class="flex items-center gap-3">
            <span class="inline-flex p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            </span>
            <div>
                <h3 class="font-bold text-slate-800 text-base">معاينة الطباعة للتقرير</h3>
                <p class="text-xs text-slate-500">جاهز للطباعة أو الحفظ بصيغة PDF</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition-all flex items-center gap-2 text-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                طباعة الآن
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-all text-sm cursor-pointer">
                إغلاق النافذة
            </button>
        </div>
    </div>

    <!-- Main Printable Paper Container -->
    <div class="print-container max-w-5xl mx-auto bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
        
        <!-- Header Banner -->
        <header class="border-b-2 border-slate-800 pb-5 mb-6">
            <div class="flex items-center justify-between gap-4 mb-4">
                <!-- Site Identity -->
                <div class="flex items-center gap-3.5">
                    @php
                        $logoUrl = siteSetting()->getFirstMediaUrl('logo_black') ?: asset('logo.png');
                    @endphp
                    <img src="{{ $logoUrl }}" alt="Logo" class="h-16 w-auto max-w-[140px] object-contain" />
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ siteSetting()->name ?? config('app.name', 'بوابة الأندلس التعليمية') }}</h1>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ config('app.url') }}</p>
                    </div>
                </div>

                <!-- Report Title & Date -->
                <div class="text-left">
                    <span class="inline-block px-3 py-1 bg-slate-900 text-white font-bold text-xs rounded mb-1.5">تقرير رسمي</span>
                    <h2 class="text-lg font-black text-slate-900">{{ $title ?? 'تقرير إحصائي' }}</h2>
                    <div class="text-xs text-slate-600 mt-1 space-y-0.5">
                        <p><span class="font-bold text-slate-700">تاريخ الطباعة:</span> {{ now()->translatedFormat('Y-m-d h:i A') }}</p>
                        <p><span class="font-bold text-slate-700">طبع بواسطة:</span> {{ auth()->user()?->name ?? 'المسؤول' }}</p>
                    </div>
                </div>
            </div>

            <!-- Applied Filters Summary Box -->
            <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200 text-xs">
                <div class="font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>الفلاتر المطبقة وقت استخراج التقرير:</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-slate-800">
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-semibold text-slate-400 block text-[10px] mb-0.5">المرحلة الدراسية:</span>
                        <span class="font-bold text-slate-900">{{ $selectedStage ?: 'جميع المراحل' }}</span>
                    </div>
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-semibold text-slate-400 block text-[10px] mb-0.5">الصف الدراسي:</span>
                        <span class="font-bold text-slate-900">{{ $selectedGrade ?: 'جميع الصفوف' }}</span>
                    </div>
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-semibold text-slate-400 block text-[10px] mb-0.5">الشعبة الدراسية:</span>
                        <span class="font-bold text-slate-900">{{ $selectedSection ?: 'جميع الشعب' }}</span>
                    </div>
                    <div class="p-2 bg-white rounded-lg border border-slate-200">
                        <span class="font-semibold text-slate-400 block text-[10px] mb-0.5">الفصل الدراسي:</span>
                        <span class="font-bold text-slate-900">{{ $selectedSemester ?: 'جميع الفصول' }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="space-y-6">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="mt-8 pt-4 border-t border-slate-200 text-center text-xs text-slate-400 flex items-center justify-between">
            <p>{{ siteSetting()->name ?? config('app.name') }} &copy; {{ date('Y') }} - جميع الحقوق محفوظة</p>
            <p>صفحة تقرير مستخرجة آلياً من النظام</p>
        </footer>

    </div>

</body>
</html>
