<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('tags.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="category" :value="__('Category')" />
                        <x-select 
                            id="category"
                            class="block mt-1 w-full "
                            name="category_id" 
                            :options="$categories" 
                            selected=""
                        />

                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <x-input-label for="edit-cat-status" :value="__('Category Status')" />
                        <x-select
                            name="status"
                            id="edit-cat-status"
                            :options="['1'=>'Active','0'=>'Inactive']"
                            selected="1"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">

                        <x-button class="ms-4 m-2" type="submit" variant="primary">
                            {{ __('Create') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>