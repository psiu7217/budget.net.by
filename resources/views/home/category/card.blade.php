<section class="space-y-6">
    <header class="border-t py-2 border-gray-100 dark:border-gray-700">
        <a href="{{ route('category.edit', $category->id) }}" class="flex justify-between items-center">
            <span class="text-gray-400 dark:text-gray-200">{{  $category->title  }}</span>
            <span class="text-gray-400 @if($category->sumChecks <= $category->latestPlanAmount) dark:text-gray-200 @else dark:text-red-400 @endif">
                {{  $category->checks->where('created_at', '>', $user->start_date_month)->sum('cash')|round(0)   }}
                 /
                {{  $category->plans->sortByDesc('created_at')->first()->cash|round(0)  }} BYN
            </span>
        </a>
    </header>

</section>
