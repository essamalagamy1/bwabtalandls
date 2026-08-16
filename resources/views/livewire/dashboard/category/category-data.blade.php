<div>
	<x-card title="{{ __('lang.categories') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_category')
				<livewire:dashboard.category.create-category wire:key="{{\Illuminate\Support\Str::random(20)}}"></livewire:dashboard.category.create-category>
			@endcan
		</x-slot:menu>
		<div class="flex gap-3 mb-3 flex-wrap">
			<div class="w-64">
				<x-ui.choices-advanced-search label="{{ __('lang.categories') }}" wire:model.live="search_category_id" :options="$all_category" single clearable searchable
				                   option-value="id" option-label="name" placeholder="{{ __('lang.search') }}"/>
			</div>
			<div class="w-48">
				<x-select label="{{__('lang.status')}}" wire:model.live="filter_status" :options="[['id' => 'active', 'name' => __('lang.active')], ['id' => 'inactive', 'name' => __('lang.inactive')]]" placeholder="{{__('lang.all')}}" option-value="id" option-label="name"/>
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
						<th class="text-center">{{__('lang.subcategories')}}</th>
						<th class="text-center">{{__('lang.status')}}</th>
						<th class="text-center">{{__('lang.created_at')}}</th>
						<th class="text-center">{{__('lang.action')}}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($categories as $category)
						<tr class="bg-base-200">
							<th class="text-center">{{$categories->firstItem() + $loop->index}}</th>
							<th class="text-nowrap">{{$category->getTranslation('name', 'ar')}}</th>
							<th class="text-nowrap">{{$category->getTranslation('name', 'en')}}</th>
							<th class="text-center">
								<x-badge :value="$category->children_count" class="bg-gray-500"/>
							</th>
							<th class="text-center">
								<x-badge :value="$category->status->title()" class="bg-{{$category->status->color()}}"/>
							</th>
							<th class="text-center text-nowrap">{{formatDate($category->created_at,true) }}</th>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_category')
										<livewire:dashboard.category.update-category :category="$category" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_category')
										<x-button wire:confirm="{{__('lang.confirm_delete', ['attribute' => __('lang.category')])}}" icon="o-trash" class="btn-sm btn-ghost" wire:click="delete({{$category->id}})" wire:loading.attr="disabled"
										          wire:target="delete({{$category->id}})" spinner="delete({{$category->id}})" tooltip="{{__('lang.delete')}}"/>
									@endcan
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="8" class="text-center">{{__('lang.no_data')}}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6 min-w-">
					<div class="flex w-full items-center justify-between">
						<div class="w-full flex-none">
							{{ $categories->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
