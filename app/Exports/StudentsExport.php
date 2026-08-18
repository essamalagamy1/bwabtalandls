<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;
    protected $filtersText;

    public function __construct(Builder $query, string $filtersText = '')
    {
        $this->query = $query;
        $this->filtersText = $filtersText;
    }

    public function query(): \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation
    {
        // Add eager loading for relationships
        return $this->query->with('grade.stage');
    }

    public function headings(): array
    {
        return [
            ['تاريخ التصدير: ' . now()->format('Y-m-d H:i')],
            ['الفلاتر المستخدمة: ' . $this->filtersText],
            [],
            [
                __('lang.name'),
                __('lang.email'),
                __('lang.phone'),
                __('lang.stage'),
                __('lang.grade'),
                __('lang.status'),
                __('lang.created_at'),
            ]
        ];
    }

    public function map($student): array
    {
        // Determine status in Arabic/Local language
        $statusLabel = '';
        if ($student->status === 'active') {
            $statusLabel = __('lang.active');
        } elseif ($student->status === 'inactive') {
            $statusLabel = __('lang.inactive');
        } else {
            $statusLabel = __('lang.pending');
        }

        return [
            $student->name,
            $student->email,
            $student->full_phone ?? '-',
            $student->grade?->stage?->name ?? '-',
            $student->grade?->name ?? '-',
            $statusLabel,
            $student->created_at ? $student->created_at->format('Y-m-d') : '-',
        ];
    }
}
