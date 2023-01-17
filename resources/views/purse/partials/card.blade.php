<section class="space-y-6">
    <header onclick="nextToggle(this)">
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
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{  $purse->description  }}</p>
    </header>

    <div class="none">
        @if($purse->number)
            <div>
                <x-input-label for="unique_code_dis" :value="__('Number')" />
                <div class="flex justify-between">
                    <x-text-input id="unique_code_dis" type="text" class="mt-1 block w-full" :value="$purse->number" disabled />
                    @if($purse->pin)
                        <button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'show-pin-{{$purse->id}}')">
                            <x-gmdi-fiber-pin-r style="color: #e5e7eb;width: 30px;margin: 0px 20px;" />
                        </button>
                    @endif
                    <button onclick="copyToClipboard('{{ $purse->number }}'); return false;"><x-entypo-copy style="color: #e5e7eb;width: 30px;margin-left: 10px;" /></button>
                </div>
            </div>
        @endif

        <div class="flex justify-between pt-6">
            <a href="{{ route('purse.edit', $purse->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Edit Purse') }}
            </a>
            <form method="post" action="{{ route('purse.destroy', $purse->id) }}">
                @csrf
                @method('delete')

                <div class="flex justify-end">
                    <x-danger-button>
                        {{ __('Delete Purse') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
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
