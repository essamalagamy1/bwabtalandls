<div>
	<x-header title="{{ __('lang.exam_reports') }}" subtitle="Deep dive into exam statistics" separator />

	{{-- Filters --}}
	<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 bg-base-200 p-4 rounded-xl">
		<x-select label="{{ __('lang.stages') }}" wire:model.live="stage_id" :options="$stages" option-value="id" option-label="name" placeholder="{{ __('lang.stages') }}" />
		<x-select label="{{ __('lang.grades') }}" wire:model.live="grade_id" :options="$grades" option-value="id" option-label="name" placeholder="{{ __('lang.grades') }}" :disabled="!$stage_id" />
		<x-select label="{{ __('lang.semesters') }}" wire:model.live="semester_id" :options="$semesters" option-value="id" option-label="name" placeholder="{{ __('lang.semesters') }}" :disabled="!$grade_id" />
	</div>

	{{-- Top Stats --}}
	<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
		<x-stat title="{{ __('lang.exams') }}" value="{{ $totalExams }}" icon="o-document-text" class="shadow-md" />
		<x-stat title="{{ __('lang.exam_attempts_mng') }}" value="{{ $totalAttempts }}" icon="o-users" color="text-info" class="shadow-md" />
	</div>

	{{-- Charts --}}
	<div class="mb-8">
		<x-card title="{{ __('lang.average_score') }} (Top 10 Recent Exams)" class="shadow-md">
			<x-chart wire:model="averageScoreChart" />
		</x-card>
	</div>

	{{-- Tables --}}
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
		<x-card title="{{ __('lang.hardest_questions') }}" class="shadow-md border-t-4 border-error" icon="o-x-circle">
			<table class="table w-full text-sm">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ __('lang.question') }}</th>
						<th>{{ __('lang.pass_fail_ratio') }}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($hardestQuestions as $index => $item)
						<tr>
							<td>{{ $index + 1 }}</td>
							<td>{{ Str::limit($item->question?->question_text, 50) }}</td>
							<td class="font-bold text-error">{{ round(($item->correct_count / $item->total_attempts) * 100) }}%</td>
						</tr>
					@empty
						<tr><td colspan="3" class="text-center">{{ __('lang.no_data') }}</td></tr>
					@endforelse
				</tbody>
			</table>
		</x-card>

		<x-card title="{{ __('lang.easiest_questions') }}" class="shadow-md border-t-4 border-success" icon="o-check-circle">
			<table class="table w-full text-sm">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ __('lang.question') }}</th>
						<th>{{ __('lang.pass_fail_ratio') }}</th>
					</tr>
				</thead>
				<tbody>
					@forelse($easiestQuestions as $index => $item)
						<tr>
							<td>{{ $index + 1 }}</td>
							<td>{{ Str::limit($item->question?->question_text, 50) }}</td>
							<td class="font-bold text-success">{{ round(($item->correct_count / $item->total_attempts) * 100) }}%</td>
						</tr>
					@empty
						<tr><td colspan="3" class="text-center">{{ __('lang.no_data') }}</td></tr>
					@endforelse
				</tbody>
			</table>
		</x-card>
	</div>

	<x-card title="{{ __('lang.exam_difficulty') }} (Ranked by Failure)" class="shadow-md mb-8">
		<table class="table w-full">
			<thead>
				<tr>
					<th>#</th>
					<th>{{ __('lang.exam') }}</th>
					<th>{{ __('lang.pass_fail_ratio') }}</th>
				</tr>
			</thead>
			<tbody>
				@forelse($difficultExams as $index => $exam)
					<tr>
						<td>{{ $index + 1 }}</td>
						<td>{{ $exam->title }}</td>
						<td class="font-bold text-warning">{{ round(($exam->pass_count / $exam->attempts_count) * 100) }}% ({{ $exam->attempts_count }} {{ __('lang.attempts') ?? 'attempts' }})</td>
					</tr>
				@empty
					<tr><td colspan="3" class="text-center">{{ __('lang.no_data') }}</td></tr>
				@endforelse
			</tbody>
		</table>
	</x-card>
</div>
