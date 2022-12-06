<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">
            {{  $income->title  }}
            <span>{{ $income->cash }} {{$purse->currency}}</span>
        </h2>
        <div class="flex justify-between">
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{  $purse->title  }}</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{  $income->created_at  }}</p>
        </div>
    </header>

    <div class="flex justify-between">
        <a href="{{ route('income.edit', $income->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
            {{ __('Edit Income') }}
        </a>
        <form method="post" action="{{ route('income.destroy', $income->id) }}">
            @csrf
            @method('delete')

            <div class="flex justify-end">
                <x-danger-button>
                    {{ __('Delete Income') }}
                </x-danger-button>
            </div>
        </form>
    </div>

    @if($purse->pin)
        <x-modal name="show-pin-{{$purse->id}}" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <div class="p-6 text-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 p-4">{{ $purse->pin  }}</h2>
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
            </div>
        </x-modal>
    @endif

</section>
