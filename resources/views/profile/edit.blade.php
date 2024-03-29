<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b-2 text-center">
                    <a href="downloads/budget.apk">{{ __('Download Android App') }}</a>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('profile.partials.update-family')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Close this month') }}</h2>
                <form method="post" action="{{route('plan.close_month')}}" class="mt-6 space-y-6">
                    @csrf
                    @method('post')

                    <div class="flex items-center justify-between gap-4">
                        <x-primary-button>{{ __('Close') }}</x-primary-button>
                    </div>

                </form>

                <form method="post" action="{{route('plan.cancel_close_month')}}" class="mt-6 space-y-6">
                    @csrf
                    @method('post')

                    <div class="flex items-center justify-between gap-4">
                        <x-primary-button>{{ __('Cansel') }}</x-primary-button>
                    </div>

                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
