@php use App\Enums\Status;use App\Services\FileService; @endphp
<div>
	<x-card title="{{ __('lang.instructors') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_instructor')
				<livewire:dashboard.instructor.create-instructor :universities="$universities" wire:key="{{\Illuminate\Support\Str::random(20)}}"></livewire:dashboard.instructor.create-instructor>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
			<x-choices-offline label="{{ __('lang.instructor') }}" wire:model.live="search_instructor_id" :options="$all_instructor" single clearable searchable
			                   option-value="id" option-label="name" option-sub-label="username" placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline searchable single clearable label="{{__('lang.university')}}" wire:model.live="search_university_id" :options="$universities" placeholder="{{ __('lang.search') }}" option-value="id" option-label="name"/>

		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{__('lang.name')}}</th>
						<th class="text-center">{{__('lang.email')}}</th>
						<th class="text-center">{{__('lang.created_at')}}</th>
						<th class="text-center">{{__('lang.action')}}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($instructors as $instructor)
						<tr class="bg-base-200">
							<th class="text-center">{{$instructors->firstItem() + $loop->index}}</th>
							<th class="text-nowrap">
								{{$instructor->name}}
							</th>
							<th class="text-center text-nowrap">{{$instructor->email}}</th>
							<th class="text-center text-nowrap">{{formatDate($instructor->created_at,true) }}</th>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_instructor')
										<livewire:dashboard.instructor.update-instructor :universities="$universities" :user="$instructor" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_instructor')
										<x-button icon="o-trash" class="btn-sm btn-ghost" wire:click="delete({{$instructor->id}})"
										          wire:confirm="{{__('lang.confirm_delete', ['attribute' => __('lang.instructor')])}}"
										          wire:loading.attr="disabled" wire:target="delete({{$instructor->id}})"
										          spinner="delete({{$instructor->id}})" tooltip="{{__('lang.delete')}}"/>
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
							{{ $instructors->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-card>
</div>

