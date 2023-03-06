<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            <div class="flex">
                <a href="{{ route('group.index') }}" class="mx-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('My Groups') }}
                </a>
                <p></p>
                <a href="{{ route('category.create') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Add Category') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session()->get('error'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3><x-input-error class="mt-2 text-m" :messages="session()->get('error')" /></h3>
                </div>
            @endif

            @if(session()->get('status'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">{{session()->get('status')}}</h3>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">
                    <div>
                        {{ __('Total purses') }}
                    </div>
                    <div>
                        {{ $sumPurse }} BYN
                    </div>
                </h3>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">
                    <div>
                        {{ __('Total plans') }}
                    </div>
                    <div>
                        {{ $sumPlans }} BYN
                    </div>
                </h3>
            </div>

            @forelse($groups as $group)
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">
                            <div>{{ $group['groupTitle'] }}</div>
                            <div>{{ $group['groupCash'] }} BYN</div>
                        </h3>

                        @forelse($group['items'] as $category)
                            @include('category.partials.card')
                        @empty
                                <p class="text-gray-900 dark:text-gray-100">No category</p>
                        @endforelse
                    </div>
            @empty
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">{{ __('No Groups') }}</h3>
            @endforelse
        </div>
    </div>

</x-app-layout>
