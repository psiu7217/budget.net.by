<section class="space-y-6">
    <header onclick="nextToggle(this)">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">
            <div class="flex">
                {{  $check->title  }}
            </div>
            <span>{{ $check->cash }} {{$check->purse->currency}}</span>
        </h2>
    </header>

    <div class="none">
        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400 mr-2">{{  $check->category->group->title  }} - {{  $check->category->title  }}</div>
        <div class="flex justify-between">
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{  $check->purse->title  }}</p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{  $check->created_at  }}</p>
        </div>
        <div class="flex justify-between pt-6">
            <a href="{{ route('check.edit', $check->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Edit Check') }}
            </a>
            <form method="post" action="{{ route('check.destroy', $check->id) }}">
                @csrf
                @method('delete')

                <div class="flex justify-end">
                    <x-danger-button>
                        {{ __('Delete Check') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </div>


</section>
