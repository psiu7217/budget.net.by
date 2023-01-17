<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Category') }}
            </h2>
            <a href="{{route('category.index')}}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
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
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Create new category') }}</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Please fill in all the fields') }}</p>
                        </header>

                        <form method="post" action="{{route('category.store')}}" class="mt-6 space-y-6">
                            @csrf
                            @method('post')

                            <div>
                                <x-input-label for="title" :value="__('Title*')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="group_id" :value="__('Group*')" />
                                <select name="group_id" id="group_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->title }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('group_id')" />
                            </div>

                            <div>
                                <div class="flex">
                                    <x-checkbox name="hide" class="mx-1" />
                                    <x-input-label for="hide" :value="__('Just for me')" class="mx-3" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('hide')" />
                            </div>

                            <div>
                                <x-input-label for="sort" :value="__('Sort')" />
                                <x-text-input id="sort" name="sort" type="number" class="mt-1 block w-full" :value="old('sort', 1)" />
                                <x-input-error class="mt-2" :messages="$errors->get('sort')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status*')" />
                                <select name="status" id="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>
                                    @foreach($statuses as $key => $status)
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>


                            <div class="flex items-center justify-between gap-4">
                                <x-primary-button>{{ __('Create Category') }}</x-primary-button>
                            </div>

                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
