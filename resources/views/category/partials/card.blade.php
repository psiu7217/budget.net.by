<section class="space-y-6">
    <header class="border-t py-2 border-gray-100 dark:border-gray-700">
        <a href="{{ route('category.edit', $category['categoryId']) }}" class="flex justify-between items-center">
            <span class="text-gray-400 dark:text-gray-200">{{  $category['categoryTitle']  }}</span>
            <span class="text-gray-400 dark:text-gray-200">{{  $category['planCash']  }} BYN</span>
        </a>
    </header>

</section>
