<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex">
                <a href="{{ route('check.create') }}" class="inline-flex items-center px-4 py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Add Check') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-xl font-semibold">
                    {{ __('Last checks') }}
                </div>
            </div>

            @foreach($checks as $check)
                <div class="p-4 mt-2 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('check.partials.card')
                </div>
            @endforeach
        </div>
    </div>


    <div class="py-12 pt-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-between">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-xl font-semibold">
                    {{ __('Purses') }}
                </div>
                <div class="p-6 text-lg font-medium text-gray-900 dark:text-gray-100 flex">
                    {{ $purses->sum('cash') }} / {{ $sumIncomeCurrentMonth }} BYN
                </div>
            </div>
            @foreach($purses as $purse)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('purse.partials.home-card')
                </div>
            @endforeach
        </div>
    </div>

    <div class="py-12 pt-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-between">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-xl font-semibold">
                    {{ __('Categories') }}
                </div>
                <div class="p-6 text-lg font-medium text-gray-900 @if($sumTotalChecks <= $sumTotalPlans) dark:text-gray-100 @else dark:text-red-400 @endif flex">
                    {{ $sumTotalChecks }} / {{ $sumTotalPlans }} BYN
                </div>
            </div>
            @foreach($groups as $group)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 @if($group['groupCheckCash'] <= $group['groupCash']) dark:text-gray-100 @else dark:text-red-400 @endif flex justify-between">
                        <div>{{ $group['groupTitle'] }}</div>
                        <div>{{ $group['groupCheckCash'] }}  / {{ $group['groupCash'] }} BYN</div>
                    </h3>
                    @if(!count($group['items']))
                        <p class="text-gray-900 dark:text-gray-100">No category</p>
                    @endif

                    @foreach($group['items'] as $category)
                        @include('home.category.card')
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>


    <div class="py-12 pt-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-xl font-semibold">
                    <a href="{{ route("statistics.index") }}">{{ __("Statistics") }}</a>
                </div>
            </div>
        </div>
    </div>


    <div class="py-12 pt-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-xl font-semibold">
                    {{ __("Made with God's help!..") }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
