<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Income') }}
            </h2>
            <a href="{{route('income.index')}}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
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


                        <form method="post" action="{{route('income.update', $income->id)}}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="title" :value="__('Title*')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $income->title)" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>


                            <div>
                                <x-input-label for="cash" :value="__('Cash*')" />
                                <x-text-input id="cash" name="cash" type="number" step="0.01" class="mt-1 block w-full" :value="old('cash', $income->cash)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('cash')" />
                            </div>

                            <div>
                                <x-input-label for="purse_id" :value="__('Purse*')" />
                                <select name="purse_id" id="purse_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>
                                    @foreach($purses as $purse)
                                        <option value="{{ $purse->id }}" @if($purse->id == $income->purse_id) selected @endif>{{ $purse->title }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('purse_id')" />
                            </div>

                            <div>
                                <x-input-label for="created_at" :value="__('Created at')" />
                                <x-text-input id="created_at" name="created_at" type="datetime-local" class="mt-1 block w-full" :value="old('created_at', $income->created_at)" />
                                <x-input-error class="mt-2" :messages="$errors->get('created_at')" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <x-primary-button>{{ __('Update income') }}</x-primary-button>
                            </div>

                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
