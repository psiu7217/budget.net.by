<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Purse') }}
            </h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Create new purse</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Please fill in all the fields</p>
                        </header>

                        <form method="post" action="{{route('purse.store')}}" class="mt-6 space-y-6">
                            @csrf
                            @method('post')

                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description')" />
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <x-input-label for="number" :value="__('Number')" />
                                <x-text-input id="number" name="number" type="text" class="mt-1 block w-full" :value="old('number')" />
                                <x-input-error class="mt-2" :messages="$errors->get('number')" />
                            </div>

                            <div>
                                <x-input-label for="hide" :value="__('Just for me')" />
                                <x-checkbox name="hide"/>
                                <x-input-error class="mt-2" :messages="$errors->get('hide')" />
                            </div>

                            <div>
                                <x-input-label for="sort" :value="__('Sort')" />
                                <x-text-input id="sort" name="sort" type="number" class="mt-1 block w-full" :value="old('sort')" />
                                <x-input-error class="mt-2" :messages="$errors->get('number')" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <x-primary-button>{{ __('Create purse') }}</x-primary-button>
                            </div>

                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
