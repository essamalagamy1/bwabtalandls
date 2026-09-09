<div>
    <x-header title="تذاكر الدعم" subtitle="إدارة التذاكر الخاصة بك">
        <x-slot:actions>
            <x-button icon="o-plus" class="btn-primary" @click="$wire.modalAdd = true" label="فتح تذكرة جديدة" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <x-input icon="o-magnifying-glass" wire:model.live.debounce="search" placeholder="ابحث بعنوان التذكرة..." clearable />
            
            <x-select wire:model.live="statusFilter" :options="[
                ['id' => 'open', 'name' => 'مفتوحة'],
                ['id' => 'in_progress', 'name' => 'قيد المراجعة'],
                ['id' => 'closed', 'name' => 'مغلقة'],
            ]" option-value="id" option-label="name" placeholder="كل الحالات" clearable />
        </div>

        <x-table :headers="$headers" :rows="$tickets" with-pagination>
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
                <x-button icon="o-eye" link="{{ route('user.tickets.show', $ticket) }}" class="btn-sm btn-circle btn-ghost text-primary" tooltip="عرض التذكرة" />
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="modalAdd" title="فتح تذكرة دعم جديدة">
        <x-form wire:submit="saveAdd">
            <x-input label="عنوان التذكرة" wire:model="subject" required />
            <x-select label="نوع التذكرة" wire:model="type" :options="[
                ['id' => 'problem', 'name' => 'مشكلة'],
                ['id' => 'suggestion', 'name' => 'اقتراح'],
                ['id' => 'inquiry', 'name' => 'استفسار'],
            ]" option-value="id" option-label="name" required />
            
            <x-slot:actions>
                <x-button label="إلغاء" @click="$wire.modalAdd = false" />
                <x-button label="إنشاء" class="btn-primary" type="submit" spinner="saveAdd" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
