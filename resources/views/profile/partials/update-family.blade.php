<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    @if($family)
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Family Information</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your family information or leave</p>
        </header>

        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Members:') }}</h3>

            <ul class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-1 border-b">
                @foreach($family->users as $user)
                    <li>{{ $user->name }}</li>
                @endforeach
            </ul>
        </div>

        <form method="post" action="{{route('family.update')}}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="name" :value="__('Family Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $family->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="unique_code_dis" :value="__('Family Code')" />
                <div class="flex justify-between">
                    <x-text-input id="unique_code_dis" type="text" class="mt-1 block w-full" :value="old('unique_code', $family->unique_code)" disabled />
                    <button onclick="copyToClipboard('{{ $family->unique_code }}'); return false;"><x-entypo-copy style="color: #e5e7eb;width: 30px;margin-left: 5px;" /></button>
                </div>
            </div>

            <div>
                <x-input-label for="first_day" :value="__('First day month')" />
                <x-text-input id="first_day" name="first_day" type="text" class="mt-1 block w-full" :value="old('first_day', $family->first_day)" required autofocus autocomplete="first_day" />
                <x-input-error class="mt-2" :messages="$errors->get('first_day')" />
            </div>

            <div class="flex items-center justify-between gap-4">
                <x-primary-button>{{ __('Update') }}</x-primary-button>
                <x-danger-button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-family-leave')"
                >Family Leave</x-danger-button>
            </div>

        </form>

        <x-modal name="confirm-family-leave" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('family.leave') }}" class="p-6">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Are you sure your want to leave your family?</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">After leaving the family, you can create a new family or add an existing family using the family code. The family in which there will be no participants will be deleted</p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        {{ __(' Leave the family') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

    @else

        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Family Information</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Find your family or create new</p>
        </header>

        <form method="post" action="{{route('family.find')}}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Find Family</h3>
                <x-input-label for="unique_code" :value="__('Family Code')" />
                <x-text-input id="unique_code" name="unique_code" type="text" class="mt-1 block w-full" :value="old('unique_code')" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('unique_code')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Find family') }}</x-primary-button>
            </div>

        </form>

        <form method="get" action="{{route('family.create')}}" class="mt-6 space-y-6">
            @csrf
            @method('get')

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
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        return false;
    }
</script>
