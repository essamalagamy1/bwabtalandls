# Copilot Instructions - E-Commerce Dashboard Project

## Project Overview

This is a Laravel 12 e-commerce application with a dashboard built using Livewire 3 (Volt), Mary UI components, and Tailwind CSS 4. The project follows strict conventions for CRUD operations, file organization, and code structure.

---

## Tech Stack

-   **Backend:** Laravel 12, PHP 8.4
-   **Frontend:** Livewire 3 (Volt - Class-based), Mary UI, Tailwind CSS 4
-   **Database:** MySQL
-   **Image Processing:** Intervention Image (GD Driver)
-   **Testing:** Pest
-   **Code Style:** Laravel Pint

---

## CRUD Structure & Conventions

### Directory Structure

Every CRUD module follows this exact structure:

```
app/
├── Livewire/
│   └── Dashboard/
│       └── [Module]/
│           ├── [Module]Data.php          # List/Index component
│           ├── Create[Module].php        # Create component
│           └── Update[Module].php        # Update component
├── Models/
│   └── [Module].php                      # Eloquent model
└── Services/
    └── FileService.php                   # File handling service

resources/
└── views/
    └── livewire/
        └── dashboard/
            └── [module]/
                ├── [module]-data.blade.php      # List view
                ├── create-[module].blade.php    # Create modal
                └── update-[module].blade.php    # Update modal

routes/
└── web.php                               # Route definitions

lang/
├── ar/
│   ├── lang.php                          # Arabic translations
│   └── validation.php                    # Arabic validation
└── en/
    └── lang.php                          # English translations
```

---

## Component Structure

### 1. Data Component ([Module]Data.php)

**Purpose:** Display list of records with pagination, search, filters, and delete functionality.

**Required Properties:**

```php
public $all_[module];              // For search dropdown
public $search_[module]_id;        // Search by ID
public $filter_[field];            // Any filters (status, category, etc.)
```

**Required Methods:**

```php
public function mount(): void
{
    // Load data for search dropdown
    $this->all_[module] = Model::get(['id', 'name'])->toArray();

    // Share breadcrumbs
    view()->share('breadcrumbs', $this->breadcrumbs());
}

public function breadcrumbs(): array
{
    return [
        [
            'label' => __('lang.[modules]'),
            'icon' => 'o-icon-name',
        ],
    ];
}

#[On('render')]
public function render(): View
{
    $data['[modules]'] = Model::query()
        ->when($this->search_[module]_id, fn (Builder $query) => $query->where('id', $this->search_[module]_id))
        ->when($this->filter_[field], fn (Builder $query) => $query->where('[field]', $this->filter_[field]))
        ->latest()
        ->paginate(20);

    return view('livewire.dashboard.[module].[module]-data', $data);
}

public function deleteSweetAlert($id): void
{
    sweetalert()
        ->showDenyButton()
        ->timer(0)
        ->iconColor('#FFA500')
        ->option('confirmButtonText', __('lang.confirm'))
        ->option('denyButtonText', __('lang.cancel'))
        ->option('id', $id)
        ->info(__('lang.confirm_delete', ['attribute' => __('lang.[module]')]));
}

#[On('sweetalert:confirmed')]
public function delete(array $payload): void
{
    $id = $payload['envelope']['options']['id'];
    $model = Model::findOrFail($id);

    // Delete image if exists using FileService
    if ($model->image) {
        \App\Services\FileService::delete($model->image);
    }

    $model->delete();
    flash()->success(__('lang.deleted_successfully', ['attribute' => __('lang.[module]')]));
}
```

---

### 2. Create Component (Create[Module].php)

**Purpose:** Handle creation of new records via modal.

**Required Properties:**

```php
public bool $modalAdd = false;
public $field1;
public $field2;
public $image;                    // If has image upload
public $status = 'inactive';      // Default status
```

**Required Methods:**

```php
public function render(): View
{
    return view('livewire.dashboard.[module].create-[module]');
}

public function rules(): array
{
    return [
        'name_ar' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        'status' => 'required|in:active,inactive',
    ];
}

public function saveAdd(): void
{
    $this->validate();
    Model::create([
        'name' => [
            'ar' => $this->name_ar,
            'en' => $this->name_en,
        ],
        'image' => FileService::save($this->image, '[folder]'),
        'status' => $this->status,
    ]);
    $this->modalAdd = false;
    $this->dispatch('render')->component([Module]Data::class);
    flash()->success(__('lang.added_successfully', ['attribute' => __('lang.[module]')]));
}

public function resetData(): void
{
    $this->reset(['name_ar', 'name_en', 'image', 'status']);
    $this->status = 'inactive';
    $this->resetErrorBag();
    $this->resetValidation();
}
```

---

### 3. Update Component (Update[Module].php)

**Purpose:** Handle updating existing records via modal.

**Required Properties:**

```php
public bool $modalUpdate = false;
public Model $[module];           // The model being updated
public $field1;
public $field2;
public $image;
public $status;
```

**Required Methods:**

```php
public function mount(): void
{
    // For translatable fields
    $this->name_ar = $this->[module]->getTranslation('name', 'ar');
    $this->name_en = $this->[module]->getTranslation('name', 'en');

    // For enum fields
    $this->status = $this->[module]->status->value;

    // For regular fields
    $this->field = $this->[module]->field;
}

public function rules(): array
{
    return [
        'name_ar' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'image' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        'status' => 'required|in:active,inactive',
    ];
}

public function saveUpdate(): void
{
    $this->validate();
    $this->[module]->update([
        'name' => [
            'ar' => $this->name_ar,
            'en' => $this->name_en,
        ],
        'image' => FileService::update($this->[module]->image, $this->image, '[folder]'),
        'status' => $this->status,
    ]);
    $this->modalUpdate = false;
    $this->dispatch('render')->component([Module]Data::class);
    flash()->success(__('lang.updated_successfully', ['attribute' => __('lang.[module]')]));
}

public function render(): View
{
    return view('livewire.dashboard.[module].update-[module]');
}

public function resetError(): void
{
    $this->resetErrorBag();
    $this->resetValidation();
}
```

---

## Blade View Structure

### Data View ([module]-data.blade.php)

```blade
<div>
    <x-card title="{{ __('lang.[modules]') }}" shadow class="mb-3">
        <x-slot:menu>
            <livewire:dashboard.[module].create-[module] wire:key="{{\Illuminate\Support\Str::random(20)}}"></livewire:dashboard.[module].create-[module]>
        </x-slot:menu>

        <!-- Filters -->
        <div class="flex gap-3 mb-3 flex-wrap">
            <div class="w-64">
                <x-ui.choices-advanced-search
                    label="{{ __('lang.[modules]') }}"
                    wire:model.live="search_[module]_id"
                    :options="$all_[module]"
                    single clearable searchable
                    option-value="id"
                    option-label="name.ar"
                    placeholder="{{ __('lang.search') }}"/>
            </div>
            <div class="w-48">
                <x-select
                    label="{{__('lang.status')}}"
                    wire:model.live="filter_status"
                    :options="[['id' => 'active', 'name' => __('lang.active')], ['id' => 'inactive', 'name' => __('lang.inactive')]]"
                    placeholder="{{__('lang.all')}}"
                    option-value="id"
                    option-label="name"/>
            </div>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="min-w-full divide-y bg-base-300 text-base-content">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{__('lang.image')}}</th>
                            <th class="text-center">{{__('lang.name_ar')}}</th>
                            <th class="text-center">{{__('lang.name_en')}}</th>
                            <th class="text-center">{{__('lang.status')}}</th>
                            <th class="text-center">{{__('lang.created_at')}}</th>
                            <th class="text-center">{{__('lang.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($[modules] as $[module])
                        <tr class="bg-base-200">
                            <th class="text-center">{{$[modules]->firstItem() + $loop->index}}</th>
                            <th class="text-center">
                                @if($[module]->image)
                                    <x-avatar :image="\App\Services\FileService::get($[module]->image)" class="!w-12 !h-12"/>
                                @else
                                    <x-avatar image="https://via.placeholder.com/100" class="!w-12 !h-12"/>
                                @endif
                            </th>
                            <th class="text-nowrap">{{$[module]->getTranslation('name', 'ar')}}</th>
                            <th class="text-nowrap">{{$[module]->getTranslation('name', 'en')}}</th>
                            <th class="text-center">
                                <x-badge :value="$[module]->status->title()" class="bg-{{$[module]->status->color()}}"/>
                            </th>
                            <th class="text-center text-nowrap">{{formatDate($[module]->created_at,true) }}</th>
                            <td>
                                <div class="flex gap-2 justify-center">
                                    <livewire:dashboard.[module].update-[module] :[module]="$[module]" :key="\Illuminate\Support\Str::random(10)"/>
                                    <x-button icon="o-trash" class="btn-sm btn-ghost" wire:click="deleteSweetAlert({{$[module]->id}})" wire:loading.attr="disabled"
                                              wire:target="deleteSweetAlert({{$[module]->id}})" spinner="deleteSweetAlert({{$[module]->id}})" tooltip="{{__('lang.delete')}}"/>
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
                            {{ $[modules]->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>
```

### Create View (create-[module].blade.php)

```blade
<div>
    <x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{__('lang.add')}}" @click="$wire.modalAdd = true" wire:click="resetData"/>

    <x-modal wire:model="modalAdd" title="{{__('lang.add')}}" box-class="modal-box-600">
        <x-form wire:submit="saveAdd">
            <x-input label="{{__('lang.name_ar')}}" wire:model="name_ar"/>
            <x-input label="{{__('lang.name_en')}}" wire:model="name_en"/>
            <x-file label="{{__('lang.image')}}" wire:model="image" accept="image/*"/>
            <x-select label="{{__('lang.status')}}" wire:model="status" :options="[['id' => 'active', 'name' => __('lang.active')], ['id' => 'inactive', 'name' => __('lang.inactive')]]" option-value="id" option-label="name"/>

            <x-slot:actions>
                <x-button label="{{__('lang.cancel')}}" @click="$wire.modalAdd = false"/>
                <x-button label="{{__('lang.save')}}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveAdd"/>
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
```

### Update View (update-[module].blade.php)

```blade
<div>
    <x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{__('lang.update')}}" wire:click="resetError"/>

    <x-modal wire:model="modalUpdate" title="{{__('lang.update')}}" box-class="modal-box-600">
        <x-form wire:submit="saveUpdate">
            @if($[module]->image)
                <div class="flex justify-center mb-4">
                    <x-avatar :image="\App\Services\FileService::get($[module]->image)" class="!w-24 !h-24"/>
                </div>
            @endif

            <x-input label="{{__('lang.name_ar')}}" wire:model="name_ar"/>
            <x-input label="{{__('lang.name_en')}}" wire:model="name_en"/>
            <x-file label="{{__('lang.image')}}" wire:model="image" accept="image/*" hint="{{__('lang.leave_empty_keep_current')}}"/>
            <x-select label="{{__('lang.status')}}" wire:model="status" :options="[['id' => 'active', 'name' => __('lang.active')], ['id' => 'inactive', 'name' => __('lang.inactive')]]" option-value="id" option-label="name"/>

            <x-slot:actions>
                <x-button label="{{__('lang.cancel')}}" @click="$wire.modalUpdate = false"/>
                <x-button label="{{__('lang.update')}}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
```

---

## File Handling (FileService)

### Usage

**Always use FileService for file operations:**

```php
// Save new file
FileService::save($file, 'folder_name');

// Update existing file
FileService::update($oldPath, $newFile, 'folder_name');

// Delete file
FileService::delete($path);

// Get file URL
FileService::get($path);
```

### Image Conversion

-   All uploaded images are automatically converted to WebP format
-   Conversion happens in `ImageConversionService::convertToWebP()`
-   Original files are deleted after conversion
-   Quality is set to 85%

---

## Routes

Add routes in `routes/web.php`:

```php
Route::prefix('dashboard')->middleware(['auth', 'verified', 'is_admin'])->group(function () {
    Route::get('[modules]', [Module]Data::class)->name('[modules]');
});
```

---

## Navigation

Add menu items in `resources/views/components/dashboard/main-menu.blade.php`:

```blade
<x-menu-item noWireNavigate title="{{__('lang.[modules]')}}" icon="o-icon-name" link="{{route('[modules]')}}" />
```

---

## Translations

### Arabic (lang/ar/lang.php)

```php
// [Module]
'[modules]' => 'الجمع',
'[module]' => 'المفرد',
```

### English (lang/en/lang.php)

```php
// [Module]
'[modules]' => 'Plural',
'[module]' => 'Singular',
```

### Validation (lang/ar/validation.php)

Add custom attribute names if needed:

```php
'attributes' => [
    'field_name' => 'اسم الحقل',
],
```

---

## Models

### Translatable Fields

Use Spatie Translatable for multilingual fields:

```php
use Spatie\Translatable\HasTranslations;

class Model extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
```

### Enums

Use Laravel Enums for status fields:

```php
protected function casts(): array
{
    return [
        'status' => Status::class,
    ];
}
```

---

## Code Quality

### Laravel Pint

Always run Pint before committing:

```bash
vendor/bin/pint app/Livewire/Dashboard/[Module]/ resources/views/livewire/dashboard/[module]/
```

### Testing

Write Pest tests for all CRUD operations:

```php
test('[module] can be created', function () {
    $user = User::factory()->create();

    Volt::test('pages.[modules].create')
        ->actingAs($user)
        ->set('name_ar', 'Test')
        ->set('name_en', 'Test')
        ->call('saveAdd')
        ->assertHasNoErrors();

    expect(Model::where('name->ar', 'Test')->exists())->toBeTrue();
});
```

---

## Important Notes

1. **Always use `wire:key` with random strings** for dynamic components
2. **Use `getTranslation()` method** for translatable fields, not direct access
3. **Use `FileService::get()`** for displaying images, not `asset('storage/')`
4. **Always validate image uploads** with max size and allowed mimes
5. **Use SweetAlert** for delete confirmations
6. **Use flash messages** for success/error notifications
7. **Follow existing naming conventions** exactly
8. **Use `choices-advanced-search`** for searchable dropdowns
9. **Pagination is always 20 records** per page
10. **Default status is always 'inactive'**

---

## Common Patterns

### Relationship Filters

```php
// In Data component
public $filter_parent_id;
public $all_parents;

public function mount(): void
{
    $this->all_parents = Parent::get(['id', 'name'])->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
        ];
    })->toArray();
}

// In render method
->when($this->filter_parent_id, fn (Builder $query) => $query->where('parent_id', $this->filter_parent_id))
```

### URL Filters

Use `#[Url]` attribute for filters that should persist in URL:

```php
#[Url]
public $filter_category_id;
```

### Passing Data to Child Components

```php
<livewire:dashboard.[module].create-[module] :all_parents="$all_parents" wire:key="..."/>
```

---

## Folder Names

-   Categories: `categories`
-   Users: `users`
-   Products: `products`
-   Attributes: `attributes`
-   Coupons: `coupons`

---

## Example: Complete CRUD Checklist

When creating a new CRUD module:

-   [ ] Create migration
-   [ ] Create model with relationships and casts
-   [ ] Create [Module]Data component
-   [ ] Create Create[Module] component
-   [ ] Create Update[Module] component
-   [ ] Create [module]-data.blade.php view
-   [ ] Create create-[module].blade.php view
-   [ ] Create update-[module].blade.php view
-   [ ] Add route in web.php
-   [ ] Add menu item in main-menu.blade.php
-   [ ] Add Arabic translations
-   [ ] Add English translations
-   [ ] Run Laravel Pint
-   [ ] Test CRUD operations

---

This document should be your primary reference when building any CRUD functionality in this project. Follow these patterns exactly to maintain consistency across the codebase.
