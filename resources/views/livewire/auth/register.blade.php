<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.auth', ['title' => 'register'])] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone = '';
    public string $phone_key = '';
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
        ]);

        unset($validated['stage_id']);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'pending';
        $validated['email_verified_at'] = now();

        $user = User::create($validated);
        $user->assignRole('student');

        event(new Registered($user));

        \App\Jobs\NotifyAdminsOfNewStudentJob::dispatch($user);

        session()->flash('status', __('lang.created_successfully', ['attribute' => __('lang.student')]) . ' - البيانات جاري المراجعة والموافقة عليها');

        $this->redirect(route('login'), navigate: true);
    }
}; ?>

<div>
    <x-card class="flex flex-col gap-6 border border-gray-300 dark:border-gray-700 text-lg font-medium rounded-xl dark:text-gray-300  dark:bg-gray-900  transition-colors duration-200 " shadow separator>

        <x-auth-header :title="__('lang.create_account') . ' - ' . __('lang.student')" :description="__('lang.enter_your_details_below_to_create_your_account')"/>

        @session('status')
        <x-alert title="{{ session('status') }}" class="text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 my-4 text-center"/>
        @endsession

        <form wire:submit="register" class="flex flex-col gap-6">
            <!-- Name -->
            <x-input
                    wire:model="name"
                    :label="__('lang.name')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="__('lang.full_name')"
            />

            <!-- Email Address -->
            <x-input
                    wire:model="email"
                    :label="__('lang.email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input
                        wire:model="password"
                        :label="__('lang.password')"
                        type="password"
                        required
                        autocomplete="new-password"
                        :placeholder="__('lang.password')"
                        viewable
                />
                
                <x-input
                        wire:model="password_confirmation"
                        :label="__('lang.password_confirmation')"
                        type="password"
                        required
                        autocomplete="new-password"
                        :placeholder="__('lang.password_confirmation')"
                        viewable
                />
            </div>

            <!-- Phone -->
            <x-phone-input required label="{{ __('lang.phone') }}" phoneProperty="phone" keyProperty="phone_key"/>

            <!-- Stage -->
            <x-choices-offline label="{{ __('lang.stage') }}" wire:model.live="stage_id" :options="$all_stages" option-value="id" option-label="name" single searchable required/>

            <!-- Grade -->
            <x-choices-offline label="{{ __('lang.grade') }}" wire:model="grade_id" :options="$all_grades" option-value="id" option-label="name" single searchable required/>

            <div class="flex items-center justify-end">
                <x-button type="submit" variant="primary" class="w-full" spinner="register">
                    {{ __('lang.create_account') }}
                </x-button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400 mt-3">
            {{ __('lang.already_have_an_account') }}
            <a class="link" href="{{route('login')}}" >{{ __('lang.login') }}</a>
        </div>
    </x-card>
</div>
