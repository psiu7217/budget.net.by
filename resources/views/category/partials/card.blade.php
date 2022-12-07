<section class="space-y-6">
    <header class="border-t py-2 border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <p class="text-gray-400 dark:text-gray-200 w-3/4">{{  $category->title  }}</p>
            <p class="text-gray-400 dark:text-gray-200">{{  $category->plans->sortByDesc('created_at')->first()->cash  }}</p>
            <div class="flex">
                <a href="{{ route('category.edit', $category->id) }}" class="mx-2 inline-flex items-center px-2 py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    <x-zondicon-edit-pencil class="h-6 w-6 text-yellow-500" />
                </a>
                <form method="post" action="{{ route('category.destroy', $category->id) }}">
                    @csrf
                    @method('delete')
                    <x-danger-button>
                        <x-zondicon-trash class="h-6 w-6"/>
                    </x-danger-button>
                </form>
            </div>
        </div>
    </header>

</section>
