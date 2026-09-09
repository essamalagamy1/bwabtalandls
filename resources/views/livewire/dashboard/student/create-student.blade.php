<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{ __('lang.add') }}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	<x-modal wire:model="modalAdd" title="{{ __('lang.add') }} {{ __('lang.student') }}" box-class="modal-box-700">
		<x-form wire:submit="saveAdd">
			{{-- ═══════════════ بيانات الطالب ═══════════════ --}}
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

			<x-input label="{{ __('lang.name') }}" wire:model="name"/>
			<x-input label="{{ __('lang.email') }}" type="email" wire:model="email"/>
			
			</div>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<x-input label="{{ __('lang.password') }}" type="password" wire:model="password"/>
				<x-input label="{{ __('lang.password_confirmation') }}" type="password" wire:model="password_confirmation"/>
			</div>
		
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<x-select label="{{ __('lang.stage') }}" wire:model.live="stage_id" :options="$all_stages" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
				<x-select label="{{ __('lang.grade') }}" wire:model.live="grade_id" :options="$all_grades" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
			</div>
	<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			<x-select label="{{ __('lang.section') }}" wire:model="section_id" :options="$all_sections" option-value="id" option-label="name" placeholder="{{ count($all_sections) > 0 ? __('lang.select') : 'لا توجد شعب متاحة لهذا الصف (اختياري)' }}" clearable />

			
			<x-phone-input required label="{{ __('lang.phone') }}" phoneProperty="phone" keyProperty="phone_key"/>
			
			</div>

				      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

			<x-select label="{{ __('lang.status') }}" wire:model="status" :options="[
                ['id' => 'active', 'name' => __('lang.active')],
                ['id' => 'inactive', 'name' => __('lang.inactive')],
                ['id' => 'pending', 'name' => __('lang.pending')],
            ]" option-value="id" option-label="name"/>

			<div>
				<x-file wire:model="image" label="{{ __('lang.image') }}" accept="image/*"/>
				<x-progress class="progress-primary h-0.5" indeterminate wire:loading wire:target="image"/>
			</div>
				      </div>

			{{-- ═══════════════ بيانات ولي الأمر (اختياري) ═══════════════ --}}
			<div class="divider text-sm font-semibold text-base-content/60">
				<x-icon name="o-user-circle" class="w-4 h-4" /> بيانات ولي الأمر <span class="badge badge-ghost badge-sm">اختياري</span>
			</div>
			<x-alert icon="o-information-circle" class="alert-info mb-2 text-sm py-2">
				إذا أدخلت بريد ولي أمر موجود مسبقاً (في حال كان لديه أبناء آخرون) سيتم ربط هذا الطالب بنفس الحساب تلقائياً دون إنشاء حساب جديد.
			</x-alert>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<x-input label="اسم ولي الأمر" wire:model="parent_name" placeholder="يُكمَّل تلقائياً إن تُرك فارغاً" clearable/>
				<x-input label="بريد ولي الأمر" type="email" wire:model="parent_email" placeholder="البريد الإلكتروني" clearable/>
			</div>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<x-input label="كلمة مرور ولي الأمر" type="password" wire:model="parent_password"/>
				<x-input label="تأكيد كلمة المرور" type="password" wire:model="parent_password_confirmation"/>
			</div>
			<x-phone-input label="هاتف ولي الأمر (اختياري)" phoneProperty="parent_phone" keyProperty="parent_phone_key"/>
			
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalAdd = false"/>
				<x-button label="{{ __('lang.save') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveAdd"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
