<div>
	<x-card title="{{ __('lang.exam_attempts_mng') }}" shadow class="mb-3">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
			<x-input label="{{ __('lang.student') }}" wire:model.live="search_student_name" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.exam') }}" wire:model.live="search_exam_id" :options="$all_exams" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{ __('lang.student') }}</th>
						<th class="text-center">{{ __('lang.exam') }}</th>
						<th class="text-center">{{ __('lang.total') }}</th>
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.created_at') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($attempts as $attempt)
						<tr class="bg-base-200">
							<th class="text-center">{{ $attempts->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">
								<div class="flex items-center gap-2">
									@if($attempt->user?->getFirstMediaUrl('image'))
										<div class="avatar">
											<div class="w-8 rounded-full">
												<img src="{{ $attempt->user->getFirstMediaUrl('image') }}" alt="{{ $attempt->user->name }}"/>
											</div>
										</div>
									@endif
									{{ $attempt->user?->name ?? '-' }}
								</div>
							</td>
							<td class="text-center text-nowrap">{{ $attempt->exam?->title ?? '-' }}</td>
							<td class="text-center"><x-badge value="{{ $attempt->total_score }}" class="badge-neutral"/></td>
							<td class="text-center">
								@if($attempt->status === 'passed')
									<x-badge value="{{ __('lang.passed') }}" class="badge-success"/>
								@elseif($attempt->status === 'failed')
									<x-badge value="{{ __('lang.failed') }}" class="badge-error"/>
								@else
									<x-badge value="{{ $attempt->status }}" class="badge-warning"/>
								@endif
							</td>
							<td class="text-center text-nowrap">{{ formatDate($attempt->created_at, true) }}</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="6" class="text-center">{{ __('lang.no_data') }}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6">
					<div class="w-full flex-none">{{ $attempts->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
