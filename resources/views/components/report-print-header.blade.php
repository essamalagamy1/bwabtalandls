@props([
    'title' => 'تقرير إحصائي وتحليلي',
    'selectedStage' => null,
    'selectedGrade' => null,
    'selectedSection' => null,
    'selectedSemester' => null,
    'selectedStudent' => null,
])

<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm 10mm;
        }

        /* Hide screen-only interactive elements */
        nav,
        aside,
        footer,
        .no-print,
        .navbar,
        .drawer-side,
        .breadcrumbs,
        .btn,
        button,
        .toast,
        .dropdown,
        .alert,
        input,
        select {
            display: none !important;
        }

        /* Reset containers */
        body, html, main, .drawer-content, .flex-grow {
            background: #ffffff !important;
            background-color: #ffffff !important;
            color: #111827 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            box-shadow: none !important;
        }

        /* Display print-only header */
        .print-only {
            display: block !important;
        }

        /* Prevent ugly page breaks */
        .card, [class*="rounded-xl"], tr, .grid, canvas {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        /* Exact colors */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* Table styling */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        th, td {
            border: 1px solid #d1d5db !important;
            padding: 5px 8px !important;
            font-size: 11px !important;
        }
        thead {
            display: table-header-group !important;
            background-color: #f3f4f6 !important;
        }

        /* Charts limit height */
        canvas {
            max-height: 220px !important;
            width: 100% !important;
        }
    }

    @media screen {
        .print-only {
            display: none !important;
        }
    }
</style>

<div class="print-only mb-6 border-b-2 border-neutral-800 pb-4">
    {{-- Header Banner --}}
    <div class="flex items-center justify-between gap-4 mb-4">
        {{-- Site Brand & Logo --}}
        <div class="flex items-center gap-3">
            @php
                $logoUrl = siteSetting()->getFirstMediaUrl('logo_black') ?: asset('logo.png');
            @endphp
            <img src="{{ $logoUrl }}" alt="Logo" class="h-16 w-auto object-contain" />
            <div>
                <h2 class="text-xl font-extrabold text-neutral-900">{{ siteSetting()->name ?? config('app.name', 'بوابة الأندلس') }}</h2>
                <p class="text-xs text-neutral-500 font-medium">{{ config('app.url') }}</p>
            </div>
        </div>

        {{-- Report Title & Date --}}
        <div class="text-left">
            <h1 class="text-lg font-black text-neutral-900">{{ $title }}</h1>
            <div class="text-xs text-neutral-600 mt-1 space-y-0.5">
                <p><span class="font-bold text-neutral-700">تاريخ الطباعة:</span> {{ now()->translatedFormat('Y-m-d h:i A') }}</p>
                <p><span class="font-bold text-neutral-700">طبع بواسطة:</span> {{ auth()->user()?->name ?? 'المسؤول' }}</p>
            </div>
        </div>
    </div>

    {{-- Applied Filters Summary Box --}}
    <div class="bg-neutral-100 rounded-lg p-3 border border-neutral-300 text-xs">
        <div class="font-bold text-neutral-800 mb-2 flex items-center gap-1.5">
            <span>{{ __('lang.applied_filters_at_print') }}:</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 {{ $selectedStudent ? 'md:grid-cols-5' : '' }} gap-2 text-neutral-800">
            <div class="p-2 bg-white rounded border border-neutral-300">
                <span class="font-semibold text-neutral-500 block mb-0.5">{{ __('lang.stage') }}:</span>
                <span class="font-bold text-neutral-900">{{ $selectedStage ?: __('lang.all_stages') }}</span>
            </div>
            <div class="p-2 bg-white rounded border border-neutral-300">
                <span class="font-semibold text-neutral-500 block mb-0.5">{{ __('lang.grade') }}:</span>
                <span class="font-bold text-neutral-900">{{ $selectedGrade ?: __('lang.all_grades') }}</span>
            </div>
            <div class="p-2 bg-white rounded border border-neutral-300">
                <span class="font-semibold text-neutral-500 block mb-0.5">{{ __('lang.section') }}:</span>
                <span class="font-bold text-neutral-900">{{ $selectedSection ?: __('lang.all_sections') }}</span>
            </div>
            <div class="p-2 bg-white rounded border border-neutral-300">
                <span class="font-semibold text-neutral-500 block mb-0.5">{{ __('lang.semester') }}:</span>
                <span class="font-bold text-neutral-900">{{ $selectedSemester ?: __('lang.all_semesters') }}</span>
            </div>
            @if($selectedStudent)
                <div class="p-2 bg-white rounded border border-indigo-300">
                    <span class="font-semibold text-indigo-500 block mb-0.5">{{ __('lang.student') }}:</span>
                    <span class="font-bold text-indigo-900">{{ $selectedStudent }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
