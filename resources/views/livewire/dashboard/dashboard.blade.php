<div>
	@role('student')
		<livewire:student.student-dashboard />
	@else
		<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

			{{-- ═══════════════════════ Filters ═══════════════════════ --}}
			<x-card shadow class="!pb-2">
				<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
					<x-select
						label="{{ __('lang.stages') }}"
						wire:model.live="stage_id"
						:options="$stages"
						option-value="id"
						option-label="name"
						placeholder="{{ __('lang.all') }}"
						icon="o-academic-cap"
					/>
					<x-select
						label="{{ __('lang.grades') }}"
						wire:model.live="grade_id"
						:options="$grades"
						option-value="id"
						option-label="name"
						placeholder="{{ __('lang.all') }}"
						icon="o-bookmark"
						:disabled="!$stage_id"
					/>
					<x-select
						label="{{ __('lang.sections') }}"
						wire:model.live="section_id"
						:options="$sections"
						option-value="id"
						option-label="name"
						placeholder="{{ __('lang.all') }}"
						icon="o-user-group"
						:disabled="!$grade_id"
					/>
					<x-select
						label="{{ __('lang.semesters') }}"
						wire:model.live="semester_id"
						:options="$semesters"
						option-value="id"
						option-label="name"
						placeholder="{{ __('lang.all') }}"
						icon="o-calendar-days"
						:disabled="!$grade_id"
					/>
				</div>
			</x-card>

			{{-- ═══════════════════════ Stats Row 1: Students ═══════════════════════ --}}
			<div class="grid auto-rows-min gap-4 grid-cols-1 md:grid-cols-3">
				{{-- Total Students --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-indigo-500/10 to-indigo-500/5">
					<x-stat title="{{ __('lang.total_students') }}" value="{{ $totalStudents }}" icon="o-users" color="text-indigo-500" class="!border-0" />
				</div>

				{{-- Active Students --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-emerald-500/10 to-emerald-500/5">
					<x-stat title="{{ __('lang.active_students') }}" value="{{ $activeStudents }}" icon="o-user-group" color="text-emerald-500" class="!border-0" />
				</div>
				
				{{-- Inactive Students --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-red-500/10 to-red-500/5">
					<x-stat title="{{ __('lang.inactive_students') }}" value="{{ $inactiveStudents }}" icon="o-user-minus" color="text-red-500" class="!border-0" />
				</div>
			</div>

			{{-- ═══════════════════════ Stats Row 2: Structure ═══════════════════════ --}}
			<div class="grid auto-rows-min gap-4 grid-cols-2 md:grid-cols-4">
				{{-- Total Stages --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-violet-500/10 to-violet-500/5">
					<x-stat title="{{ __('lang.total_stages') }}" value="{{ $totalStages }}" icon="o-academic-cap" color="text-violet-500" class="!border-0" />
				</div>

				{{-- Total Grades --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-sky-500/10 to-sky-500/5">
					<x-stat title="{{ __('lang.total_grades') }}" value="{{ $totalGrades }}" icon="o-bookmark" color="text-sky-500" class="!border-0" />
				</div>
				
				{{-- Total Semesters --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-amber-500/10 to-amber-500/5">
					<x-stat title="{{ __('lang.total_semesters') }}" value="{{ $totalSemesters }}" icon="o-calendar-days" color="text-amber-500" class="!border-0" />
				</div>

				{{-- Total Weeks --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-teal-500/10 to-teal-500/5">
					<x-stat title="{{ __('lang.total_weeks') }}" value="{{ $totalWeeks }}" icon="o-clock" color="text-teal-500" class="!border-0" />
				</div>
			</div>
			
			{{-- ═══════════════════════ Stats Row 3: Exams ═══════════════════════ --}}
			<div class="grid auto-rows-min gap-4 grid-cols-2 md:grid-cols-4">
				{{-- Total Trainings --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-rose-500/10 to-rose-500/5">
					<x-stat title="{{ __('lang.total_trainings') }}" value="{{ $totalTrainings }}" icon="o-play-circle" color="text-rose-500" class="!border-0" />
				</div>

				{{-- Total Exams --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-orange-500/10 to-orange-500/5">
					<x-stat title="{{ __('lang.total_exams') }}" value="{{ $totalExams }}" icon="o-document-text" color="text-orange-500" class="!border-0" />
				</div>

				{{-- Total Questions --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-pink-500/10 to-pink-500/5">
					<x-stat title="{{ __('lang.total_questions') }}" value="{{ $totalQuestions }}" icon="o-question-mark-circle" color="text-pink-500" class="!border-0" />
				</div>

				{{-- Total Attempts --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-cyan-500/10 to-cyan-500/5">
					<x-stat title="{{ __('lang.total_attempts') }}" value="{{ $totalAttempts }}" icon="o-arrow-path" color="text-cyan-500" class="!border-0" />
				</div>
			</div>

			{{-- ═══════════════════════ Stats Row 4: Performance ═══════════════════════ --}}
			<div class="grid auto-rows-min gap-4 grid-cols-2 md:grid-cols-2">
				{{-- Average Score --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-yellow-500/10 to-yellow-500/5">
					<x-stat title="{{ __('lang.average_score') }}" value="{{ $avgScore }}%" icon="o-chart-bar" color="text-yellow-500" class="!border-0" />
				</div>

				{{-- Pass Rate --}}
				<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-gradient-to-br from-lime-500/10 to-lime-500/5">
					<x-stat title="{{ __('lang.pass_rate') }}" value="{{ $passRate }}%" icon="o-check-badge" color="text-lime-600" class="!border-0" />
				</div>
			</div>

			{{-- ═══════════════════════ Charts ═══════════════════════ --}}
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
				{{-- Students Per Grade --}}
				<x-card title="{{ __('lang.students_per_grade') }}" shadow separator icon="o-chart-bar">
					<x-chart wire:model="studentsPerGradeChart" />
				</x-card>

				{{-- Student Status Distribution --}}
				<x-card title="{{ __('lang.student_status_distribution') }}" shadow separator icon="o-chart-pie">
					<div class="max-w-xs mx-auto">
						<x-chart wire:model="studentStatusChart" />
					</div>
				</x-card>

				{{-- Exam Scores Average --}}
				<x-card title="{{ __('lang.exam_scores_avg') }}" shadow separator icon="o-chart-bar">
					<x-chart wire:model="examScoresChart" />
				</x-card>

				{{-- New Students Monthly --}}
				<x-card title="{{ __('lang.new_students_monthly') }}" shadow separator icon="o-arrow-trending-up">
					<x-chart wire:model="newStudentsMonthlyChart" />
				</x-card>
			</div>

			{{-- ═══════════════════════ Latest Students Table ═══════════════════════ --}}
			<x-card title="{{ __('lang.latest_students') }}" shadow separator icon="o-user-plus">
				<div class="overflow-x-auto">
					<table class="table">
						<thead class="bg-base-300 text-base-content">
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">{{ __('lang.name') }}</th>
							<th class="text-center">{{ __('lang.email') }}</th>
							<th class="text-center">{{ __('lang.grade') }}</th>
							<th class="text-center">{{ __('lang.stage') }}</th>
							<th class="text-center">{{ __('lang.status') }}</th>
							<th class="text-center">{{ __('lang.joined_at') }}</th>
						</tr>
						</thead>
						<tbody>
						@forelse($latestStudents as $index => $student)
							<tr class="bg-base-200 hover:bg-base-300 transition-colors">
								<th class="text-center">{{ $index + 1 }}</th>
								<td class="text-nowrap">
									<div class="flex items-center gap-2">
										@if($student->getFirstMediaUrl('image'))
											<div class="avatar">
												<div class="w-8 rounded-full">
													<img src="{{ $student->getFirstMediaUrl('image') }}" alt="{{ $student->name }}"/>
												</div>
											</div>
										@else
											<div class="avatar placeholder">
												<div class="bg-neutral text-neutral-content w-8 rounded-full">
													<span class="text-xs">{{ mb_substr($student->name, 0, 1) }}</span>
												</div>
											</div>
										@endif
										{{ $student->name }}
									</div>
								</td>
								<td class="text-center text-nowrap">{{ $student->email }}</td>
								<td class="text-center text-nowrap">{{ $student->grade?->name ?? '-' }}</td>
								<td class="text-center text-nowrap">{{ $student->grade?->stage?->name ?? '-' }}</td>
								<td class="text-center">
									@if($student->status === 'active')
										<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
									@elseif($student->status === 'inactive')
										<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
									@else
										<x-badge value="{{ __('lang.pending') }}" class="badge-warning"/>
									@endif
								</td>
								<td class="text-center text-nowrap">{{ $student->created_at->diffForHumans() }}</td>
							</tr>
						@empty
							<tr class="bg-base-200">
								<th colspan="7" class="text-center">{{ __('lang.no_data') }}</th>
							</tr>
						@endforelse
						</tbody>
					</table>
				</div>
			</x-card>
		</div>
	@endrole
</div>