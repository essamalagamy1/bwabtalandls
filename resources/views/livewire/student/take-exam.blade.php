<div>
	<div class="flex justify-between items-center mb-6">
		<h1 class="text-2xl font-bold">{{ $exam->title }}</h1>
		
		@if($timeLeft > 0)
			<div x-data="{ time: {{ $timeLeft }} }" x-init="
				setInterval(() => {
					if (time > 0) {
						time--;
					} else {
						$wire.submitExam();
					}
				}, 1000);
			">
				<div class="bg-error text-error-content px-4 py-2 rounded-lg font-mono text-xl flex items-center gap-2 shadow-lg">
					<x-icon name="o-clock" class="w-6 h-6"/>
					<span x-text="Math.floor(time / 60).toString().padStart(2, '0') + ':' + (time % 60).toString().padStart(2, '0')"></span>
				</div>
			</div>
		@endif
	</div>

	@if($exam->hasAttachment())
		<div class="mb-6">
			@if($exam->isUnlimitedMediaViews())
				{{-- Unlimited Media Section --}}
				<x-card class="border-2 border-primary/20 bg-base-100 shadow-md">
					<div class="flex items-center justify-between gap-2 mb-4 pb-2 border-b border-base-200">
						<div class="flex items-center gap-2">
							@if($exam->attachment_type === 'audio')
								<x-icon name="o-speaker-wave" class="w-6 h-6 text-primary animate-pulse"/>
								<span class="font-bold text-lg">{{ __('lang.attached_audio') }}</span>
							@elseif($exam->attachment_type === 'image')
								<x-icon name="o-photo" class="w-6 h-6 text-primary"/>
								<span class="font-bold text-lg">{{ __('lang.attached_image') }}</span>
							@else
								<x-icon name="o-document" class="w-6 h-6 text-primary"/>
								<span class="font-bold text-lg">{{ __('lang.attached_media') }}</span>
							@endif
						</div>
						<x-badge value="{{ __('lang.unlimited_views') }}" class="badge-success badge-outline font-medium text-xs"/>
					</div>

					@if($exam->attachment_type === 'audio')
						<div class="p-2 bg-base-200/50 rounded-xl">
							<audio controls class="w-full" src="{{ $exam->getFirstMediaUrl('attachment') }}">
								متصفحك لا يدعم مشغل الصوت.
							</audio>
						</div>
					@elseif($exam->attachment_type === 'image')
						<div class="flex justify-center bg-base-200/30 p-2 rounded-xl">
							<img src="{{ $exam->getFirstMediaUrl('attachment') }}" alt="{{ $exam->title }}" class="max-h-96 rounded-lg object-contain shadow" />
						</div>
					@else
						<div class="flex justify-center p-3">
							<a href="{{ $exam->getFirstMediaUrl('attachment') }}" target="_blank" class="btn btn-primary btn-sm flex items-center gap-2">
								<x-icon name="o-arrow-down-tray" class="w-4 h-4"/>
								<span>{{ __('lang.view') }} {{ __('lang.attachment_file') }}</span>
							</a>
						</div>
					@endif
				</x-card>
			@else
				{{-- Limited Views Media Section --}}
				@php
					$remaining = $attempt->remainingMediaViews();
					$canView = $attempt->canViewMedia();
				@endphp

				<x-card class="border-2 {{ $canView ? 'border-warning/40 bg-warning/5' : 'border-base-300 bg-base-200/50' }} shadow-md transition-all">
					<div class="flex flex-wrap items-center justify-between gap-3">
						<div class="flex items-center gap-3">
							<div class="p-3 {{ $canView ? 'bg-warning/20 text-warning-content' : 'bg-base-300 text-base-content/50' }} rounded-xl">
								@if($exam->attachment_type === 'audio')
									<x-icon name="o-speaker-wave" class="w-7 h-7 text-warning"/>
								@elseif($exam->attachment_type === 'image')
									<x-icon name="o-photo" class="w-7 h-7 text-warning"/>
								@else
									<x-icon name="o-document" class="w-7 h-7 text-warning"/>
								@endif
							</div>
							<div>
								<div class="font-bold text-lg flex items-center gap-2">
									<span>
										@if($exam->attachment_type === 'audio')
											{{ __('lang.attached_audio') }}
										@elseif($exam->attachment_type === 'image')
											{{ __('lang.attached_image') }}
										@else
											{{ __('lang.attached_media') }}
										@endif
									</span>
								</div>
								<div class="text-sm text-base-content/70 mt-0.5">
									العدد المسموح: <span class="font-bold">{{ $exam->media_views_limit }}</span> | المتبقي لك: <span class="font-bold {{ $remaining > 0 ? 'text-primary' : 'text-error' }}">{{ $remaining }}</span>
								</div>
							</div>
						</div>

						<div>
							@if($isMediaOpen)
								<x-button 
									label="{{ $exam->attachment_type === 'audio' ? __('lang.close_audio') : ($exam->attachment_type === 'image' ? __('lang.close_image') : __('lang.close')) }}" 
									icon="o-eye-slash" 
									wire:click="closeMedia" 
									class="btn-outline btn-sm sm:btn-md text-error" 
									spinner="closeMedia" 
								/>
							@elseif($canView)
								<x-button 
									label="{{ $exam->attachment_type === 'audio' ? __('lang.play_audio') : ($exam->attachment_type === 'image' ? __('lang.view_image') : __('lang.view')) }}" 
									icon="{{ $exam->attachment_type === 'audio' ? 'o-play' : 'o-eye' }}" 
									wire:click="openMedia" 
									wire:confirm="هل أنت متأكد من رغبتك في الاستماع/المشاهدة الآن؟ سيتم احتساب 1 من عدد المرات المتاحة لك."
									class="btn-warning btn-sm sm:btn-md" 
									spinner="openMedia" 
								/>
							@else
								<div class="badge badge-error gap-1 p-3 text-xs font-semibold">
									<x-icon name="o-lock-closed" class="w-4 h-4"/>
									<span>{{ __('lang.views_exhausted') }}</span>
								</div>
							@endif
						</div>
					</div>

					@if($isMediaOpen)
						<div class="mt-4 pt-4 border-t border-base-300">
							@if($exam->attachment_type === 'audio')
								<div class="p-3 bg-base-100 rounded-xl border border-warning/30 flex flex-col gap-3" x-data>
									<div class="flex items-center gap-2 text-warning font-medium text-xs">
										<x-icon name="o-speaker-wave" class="w-4 h-4 animate-pulse shrink-0"/>
										<span>{{ __('lang.playing_audio_notice') }}</span>
									</div>
									<audio 
										x-ref="audioEl"
										x-init="$nextTick(() => { $el.play().catch(e => console.log('Autoplay deferred:', e)); })"
										@ended="$wire.closeMedia()"
										controls 
										autoplay 
										class="w-full" 
										src="{{ $exam->getFirstMediaUrl('attachment') }}"
									>
										متصفحك لا يدعم مشغل الصوت.
									</audio>
									<div class="flex justify-end">
										<x-button label="{{ __('lang.close_audio') }}" icon="o-stop" wire:click="closeMedia" class="btn-xs btn-ghost text-error" spinner="closeMedia"/>
									</div>
								</div>
							@elseif($exam->attachment_type === 'image')
								<div class="flex flex-col items-center bg-base-100 p-3 rounded-xl border border-warning/30">
									<img src="{{ $exam->getFirstMediaUrl('attachment') }}" alt="{{ $exam->title }}" class="max-h-96 rounded-lg object-contain shadow mb-3" />
									<x-button label="{{ __('lang.close_image') }}" icon="o-eye-slash" wire:click="closeMedia" class="btn-sm btn-ghost text-error" spinner="closeMedia"/>
								</div>
							@else
								<div class="flex flex-col items-center bg-base-100 p-3 rounded-xl border border-warning/30 gap-3">
									<a href="{{ $exam->getFirstMediaUrl('attachment') }}" target="_blank" class="btn btn-primary btn-sm flex items-center gap-2">
										<x-icon name="o-arrow-down-tray" class="w-4 h-4"/>
										<span>{{ __('lang.view') }} {{ __('lang.attachment_file') }}</span>
									</a>
									<x-button label="{{ __('lang.close') }}" icon="o-eye-slash" wire:click="closeMedia" class="btn-sm btn-ghost text-error" spinner="closeMedia"/>
								</div>
							@endif
						</div>
					@endif
				</x-card>
			@endif
		</div>
	@endif

	<div class="space-y-6">
		@foreach($exam->questions as $index => $question)
			<x-card class="shadow-md" title="{{ __('lang.question') }} {{ $index + 1 }}">
				<div class="mb-4 text-lg">
					{{ $question->question_text }}
				</div>
				
				@if($question->getFirstMediaUrl('image'))
					<div class="mb-4">
						<img src="{{ $question->getFirstMediaUrl('image') }}" alt="Question Image" class="max-h-64 rounded-lg shadow-sm" />
					</div>
				@endif

				<div class="space-y-3">
					<label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-base-200 transition-colors border border-base-300">
						<input type="radio" wire:model.defer="answers.{{ $question->id }}" value="a" class="radio radio-primary" />
						<span>{{ $question->option_a }}</span>
					</label>
					
					<label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-base-200 transition-colors border border-base-300">
						<input type="radio" wire:model.defer="answers.{{ $question->id }}" value="b" class="radio radio-primary" />
						<span>{{ $question->option_b }}</span>
					</label>
					
					<label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-base-200 transition-colors border border-base-300">
						<input type="radio" wire:model.defer="answers.{{ $question->id }}" value="c" class="radio radio-primary" />
						<span>{{ $question->option_c }}</span>
					</label>
					
					<label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-base-200 transition-colors border border-base-300">
						<input type="radio" wire:model.defer="answers.{{ $question->id }}" value="d" class="radio radio-primary" />
						<span>{{ $question->option_d }}</span>
					</label>
				</div>
			</x-card>
		@endforeach
	</div>

	<div class="mt-8 flex justify-end">
		<x-button label="{{ __('lang.submit_exam') }}" wire:click="submitExam" wire:confirm="{{ __('lang.confirm_submit_exam') }}" icon="o-check-circle" class="btn-primary btn-lg w-full sm:w-auto" spinner="submitExam" />
	</div>
</div>
