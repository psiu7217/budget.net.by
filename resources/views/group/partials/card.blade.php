<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">
            <div>{{  $group->title  }}</div>
            <div class="flex justify-between">
                <a href="{{ route('group.edit', $group->id) }}" class="mx-4 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Edit Group') }}
                </a>
                <form method="post" action="{{ route('group.destroy', $group->id) }}">
                    @csrf
                    @method('delete')

                    <div class="flex justify-end">
                        <x-danger-button>
                            {{ __('Delete Group') }}
                        </x-danger-button>
                    </div>
                </form>
            </div>
        </h2>
    </header>


</section>
