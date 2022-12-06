<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{  $purse->title  }}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{  $purse->description  }}</p>
    </header>

    <div>
        <x-input-label for="unique_code_dis" :value="__('Number')" />
        <div class="flex justify-between">
            <x-text-input id="unique_code_dis" type="text" class="mt-1 block w-full" :value="$purse->number" disabled />
            <button onclick="copyToClipboard('{{ $purse->number }}'); return false;"><x-entypo-copy style="color: #e5e7eb;width: 30px;margin-left: 5px;" /></button>
        </div>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('purse.edit', $purse->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
            {{ __('Edit Purse') }}
        </a>
        <form method="post" action="{{ route('purse.destroy', $purse->id) }}">
            @csrf
            @method('delete')

            <div class="flex justify-end">
                <x-danger-button class="ml-3">
                    {{ __('Delete Purse') }}
                </x-danger-button>
            </div>
        </form>
    </div>

</section>
