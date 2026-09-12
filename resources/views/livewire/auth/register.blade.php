<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.auth', ['title' => 'register', 'maxWidth' => 'max-w-4xl'])] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone = '';
    public string $phone_key = '';

    // Parent Fields
    public string $parent_name = '';
    public string $parent_email = '';
    public string $parent_password = '';
    public string $parent_password_confirmation = '';
    public string $parent_phone = '';
    public string $parent_phone_key = '';
    public $stage_id = null;
    public $grade_id = null;
    public array $all_stages = [];
    public array $all_grades = [];

    public function mount(): void
    {
        $this->all_stages = \App\Models\Stage::where('is_active', true)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function updatedStageId(): void
    {
        $this->grade_id = null;
        if ($this->stage_id) {
            $this->all_grades = \App\Models\Grade::where('stage_id', $this->stage_id)
                ->where('is_active', true)
                ->get(['id', 'name'])
                ->toArray();
        } else {
            $this->all_grades = [];
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'phone_key' => ['required', 'string', 'max:5'],
            'stage_id' => ['required', 'exists:stages,id'],
            'grade_id' => ['required', 'exists:grades,id'],

            // Parent Validation
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email'],
            'parent_password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'parent_phone' => ['required', 'string', 'max:20'],
            'parent_phone_key' => ['required', 'string', 'max:5'],
        ]);

        unset($validated['stage_id']);
        unset($validated['parent_name'], $validated['parent_email'], $validated['parent_password'], $validated['parent_phone'], $validated['parent_phone_key']);

        // Create Parent
        $parent = User::create([
            'name' => $this->parent_name,
            'email' => $this->parent_email,
            'password' => Hash::make($this->parent_password),
            'phone' => $this->parent_phone,
            'phone_key' => $this->parent_phone_key,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $parent->assignRole('parent');

        // Notify Parent
        $parent->notify(new \App\Notifications\ParentRegisteredNotification($this->parent_password));

        $validated['password'] = Hash::make($this->password);
        $validated['status'] = 'pending';
        $validated['email_verified_at'] = now();
        $validated['parent_id'] = $parent->id;

        $user = User::create($validated);
        $user->assignRole('student');

        \App\Models\AcademicEnrollment::create([
            'user_id' => $user->id,
            'grade_id' => $user->grade_id,
            'section_id' => null, // Section will be assigned later by admin if needed
            'is_current' => true,
            'notes' => 'Registered via auth',
        ]);

        event(new Registered($user));

        // \App\Jobs\NotifyAdminsOfNewStudentJob::dispatch($user);

        session()->flash('status', 'تم تسجيل بياناتك بنجاح، وجاري مراجعتها. وبعد الموافقة، بنرسل لك رسالة على بريدك الإلكتروني.');

        $this->redirect(route('login'), navigate: true);
    }
}; ?>

<div>
    <x-card
        class="flex flex-col gap-6 border border-gray-300 dark:border-gray-700 text-lg font-medium rounded-xl dark:text-gray-300  dark:bg-gray-900  transition-colors duration-200 "
        shadow separator>

        <x-auth-header title="سجل معنا - طالب" description="سجّل بياناتك أدناه وأنشئ حسابك بسهولة" />

        @session('status')
            <x-alert title="{{ session('status') }}"
                class="text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 my-4 text-center" />
        @endsession

        <form wire:submit="register" class="flex flex-col gap-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">

                {{-- قسم بيانات الطالب (اليمين) --}}
                <div class="flex flex-col gap-4 bg-base-100 p-6 rounded-2xl shadow-sm border border-base-200">
                    <h3 class="text-xl font-bold text-primary mb-2 flex items-center gap-2">
                        <x-icon name="o-academic-cap" class="w-6 h-6" />
                        بيانات الطالب
                    </h3>

                    <x-input wire:model="name" :label="__('lang.name')" type="text" required autofocus autocomplete="name"
                        :placeholder="__('lang.full_name')" />

                    <x-input wire:model="email" :label="__('lang.email')" type="email" required autocomplete="email"
                        placeholder="email@example.com" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-password wire:model="password" :label="__('lang.password')" required autocomplete="new-password"
                            :placeholder="__('lang.password')" right />
                        <x-password wire:model="password_confirmation" :label="__('lang.password_confirmation')" required
                            autocomplete="new-password" :placeholder="__('lang.password_confirmation')" right />
                    </div>

                    <x-phone-input required label="{{ __('lang.phone') }}" phoneProperty="phone"
                        keyProperty="phone_key" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-choices-offline label="{{ __('lang.stage') }}" wire:model.live="stage_id" :options="$all_stages"
                            option-value="id" option-label="name" single searchable required />
                        <x-select label="{{ __('lang.grade') }}" wire:model="grade_id" :options="$all_grades"
                            option-value="id" option-label="name" required placeholder="{{ __('lang.grade') }}" />
                    </div>
                </div>

                {{-- قسم بيانات ولي الأمر (اليسار) --}}
                <div class="flex flex-col gap-4 bg-base-100 p-6 rounded-2xl shadow-sm border border-base-200">
                    <h3 class="text-xl font-bold text-secondary mb-2 flex items-center gap-2">
                        <x-icon name="o-user-group" class="w-6 h-6" />
                        بيانات ولي الأمر
                    </h3>

                    <x-input wire:model="parent_name" label="اسم ولي الأمر" type="text" required :placeholder="__('lang.full_name')" />

                    <x-input wire:model="parent_email" label="البريد الإلكتروني لولي الأمر" type="email" required
                        placeholder="parent@example.com" />

                    <x-phone-input required label="رقم جوال ولي الأمر" phoneProperty="parent_phone"
                        keyProperty="parent_phone_key" />

                    <x-password wire:model="parent_password" label="كلمة سر حساب ولي الأمر" required :placeholder="__('lang.password')"
                        right />
                    <x-password wire:model="parent_password_confirmation" label="تأكيد كلمة المرور" required
                        :placeholder="__('lang.password_confirmation')" right />
                </div>
            </div>

            {{-- تنبيه --}}
            <div
                class="mt-4 p-4 rounded-xl bg-orange-50 border border-orange-200 text-orange-800 flex items-start gap-3">
                <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-orange-500 shrink-0 mt-0.5" />
                <div class="text-sm font-medium leading-relaxed">
                    <strong>تنبيه هام:</strong> الطالب مسؤول مسؤولية كاملة عن إدخال البيانات بشكل صحيح. سيتم التواصل مع
                    ولي الأمر هاتفياً أو عبر البريد الإلكتروني للتأكد من صحة البيانات المُدخلة كشرط أساسي لتفعيل حساب
                    الطالب وحساب ولي الأمر.
                </div>
            </div>

            <div class="flex items-center justify-center mt-4">
                <x-button type="submit" variant="primary" class="w-full md:w-1/2 rounded-full font-bold text-lg"
                    spinner="register">
                    {{ __('lang.create_account') }}
                </x-button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400 mt-3">
            {{ __('lang.already_have_an_account') }}
            <a class="link" href="{{ route('login') }}">{{ __('lang.login') }}</a>
        </div>
    </x-card>
</div>
