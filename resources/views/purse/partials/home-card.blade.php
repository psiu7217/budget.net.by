<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">
            <span class="flex">
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400 mr-2">{{  $purse->user->name  }} - </div>
                {{  $purse->title  }}
                @if($purse->hide)
                    <x-zondicon-view-hide style="width: 20px;margin-left: 15px;" />
                @endif
            </span>
            {{ $purse->cash }} {{ $purse->currency }}
        </h2>
    </header>
</section>
