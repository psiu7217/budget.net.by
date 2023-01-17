<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Category') }}
            </h2>
            <a href="{{route('category.index')}}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    <section>
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>


                        <form method="post" action="{{route('category.update', $category->id)}}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="title" :value="__('Title*')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $category->title)" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="group_id" :value="__('Group*')" />
                                <select name="group_id" id="group_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" @if($group->id == $category->group_id) selected @endif>{{ $group->title }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('group_id')" />
                            </div>

                            <div>
                                <div class="flex">
                                    <input name="hide" type="checkbox" id="hide" class="mx-1" @if($category->hide) checked @endif>
                                    <x-input-label for="hide" :value="__('Just for me')" class="mx-3" />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('hide')" />
                            </div>

                            <div>
                                <x-input-label for="sort" :value="__('Sort')" />
                                <x-text-input id="sort" name="sort" type="number" class="mt-1 block w-full" :value="old('sort', $category->sort)" />
                                <x-input-error class="mt-2" :messages="$errors->get('sort')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status*')" />
                                <select name="status" id="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>
                                    @foreach($statuses as $key => $status)
                                        <option value="{{ $key }}" @if($category->status == $key) selected @endif>{{ $status }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <x-primary-button>{{ __('Update Category') }}</x-primary-button>
                            </div>
                        </form>


                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="post" action="{{ route('category.destroy', $category->id) }}">
                    @csrf
                    @method('delete')
                    <x-danger-button>
                        <x-zondicon-trash class="h-6 w-6"/> Delete category
                    </x-danger-button>
                </form>
            </div>


            @foreach($category->checks->where('created_at', '>', $user->start_date_month) as $check)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('check.partials.card')
                </div>
            @endforeach

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section>
                    @foreach($plans as $plan)
                        <div class="space-y-6">
                            <div class="@if ($loop->first) border-b-2 py-4 @else mt-4 @endif">

                                @if ($loop->first)
                                    <p class="text-gray-400 dark:text-gray-200 font-medium text-sm">Current plan for this month</p>
                                @endif

                                @if ($loop->iteration == 2)
                                        <p class="text-gray-400 dark:text-gray-200 font-medium text-sm">Other plans</p>
                                @endif

                                <div class="flex justify-between">
                                    <p class="text-gray-400 dark:text-gray-200">{{ $plan->cash }} BYN</p>
                                    <p class="text-gray-400 dark:text-gray-200">{{ ($plan->created_at)->format('d-m-Y') }}</p>
                                </div>
                                @if ($loop->first)
                                        <form method="post" action="{{route('plan.update', $plan->id)}}" class="border-t my-4 py-4">
                                            @csrf
                                            @method('patch')

                                            <div>
                                                <x-input-label for="cash" :value="__('Cash*')" />
                                                <x-text-input id="cash" name="cash" type="number" step="0.01" class="mt-1 block w-full" :value="old('cash', $plan->cash)" required />
                                                <x-input-error class="mt-2" :messages="$errors->get('cash')" />
                                            </div>

                                            <div class="flex items-center justify-between gap-4 mt-2">
                                                <x-primary-button class="w-full">{{ __('Update Current Plan') }}</x-primary-button>
                                            </div>

                                        </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </section>
            </div>
        </div>
    </div>

</x-app-layout>
