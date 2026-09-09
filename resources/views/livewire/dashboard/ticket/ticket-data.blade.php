<div>
    <x-header title="تذاكر الدعم" subtitle="إدارة جميع التذاكر من الطلاب وأولياء الأمور">
        <x-slot:actions>
            {{-- Add button if admin can create tickets for users, for now we just show filters --}}
        </x-slot:actions>
    </x-header>

    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <x-input icon="o-magnifying-glass" wire:model.live.debounce="search" placeholder="ابحث بعنوان التذكرة..." clearable />
            
            <x-select wire:model.live="statusFilter" :options="[
                ['id' => 'open', 'name' => 'مفتوحة'],
                ['id' => 'in_progress', 'name' => 'قيد المراجعة'],
                ['id' => 'closed', 'name' => 'مغلقة'],
            ]" option-value="id" option-label="name" placeholder="كل الحالات" clearable />

            <x-select wire:model.live="typeFilter" :options="[
                ['id' => 'problem', 'name' => 'مشكلة'],
                ['id' => 'suggestion', 'name' => 'اقتراح'],
                ['id' => 'inquiry', 'name' => 'استفسار'],
            ]" option-value="id" option-label="name" placeholder="كل الأنواع" clearable />

            <x-select wire:model.live="priorityFilter" :options="[
                ['id' => 'high', 'name' => 'عالية'],
                ['id' => 'medium', 'name' => 'متوسطة'],
                ['id' => 'low', 'name' => 'منخفضة'],
            ]" option-value="id" option-label="name" placeholder="كل الأولويات" clearable />

            <x-select wire:model.live="roleFilter" :options="[
                ['id' => 'student', 'name' => 'تذاكر الطلاب'],
                ['id' => 'parent', 'name' => 'تذاكر أولياء الأمور'],
            ]" option-value="id" option-label="name" placeholder="مقدم التذكرة (الكل)" clearable />
        </div>

        <x-table :headers="$headers" :rows="$tickets" with-pagination>
            @scope('cell_user', $ticket)
                <div class="flex items-center gap-2">
                    <x-avatar :image="$ticket->user->getFirstMediaUrl('image')" class="w-8 h-8" />
                    <div>
                        <div class="text-sm font-semibold">{{ $ticket->user->name }}</div>
                        <div class="text-xs text-base-content/70">
                            {{ $ticket->user->isParent() ? 'ولي أمر' : 'طالب' }}
                        </div>
                    </div>
                </div>
            @endscope

            @scope('cell_type', $ticket)
                @php
                    $typeColors = ['problem' => 'badge-error', 'suggestion' => 'badge-info', 'inquiry' => 'badge-warning'];
                    $typeNames = ['problem' => 'مشكلة', 'suggestion' => 'اقتراح', 'inquiry' => 'استفسار'];
                @endphp
                <span class="badge {{ $typeColors[$ticket->type] ?? 'badge-ghost' }} badge-sm">
                    {{ $typeNames[$ticket->type] ?? $ticket->type }}
                </span>
            @endscope

            @scope('cell_status', $ticket)
                @php
                    $statusColors = ['open' => 'badge-success', 'in_progress' => 'badge-warning', 'closed' => 'badge-ghost'];
                    $statusNames = ['open' => 'مفتوحة', 'in_progress' => 'قيد المراجعة', 'closed' => 'مغلقة'];
                @endphp
                <span class="badge {{ $statusColors[$ticket->status] ?? 'badge-ghost' }}">
                    {{ $statusNames[$ticket->status] ?? $ticket->status }}
                </span>
            @endscope

            @scope('actions', $ticket)
                <x-button icon="o-eye" link="{{ route('dashboard.tickets.show', $ticket) }}" class="btn-sm btn-circle btn-ghost text-primary" tooltip="عرض" />
                @can('delete_ticket')
                    <x-button icon="o-trash" wire:click="deleteTicket({{ $ticket->id }})" wire:confirm="هل أنت متأكد من حذف التذكرة؟" class="btn-sm btn-circle btn-ghost text-error" tooltip="حذف" />
                @endcan
            @endscope
        </x-table>
    </x-card>
</div>
