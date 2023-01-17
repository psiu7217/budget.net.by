<section class="space-y-6">
    <header onclick="nextToggle(this)">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">
            <div class="flex">
                {{  $transaction->purse->title }} -> {{  $transaction->purseTo->title }}
            </div>
            <span>{{ $transaction->rate }} BYN</span>
        </h2>
        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400 mr-2">{{  $transaction->created_at }}</div>
    </header>
</section>
