<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Purse') }}
            </h2>
            <a href="{{route('purse.index')}}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
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

                        <form method="post" action="{{route('purse.update', $purse->id)}}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $purse->title)" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $purse->description)" />
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <x-input-label for="number" :value="__('Number')" />
                                <x-text-input id="number" name="number" type="text" class="mt-1 block w-full" :value="old('number', $purse->number)" />
                                <x-input-error class="mt-2" :messages="$errors->get('number')" />
                            </div>

                            <div>
                                <x-input-label for="pin" :value="__('Pin')" />
                                <x-text-input id="pin" name="pin" type="number" class="mt-1 block w-full" :value="old('pin', $purse->pin)" />
                                <x-input-error class="mt-2" :messages="$errors->get('pin')" />
                            </div>

                            <div>
                                <x-input-label for="currency" :value="__('Currency')" />
                                <x-text-input id="currency" name="currency" type="text" class="mt-1 block w-full" :value="old('currency', $purse->currency)" />
                                <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                            </div>

                            <div>
                                <x-input-label for="cash" :value="__('Cash*')" />
                                <x-text-input id="cash" name="cash" type="number" step="0.01" class="mt-1 block w-full" :value="old('cash', $purse->cash)" />
                                <x-input-error class="mt-2" :messages="$errors->get('cash')" />
                            </div>

                            <div>
                                <x-input-label for="hide" :value="__('Just for me')" />
                                <input name="hide" type="checkbox" id="hide" @if($purse->hide) checked @endif>
                                <x-input-error class="mt-2" :messages="$errors->get('hide')" />
                            </div>

                            <div>
                                <x-input-label for="sort" :value="__('Sort')" />
                                <x-text-input id="sort" name="sort" type="number" class="mt-1 block w-full" :value="old('sort', $purse->sort)" />
                                <x-input-error class="mt-2" :messages="$errors->get('number')" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <x-primary-button>{{ __('Update purse') }}</x-primary-button>
                            </div>

                        </form>

                    </section>
                </div>
            </div>

            @forelse($checks as $check)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('check.partials.card')
                </div>
            @empty
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <p>No checks</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
