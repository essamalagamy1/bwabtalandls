<div>
    <x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}"
        wire:click="resetError" />
    <x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.student') }}" box-class="modal-box-700">
        <x-form wire:submit="saveUpdate">
            <div class="text-center mb-3 mx-auto">
                <x-avatar :image="$student->getFirstMediaUrl('image')" class="w-20 h-20" />
            </div>

            {{-- ═══════════════ بيانات الطالب ═══════════════ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <x-input label="{{ __('lang.name') }}" wire:model="name" />
                <x-input label="{{ __('lang.email') }}" type="email" wire:model="email" />

            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-password label="{{ __('lang.password') }}" right wire:model="password" />
                <x-password label="{{ __('lang.password_confirmation') }}" right
                    wire:model="password_confirmation" />
            </div>
      

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-select label="{{ __('lang.stage') }}" wire:model.live="stage_id" :options="$all_stages" option-value="id"
                    option-label="name" placeholder="{{ __('lang.select') }}..." />
                <x-select label="{{ __('lang.grade') }}" wire:model.live="grade_id" :options="$all_grades" option-value="id"
                    option-label="name" placeholder="{{ __('lang.select') }}..." />
            </div>
				      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <x-select label="{{ __('lang.section') }}" wire:model="section_id" :options="$all_sections" option-value="id"
                option-label="name"
                placeholder="{{ count($all_sections) > 0 ? __('lang.select') : 'لا توجد شعب متاحة لهذا الصف (اختياري)' }}"
                clearable />


            <x-phone-input required label="{{ __('lang.phone') }}" phoneProperty="phone" keyProperty="phone_key" />


			            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <x-select label="{{ __('lang.status') }}" wire:model="status" :options="[
                    ['id' => 'active', 'name' => __('lang.active')],
                    ['id' => 'inactive', 'name' => __('lang.inactive')],
                    ['id' => 'pending', 'name' => __('lang.pending')],
                ]" option-value="id"
                    option-label="name" />

                <div>
                    <x-file wire:model="image" label="{{ __('lang.image') }}" accept="image/*" />
                    <x-progress class="progress-primary h-0.5" indeterminate wire:loading wire:target="image" />
                </div>
            </div>

            {{-- ═══════════════ بيانات ولي الأمر (اختياري) ═══════════════ --}}
            <div class="divider text-sm font-semibold text-base-content/60">
                <x-icon name="o-user-circle" class="w-4 h-4" /> بيانات ولي الأمر <span
                    class="badge badge-ghost badge-sm">اختياري</span>
            </div>

            @if ($existing_parent_id)
                <div class="alert alert-success py-2 mb-2">
                    <x-icon name="o-check-circle" class="w-5 h-5" />
                    <div class="text-sm">
                        <span class="font-semibold">ولي الأمر الحالي: </span>{{ $existing_parent_name }}
                        ({{ $existing_parent_email }})
                    </div>
                </div>
            @endif

            <x-alert icon="o-information-circle" class="alert-info mb-2 text-sm py-2">
                @if ($existing_parent_id)
                    يمكنك تعديل بيانات ولي الأمر أو إدخال بريد آخر لتغيير الربط. مسح البريد يفصل الطالب عن ولي أمره.
                @else
                    يمكنك ربط الطالب بحساب ولي أمر (بريد موجود مسبقاً أو إنشاء حساب جديد).
                @endif
            </x-alert>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="اسم ولي الأمر" wire:model="parent_name" placeholder="اسم ولي الأمر (اختياري)"
                    clearable />
                <x-input label="بريد ولي الأمر" type="email" wire:model="parent_email" placeholder="البريد الإلكتروني"
                    clearable />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-password label="كلمة مرور ولي الأمر" right wire:model="parent_password"
                    hint="اترك الحقل فارغاً إذا لم ترغب بتغيير كلمة المرور" />
                <x-password label="تأكيد كلمة المرور" right wire:model="parent_password_confirmation" />
            </div>
            <x-phone-input label="هاتف ولي الأمر (اختياري)" phoneProperty="parent_phone"
                keyProperty="parent_phone_key" />

            <x-slot:actions>
                <x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false" />
                <x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled"
                    type="submit" spinner="saveUpdate" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
