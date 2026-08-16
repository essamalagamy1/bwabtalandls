<div>
	<x-card title="{{ __('lang.faqs') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_faq')
				<livewire:dashboard.faq.create-faq wire:key="{{\Illuminate\Support\Str::random(20)}}"></livewire:dashboard.faq.create-faq>
			@endcan
		</x-slot:menu>
		<div class="flex gap-3 mb-3 flex-wrap">
			<div class="w-64">
				<x-ui.choices-advanced-search label="{{ __('lang.faqs') }}" wire:model.live="search_faq_id" :options="$all_faq" single clearable searchable
				                   option-value="id" option-label="question" placeholder="{{ __('lang.search') }}"/>
			</div>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{__('lang.question_ar')}}</th>
						<th class="text-center">{{__('lang.question_en')}}</th>
						<th class="text-center">{{__('lang.answer_ar')}}</th>
						<th class="text-center">{{__('lang.answer_en')}}</th>
						<th class="text-center">{{__('lang.created_at')}}</th>
						<th class="text-center">{{__('lang.action')}}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($faqs as $faq)
						<tr class="bg-base-200">
							<th class="text-center">{{$faqs->firstItem() + $loop->index}}</th>
							<th class="text-nowrap">{{$faq->getTranslation('question', 'ar')}}</th>
							<th class="text-nowrap">{{$faq->getTranslation('question', 'en')}}</th>
							<th class="max-w-xs truncate">{{$faq->getTranslation('answer', 'ar')}}</th>
							<th class="max-w-xs truncate">{{$faq->getTranslation('answer', 'en')}}</th>
							<th class="text-center text-nowrap">{{formatDate($faq->created_at,true) }}</th>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_faq')
										<livewire:dashboard.faq.update-faq :faq="$faq" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_faq')
										<x-button wire:confirm="{{__('lang.confirm_delete', ['attribute' => __('lang.faq')])}}" icon="o-trash" class="btn-sm btn-ghost" wire:click="delete({{$faq->id}})" wire:loading.attr="disabled"
										          wire:target="delete({{$faq->id}})" spinner="delete({{$faq->id}})" tooltip="{{__('lang.delete')}}"/>
									@endcan
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="7" class="text-center">{{__('lang.no_data')}}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6 min-w-">
					<div class="flex w-full items-center justify-between">
						<div class="w-full flex-none">
							{{ $faqs->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
