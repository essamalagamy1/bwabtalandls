@php use App\Services\FileService; @endphp
<div>
	<x-card title="{{ __('lang.banners') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_banner')
				<livewire:dashboard.banner.create-banner :products="$products" wire:key="{{\Illuminate\Support\Str::random(20)}}"></livewire:dashboard.banner.create-banner>
			@endcan
		</x-slot:menu>
		<div class="flex gap-3 mb-3 flex-wrap">
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
						<th class="text-center">{{__('lang.sort')}}</th>
						<th class="text-center">{{__('lang.name_ar')}}</th>
						<th class="text-center">{{__('lang.name_en')}}</th>
						<th class="text-center">{{__('lang.status')}}</th>
						<th class="text-center">{{__('lang.created_at')}}</th>
						<th class="text-center">{{__('lang.action')}}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($banners as $banner)
						<tr class="bg-base-200">
							<th class="text-center">{{$banners->firstItem() + $loop->index}}</th>
							<th class="text-center">{{$banner->sort}}</th>
							<th class="text-nowrap">{{$banner->getTranslation('name', 'ar')}}</th>
							<th class="text-nowrap">{{$banner->getTranslation('name', 'en')}}</th>
{{--							<th class="text-center">--}}
{{--								@if($banner->product)--}}
{{--									<span class="text-sm">{{$banner->product->name}}</span>--}}
{{--								@else--}}
{{--									<span class="text-gray-400">-</span>--}}
{{--								@endif--}}
{{--							</th>--}}
							<th class="text-center">
								<x-badge :value="$banner->status->title()" class="bg-{{$banner->status->color()}}"/>
							</th>
							<th class="text-center text-nowrap">{{formatDate($banner->created_at,true) }}</th>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_banner')
										<livewire:dashboard.banner.update-banner :products="$products" :banner="$banner" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_banner')
										<x-button icon="o-trash" class="btn-sm btn-ghost" wire:click="delete({{$banner->id}})"
										          wire:confirm="{{__('lang.confirm_delete', ['attribute' => __('lang.banner')])}}"
										          wire:loading.attr="disabled" wire:target="delete({{$banner->id}})"
										          spinner="delete({{$banner->id}})" tooltip="{{__('lang.delete')}}"/>
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
							{{ $banners->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
