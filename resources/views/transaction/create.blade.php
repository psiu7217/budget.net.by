<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Transaction') }}
            </h2>
            <a href="{{route('transaction.index')}}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
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
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Create new transaction') }}</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Please fill in all the fields') }}</p>
                        </header>

                        <form method="post" action="{{route('transaction.store')}}" class="mt-6 space-y-6">
                            @csrf
                            @method('post')

                            <div>
                                <x-input-label for="from_purse_id" :value="__('Purse From*')" />
                                <select name="from_purse_id" id="from_purse_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>
                                    @foreach($purses as $purse)
                                        <option value="{{ $purse->id }}">{{ $purse->title }} - {{ $purse->cash }} - {{ $purse->currency }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('from_purse_id')" />
                            </div>

                            <div>
                                <x-input-label for="to_purse_id" :value="__('Purse To*')" />
                                <select name="to_purse_id" id="to_purse_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>
                                    @foreach($purses as $purse)
                                        <option value="{{ $purse->id }}" @if ($loop->index == 1) selected @endif>{{ $purse->title }} - {{ $purse->cash }} - {{ $purse->currency }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('to_purse_id')" />
                            </div>

                            <div>
                                <x-input-label for="rate" :value="__('Rate*')" />
                                <x-text-input id="rate" name="rate" type="number" step="0.01" class="mt-1 block w-full" :value="old('rate', 0)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('rate')" />
                            </div>

                            <div class="flex items-center justify-center gap-4">
                                <x-primary-button class="w-full text-center flex justify-center">{{ __('Add transaction') }}</x-primary-button>
                            </div>

                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
