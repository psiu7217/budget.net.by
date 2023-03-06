<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Goal') }}
            </h2>
            <a href="{{route('goal.index')}}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    <section>
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Create new Goal</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Please fill in all the fields</p>
                        </header>

                        <form method="post" action="{{route('goal.store')}}" class="mt-6 space-y-6">
                            @csrf
                            @method('post')

                            <div>
                                <x-input-label for="title" :value="__('Title*')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="target_amount" :value="__('Target amount*')" />
                                <x-text-input id="target_amount" name="target_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('target_amount')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('target_amount')" />
                            </div>

                            <div>
                                <x-input-label for="current_amount" :value="__('Current amount')" />
                                <x-text-input id="current_amount" name="current_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('current_amount')" />
                                <x-input-error class="mt-2" :messages="$errors->get('current_amount')" />
                            </div>

                            <div>
                                <x-input-label for="deadline" :value="__('Deadline')" />
                                <x-text-input type="date" id="deadline" name="deadline" class="mt-1 block w-full" :value="old('deadline')" required/>
                                <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <x-primary-button>{{ __('Create Goal') }}</x-primary-button>
                            </div>

                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
