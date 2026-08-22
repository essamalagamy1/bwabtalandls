@php use App\Enums\Status;use App\Services\FileService; @endphp
<div>
	<x-card title="{{ __('lang.admins') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_admin')
				<livewire:dashboard.admin.create-admin :all_roles="$all_roles" wire:key="{{\Illuminate\Support\Str::random(20)}}"></livewire:dashboard.admin.create-admin>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
			<x-choices-offline label="{{ __('lang.admins') }}" wire:model.live="search_admin_id" :options="$all_admin" single clearable searchable option-value="id" option-label="name" option-sub-label="username"
			                   placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{__('lang.roles')}}" wire:model.live="search_admin_role_id" :options="$all_roles" option-value="id" option-label="name" clearable searchable single  placeholder="{{ __('lang.search') }}"/>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{__('lang.name')}}</th>
						<th class="text-center">{{__('lang.email')}}</th>
						<th class="text-center">{{__('lang.roles')}}</th>
						<th class="text-center">{{__('lang.status')}}</th>
						<th class="text-center">{{__('lang.created_at')}}</th>
						<th class="text-center">{{__('lang.action')}}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($admins as $admin)
						<tr class="bg-base-200">
							<th class="text-center">{{$admins->firstItem() + $loop->index}}</th>
							<th class="text-nowrap">
								{{$admin->name}}
							</th>
							<th class="text-center text-nowrap">{{$admin->email}}</th>
							<th class="text-center text-nowrap">
								@foreach($admin->roles as $role)
									@if($role->name !== 'admin')
										<x-badge :value="$role->name" class="bg-blue-500 mr-1"/>
									@endif
								@endforeach
							</th>
							<th class="text-center">
								@if($admin->status === 'active')
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@elseif($admin->status === 'inactive')
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@else
									<x-badge value="{{ __('lang.pending') }}" class="badge-warning"/>
								@endif
							</th>
							<th class="text-center text-nowrap">{{formatDate($admin->created_at,true) }}</th>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_admin')
										<x-button 
											icon="{{ $admin->status === 'active' ? 'o-x-circle' : 'o-check-circle' }}" 
											class="btn-sm btn-ghost {{ $admin->status === 'active' ? 'text-error' : 'text-success' }}" 
											wire:click="toggleStatus({{ $admin->id }})" 
											spinner="toggleStatus({{ $admin->id }})" 
											tooltip="{{ $admin->status === 'active' ? __('lang.inactive') : __('lang.active') }}" 
										/>
										<livewire:dashboard.admin.update-admin :all_roles="$all_roles" :user="$admin" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_admin')
										<x-button icon="o-trash" class="btn-sm btn-ghost" wire:click="delete({{$admin->id}})"
										          wire:confirm="{{__('lang.confirm_delete', ['attribute' => __('lang.admin')])}}"
										          wire:loading.attr="disabled" wire:target="delete({{$admin->id}})"
										          spinner="delete({{$admin->id}})" tooltip="{{__('lang.delete')}}"/>
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
							{{ $admins->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
