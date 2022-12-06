<section class="space-y-6">
    <header class="border-t py-2 border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <p class="text-gray-400 dark:text-gray-200">{{  $category->title  }}</p>
{{--            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{  $purse->title  }}</p>--}}
{{--            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{  $income->created_at  }}</p>--}}

            <div class="flex">
                <a href="{{ route('category.edit', $category->id) }}" class="mx-2 inline-flex items-center px-2 py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Edit Category') }}
                </a>
                <form method="post" action="{{ route('category.destroy', $category->id) }}">
                    @csrf
                    @method('delete')

                    <div class="flex justify-end">
                        <x-danger-button>
                            {{ __('Delete Income') }}
                        </x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </header>

</section>
