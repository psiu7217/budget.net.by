<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    @if($family)
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Family Information</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your family information or create new</p>
        </header>

        <form method="post" action="{{route('family.create')}}" class="mt-6 space-y-6">
            @csrf
            @method('post')

            <div>
                <x-input-label for="name" :value="__('Family Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Create new') }}</x-primary-button>
            </div>

        </form>

    @else

        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Family Information</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Find your family or create new</p>
        </header>

        <form method="post" action="{{route('family.find')}}" class="mt-6 space-y-6">
            @csrf
            @method('post')

            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Find Family</h3>
                <x-input-label for="unique_code" :value="__('Family Code')" />
                <x-text-input id="unique_code" name="unique_code" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Find family') }}</x-primary-button>
            </div>

        </form>

        <form method="post" action="{{route('family.create')}}" class="mt-6 space-y-6">
            @csrf
            @method('post')

            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Create new Family</h3>
                <x-input-label for="name" :value="__('Family Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Create new') }}</x-primary-button>
            </div>

        </form>
    @endif

</section>
