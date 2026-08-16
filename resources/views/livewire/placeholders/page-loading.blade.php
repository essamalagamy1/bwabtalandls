{{--
|--------------------------------------------------------------------------
| Page Loading Placeholder
|--------------------------------------------------------------------------
|
| يظهر أثناء تحميل الصفحات - Lazy Loading
| يعطي انطباع أن الصفحة تعمل وليست معطلة
|
--}}

<div class="animate-pulse">
    <x-card shadow class="mb-3">
        <x-slot:title>
            <div class="h-6 bg-base-300 rounded w-48"></div>
        </x-slot:title>
        <x-slot:menu>
            <div class="h-10 bg-base-300 rounded w-32"></div>
        </x-slot:menu>

        {{-- Search/Filter Skeleton --}}
        <div class="flex gap-3 mb-4 flex-wrap">
            <div class="h-10 bg-base-300 rounded w-64"></div>
            <div class="h-10 bg-base-300 rounded w-48"></div>
        </div>

        {{-- Table Skeleton --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="min-w-full divide-y bg-base-300">
                        <tr>
                            @for($i = 0; $i < 6; $i++)
                                <th class="text-center">
                                    <div class="h-4 bg-base-200 rounded w-16 mx-auto"></div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @for($row = 0; $row < 5; $row++)
                            <tr class="bg-base-200">
                                @for($col = 0; $col < 6; $col++)
                                    <td class="text-center">
                                        <div class="h-4 bg-base-300 rounded w-20 mx-auto"></div>
                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    </tbody>
                </table>

                {{-- Pagination Skeleton --}}
                <div class="flex items-center justify-between px-4 py-3 bg-base-300 sm:px-6">
                    <div class="flex gap-2">
                        <div class="h-8 bg-base-200 rounded w-20"></div>
                        <div class="h-8 bg-base-200 rounded w-8"></div>
                        <div class="h-8 bg-base-200 rounded w-8"></div>
                        <div class="h-8 bg-base-200 rounded w-8"></div>
                        <div class="h-8 bg-base-200 rounded w-20"></div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>

