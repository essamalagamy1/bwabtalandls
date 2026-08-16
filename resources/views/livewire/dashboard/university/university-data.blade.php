@php use App\Services\FileService; @endphp
<div>
	<x-card title="{{ __('lang.universities') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_university')
				<livewire:dashboard.university.create-university wire:key="{{\Illuminate\Support\Str::random(20)}}"></livewire:dashboard.university.create-university>
			@endcan
		</x-slot:menu>
		<div class="flex gap-3 mb-3 flex-wrap">
			<div class="w-64">
				<x-input label="{{__('lang.search')}}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" placeholder="{{__('lang.search')}}"/>
			</div>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{__('lang.name_ar')}}</th>
						<th class="text-center">{{__('lang.name_en')}}</th>
						<th class="text-center">{{__('lang.status')}}</th>
						<th class="text-center">{{__('lang.created_at')}}</th>
						<th class="text-center">{{__('lang.action')}}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($universities as $university)
						<tr class="bg-base-200">
							<th class="text-center">{{$universities->firstItem() + $loop->index}}</th>
							<th class="text-nowrap">{{$university->getTranslation('name', 'ar')}}</th>
							<th class="text-nowrap">{{$university->getTranslation('name', 'en')}}</th>
							<th class="text-center">
								<x-badge :value="$university->status->title()" class="bg-{{$university->status->color()}}"/>
							</th>
							<th class="text-center text-nowrap">{{formatDate($university->created_at,true) }}</th>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_university')
										<livewire:dashboard.university.update-university :university="$university" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_university')
										<x-button wire:confirm="{{__('lang.confirm_delete', ['attribute' => __('lang.university')])}}" icon="o-trash" class="btn-sm btn-ghost" wire:click="delete({{$university->id}})" wire:loading.attr="disabled"
										          wire:target="delete({{$university->id}})" spinner="delete({{$university->id}})" tooltip="{{__('lang.delete')}}"/>
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
							{{ $universities->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-card>
</div>

