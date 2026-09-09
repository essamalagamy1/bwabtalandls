<div>
    {{-- ═══════════════════════════ Header ═══════════════════════════ --}}
    <x-header title="لوحة ولي الأمر" subtitle="متابعة أداء أبنائك" separator>
        <x-slot:actions>
            @if($childrenCount > 1)
                {{-- Child Switcher --}}
                <x-select
                    wire:model.live="selectedChildId"
                    :options="$children"
                    option-value="id"
                    option-label="name"
                    placeholder="اختر الطالب..."
                    class="select-sm w-44"
                    icon="o-user-circle"
                />
            @endif
        </x-slot:actions>
    </x-header>

    {{-- ═══ Ticket Statistics ═══ --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <x-icon name="o-ticket" class="w-6 h-6 text-primary" />
            إحصائيات تذاكر الدعم الفني
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-stat title="إجمالي التذاكر" value="{{ $ticketStats['total'] ?? 0 }}" icon="o-ticket" class="text-primary shadow-sm" />
            <x-stat title="التذاكر المفتوحة" value="{{ $ticketStats['open'] ?? 0 }}" icon="o-envelope-open" class="text-success shadow-sm" />
            <x-stat title="قيد المراجعة" value="{{ $ticketStats['in_progress'] ?? 0 }}" icon="o-arrow-path" class="text-warning shadow-sm" />
            <x-stat title="التذاكر المغلقة" value="{{ $ticketStats['closed'] ?? 0 }}" icon="o-check-circle" class="text-neutral shadow-sm" />
        </div>
    </div>

    @if($children->isEmpty())
        {{-- No children linked --}}
        <x-alert
            title="لا يوجد أبناء مرتبطون بحسابك حتى الآن."
            description="يرجى التواصل مع إدارة المنصة لربط أبنائك بحسابك."
            icon="o-information-circle"
            class="alert-info"
        />
    @else
        @if($child)
            {{-- ═══ Child Profile Card + Quick Stats ═══ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                {{-- Profile Card --}}
                <div class="card bg-base-100 shadow-xl lg:col-span-1">
                    <div class="card-body items-center text-center">
                        <x-avatar :image="$child->getFirstMediaUrl('image')" class="!w-24 !h-24 mb-4" />
                        <h2 class="card-title text-2xl">{{ $child->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $child->email }}</p>
                        <div class="badge {{ $child->status === 'active' ? 'badge-success' : ($child->status === 'inactive' ? 'badge-error' : 'badge-warning') }} mt-2">
                            {{ $child->status === 'active' ? 'مفعل' : ($child->status === 'inactive' ? 'معطل' : 'قيد الانتظار') }}
                        </div>

                        <div class="w-full mt-6 space-y-2 text-sm text-start">
                            <div class="flex justify-between border-b border-base-300 pb-2">
                                <span class="font-bold text-gray-500">رقم الجوال</span>
                                <span dir="ltr">{{ $child->phone_key }}{{ $child->phone }}</span>
                            </div>
                            <div class="flex justify-between border-b border-base-300 pb-2">
                                <span class="font-bold text-gray-500">المرحلة</span>
                                <span>{{ $child->grade?->stage?->name ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-base-300 pb-2">
                                <span class="font-bold text-gray-500">الصف</span>
                                <span>{{ $child->grade?->name ?? '---' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-base-300 pb-2">
                                <span class="font-bold text-gray-500">الشعبة</span>
                                <span>{{ $child->section?->name ?? '---' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats + Charts --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Stats Row --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <x-stat title="إجمالي الامتحانات" value="{{ $totalExamsTaken }}" icon="o-document-text" class="shadow-md bg-base-100" />
                        <x-stat title="متوسط الدرجات" value="{{ $averageScore }}%" icon="o-star" class="shadow-md bg-base-100" />
                        <x-stat title="ناجح" value="{{ $passedCount }}" icon="o-check-circle" class="shadow-md bg-base-100 text-success" />
                        <x-stat title="راسب" value="{{ $failedCount }}" icon="o-x-circle" class="shadow-md bg-base-100 text-error" />
                    </div>

                    {{-- Charts --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="card bg-base-100 shadow-sm p-4">
                            <h3 class="font-bold mb-4">مخطط تقدم الدرجات</h3>
                            @if($totalExamsTaken > 0)
                                <x-chart wire:model="progressChart" />
                            @else
                                <p class="text-center text-gray-500 py-10">لا توجد بيانات بعد</p>
                            @endif
                        </div>
                        <div class="card bg-base-100 shadow-sm p-4">
                            <h3 class="font-bold mb-4">حالة الامتحانات</h3>
                            @if($totalExamsTaken > 0)
                                <x-chart wire:model="statusChart" />
                            @else
                                <p class="text-center text-gray-500 py-10">لا توجد بيانات بعد</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Recent Exams Table ═══ --}}
            <x-card title="أحدث الامتحانات" class="shadow-xl">
                @if($allAttempts->isEmpty())
                    <x-alert title="لا يوجد امتحانات تم أداؤها حتى الآن." icon="o-information-circle" class="alert-info" />
                @else
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>الامتحان</th>
                                    <th>التاريخ</th>
                                    <th>الدرجة</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allAttempts as $attempt)
                                    <tr>
                                        <td>{{ $attempt->exam?->title }}</td>
                                        <td>{{ $attempt->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $attempt->total_score }}</td>
                                        <td>
                                            <div class="badge {{ $attempt->status === 'passed' ? 'badge-success' : ($attempt->status === 'failed' ? 'badge-error' : 'badge-warning') }}">
                                                {{ $attempt->status === 'passed' ? 'ناجح' : ($attempt->status === 'failed' ? 'راسب' : 'قيد الإجراء') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        @endif
    @endif
</div>
