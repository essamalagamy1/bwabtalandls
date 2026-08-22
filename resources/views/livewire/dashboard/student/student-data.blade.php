<div>
	<x-card title="{{ __('lang.students') }}" shadow class="mb-3">
		<x-slot:menu>
			<x-button icon="o-document-arrow-down" class="btn-success btn-sm" wire:click="exportExcel" tooltip="تصدير Excel" spinner="exportExcel" />
			<x-button icon="o-document-arrow-down" class="btn-error btn-sm" wire:click="exportPdf" tooltip="تصدير PDF" spinner="exportPdf" />
			@can('create_student')
				<livewire:dashboard.student.create-student wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
			<x-stat title="إجمالي الطلاب" value="{{ $total_students }}" icon="o-users" class="bg-base-200" />
			<x-stat title="الطلاب المفعلين" value="{{ $active_students }}" icon="o-check-circle" class="bg-success/20 text-success" />
			<x-stat title="الطلاب غير المفعلين" value="{{ $inactive_students }}" icon="o-x-circle" class="bg-error/20 text-error" />
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_name" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.stage') }}" wire:model.live="search_stage_id" :options="$all_stages" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{ __('lang.grade') }}" wire:model.live="search_grade_id" :options="$all_grades" option-sub-label="full_path_name" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{ __('lang.section') }}" wire:model.live="search_section_id" :options="$all_sections" option-sub-label="full_path_name" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{ __('lang.semester') }}" wire:model.live="search_semester_id" :options="$all_semesters" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{ __('lang.week') }}" wire:model.live="search_week_id" :options="$all_weeks" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-select label="{{ __('lang.status') }}" wire:model.live="search_status" :options="[
                ['id' => '', 'name' => __('lang.all')],
                ['id' => 'active', 'name' => __('lang.active')],
                ['id' => 'inactive', 'name' => __('lang.inactive')],
                ['id' => 'pending', 'name' => __('lang.pending')],
            ]" option-value="id" option-label="name" />
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{ __('lang.name') }}</th>
						<th class="text-center">{{ __('lang.email') }}</th>
						<th class="text-center">{{ __('lang.grade') }}</th>
						<th class="text-center">{{ __('lang.section') }}</th>
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.created_at') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($students as $student)
						<tr class="bg-base-200">
							<th class="text-center">{{ $students->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">
								<div class="flex items-center gap-2">
									@if($student->getFirstMediaUrl('image'))
										<div class="avatar">
											<div class="w-8 rounded-full">
												<img src="{{ $student->getFirstMediaUrl('image') }}" alt="{{ $student->name }}"/>
											</div>
										</div>
									@endif
									{{ $student->name }}
								</div>
							</td>
							<td class="text-center text-nowrap">{{ $student->email }}</td>
							<td class="text-center text-nowrap">{{ $student->grade?->name ?? '-' }}</td>
							<td class="text-center text-nowrap">{{ $student->section?->name ?? '-' }}</td>
							<td class="text-center">
								@if($student->status === 'active')
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@elseif($student->status === 'inactive')
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@else
									<x-badge value="{{ __('lang.pending') }}" class="badge-warning"/>
								@endif
							</td>
							<td class="text-center text-nowrap">{{ formatDate($student->created_at, true) }}</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('show_student')
										<x-button 
											icon="o-chart-bar" 
											class="btn-sm btn-ghost text-info" 
											link="{{ route('students.profile', $student->id) }}" 
											tooltip="{{ __('lang.student_profile') ?? 'ملف الطالب' }}" 
											wire:navigate 
										/>
									@endcan
									@can('edit_student')
										<x-button 
											icon="{{ $student->status === 'active' ? 'o-x-circle' : 'o-check-circle' }}" 
											class="btn-sm btn-ghost {{ $student->status === 'active' ? 'text-error' : 'text-success' }}" 
											wire:click="toggleStatus({{ $student->id }})" 
											spinner="toggleStatus({{ $student->id }})" 
											tooltip="{{ $student->status === 'active' ? __('lang.inactive') : __('lang.active') }}" 
										/>
										<livewire:dashboard.student.update-student :student="$student" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_student')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $student->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.student')]) }}"
											spinner="delete({{ $student->id }})"
											tooltip="{{ __('lang.delete') }}"/>
									@endcan
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="7" class="text-center">{{ __('lang.no_data') }}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6">
					<div class="w-full flex-none">{{ $students->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
