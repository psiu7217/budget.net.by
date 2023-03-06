<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Goals') }}
        </h2>
        <div class="flex">
            <a href="{{ route('goal.create') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Add Goal') }}
            </a>
        </div>
    </div>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session()->get('error'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3><x-input-error class="mt-2 text-m" :messages="session()->get('error')" /></h3>
                </div>
            @endif

            @if(session()->get('status'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">{{session()->get('status')}}</h3>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-between">
                <div class="p-6 text-gray-900 dark:text-gray-100 w-full">
                    <div class="card-body">
                        <table class="table w-full text-left">
                            <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Progress') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($goals as $goal)
                                <tr>
                                    <td>{{ $goal->title }}</td>
                                    <td>{{ $goal->target_amount }} BYN</td>
                                    <td>{{ $goal->getProgressPercentage() }}%</td>
                                    <td>{{ $goal->is_achieved ? __('Achieved') : __('In Progress') }}</td>
                                    <td class="flex justify-flex-end">
                                        <a href="{{ route('goal.edit', $goal->id) }}" class="mx-4 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                            {{ __('Edit') }}
                                        </a>
                                        <form method="post" action="{{ route('goal.destroy', $goal->id) }}">
                                            @csrf
                                            @method('delete')

                                            <div class="flex justify-end">
                                                <x-danger-button>
                                                    {{ __('Delete') }}
                                                </x-danger-button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section>

                            <div class="space-y-6">
                                <div class=" mt-4">

                                    <p class="text-gray-400 dark:text-gray-200 font-medium ">{{ __('Go to goal!') }}</p>

                                    <form method="post" action="{{route('goal.add_amount')}}" class="mt-6 space-y-6">
                                        @csrf
                                        @method('post')

                                        <div>
                                            <x-input-label for="goal_id" :value="__('Goal*')" />
                                            <select name="goal_id" id="goal_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>
                                                @foreach($goals as $goal)
                                                    <option value="{{ $goal->id }}">{{ $goal->title }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('goal_id')" />
                                        </div>

                                        <div>
                                            <x-input-label for="amount" :value="__('Amount*')" />
                                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount')" required />
                                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                                        </div>

                                        <div class="flex items-center justify-between gap-4 mt-2">
                                            <x-primary-button class="w-full">{{ __('Go') }}</x-primary-button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                    </section>
                </div>



        </div>
    </div>

</x-app-layout>
