<div>
	<x-header title="{{ __('lang.student_reports') }}" subtitle="Insights into student performance" separator />

	{{-- Filters --}}
	<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 bg-base-200 p-4 rounded-xl">
		<x-select label="{{ __('lang.stages') }}" wire:model.live="stage_id" :options="$stages" option-value="id" option-label="name" placeholder="{{ __('lang.stages') }}" />
		<x-select label="{{ __('lang.grades') }}" wire:model.live="grade_id" :options="$grades" option-value="id" option-label="name" placeholder="{{ __('lang.grades') }}" :disabled="!$stage_id" />
		<x-select label="{{ __('lang.semesters') }}" wire:model.live="semester_id" :options="$semesters" option-value="id" option-label="name" placeholder="{{ __('lang.semesters') }}" :disabled="!$grade_id" />
	</div>

	{{-- Top Stats --}}
	<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
		<x-stat title="{{ __('lang.total') }}" value="{{ $totalAttempts }}" icon="o-document-text" class="shadow-md" />
		<x-stat title="{{ __('lang.average_score') }}" value="{{ round($avgScore, 2) }}%" icon="o-star" color="text-warning" class="shadow-md" />
		<x-stat title="{{ __('lang.pass_fail_ratio') }}" value="{{ $totalAttempts > 0 ? round(($passFailChart['data']['datasets'][0]['data'][0] / $totalAttempts) * 100) : 0 }}%" icon="o-check-circle" color="text-success" class="shadow-md" />
	</div>

	{{-- Charts --}}
	<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
		<x-card title="{{ __('lang.pass_fail_ratio') }}" class="shadow-md">
			<x-chart wire:model="passFailChart" />
		</x-card>

		<x-card title="{{ __('lang.performance_over_time') }}" class="shadow-md lg:col-span-2">
			<x-chart wire:model="performanceChart" />
		</x-card>
	</div>

	{{-- Tables --}}
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
		<x-card title="{{ __('lang.top_students') }}" class="shadow-md" icon="o-trophy">
			<table class="table w-full">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ __('lang.student') }}</th>
						<th>{{ __('lang.average_score') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($topStudents as $index => $student)
						<tr>
							<td>{{ $index + 1 }}</td>
							<td>
								<div class="flex items-center gap-2">
									@if($student->getFirstMediaUrl('image'))
										<div class="avatar"><div class="w-8 rounded-full"><img src="{{ $student->getFirstMediaUrl('image') }}" /></div></div>
									@endif
									{{ $student->name }}
								</div>
							</td>
							<td class="font-bold text-success">{{ round($student->exam_attempts_avg_total_score, 2) }}%</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</x-card>

		<x-card title="{{ __('lang.students_needing_improvement') }}" class="shadow-md" icon="o-arrow-trending-down">
			<table class="table w-full">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ __('lang.student') }}</th>
						<th>{{ __('lang.average_score') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($weakStudents as $index => $student)
						<tr>
							<td>{{ $index + 1 }}</td>
							<td>
								<div class="flex items-center gap-2">
									@if($student->getFirstMediaUrl('image'))
										<div class="avatar"><div class="w-8 rounded-full"><img src="{{ $student->getFirstMediaUrl('image') }}" /></div></div>
									@endif
									{{ $student->name }}
								</div>
							</td>
							<td class="font-bold text-error">{{ round($student->exam_attempts_avg_total_score, 2) }}%</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</x-card>
	</div>
</div>
