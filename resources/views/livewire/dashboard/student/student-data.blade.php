<div>
	<x-card title="{{ __('lang.students') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_student')
				<livewire:dashboard.student.create-student :all_grades="$all_grades" wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_name" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.grade') }}" wire:model.live="search_grade_id" :options="$all_grades" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-select label="{{ __('lang.status') }}" wire:model.live="search_status" :options="[
                ['id' => '', 'name' => __('lang.all')],
                ['id' => 'active', 'name' => __('lang.active')],
                ['id' => 'inactive', 'name' => __('lang.inactive')],
                ['id' => 'pending', 'name' => __('lang.pending')],
            ]" option-value="id" option-label="name"/>
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
									@can('edit_student')
										<livewire:dashboard.student.update-student :student="$student" :all_grades="$all_grades" :key="\Illuminate\Support\Str::random(10)"/>
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
