<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="m-2">
                        <x-input-label for="name" :value="__('Name')" class="m-2" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="m-2">
                        <x-input-label for="email" :value="__('Category')" class="m-2" />
                        <x-select
                        name="status"
                        :options="['1'=>'Active','0'=>'Inactive']"
                        selected="{{old('name','1')}}"
                        >
    
                        </x-select>
                    </div>

                    <div class="flex items-center justify-end mt-4">

                        <x--button type="submit" variant="primary" class="m-2">
                            {{ __('Create') }}
                        </x--button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="container">
        <div>
            <h1 class="text-white">Categories</h1>
        </div>
         <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{$category->name??''}}</td>
                                <td>{{($category->status?'Active':'Inactive')}}</td>
                                <td class="d-flex ">
                                    <x-button type="button" class="edit-category-btn" category-id="{{ $category->id }}" category-status="{{ $category->status }}" category-name="{{ $category->name }}" variant="primary">
                                        Edit
                                    </x-button>
                                   <form class="ml-2" action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <x-button type="submit" variant="danger">
                                            Delete
                                        </x-button>
                                    </form>


                                </td>
                            </tr>
                        @empty

                        @endforelse
                        
                    </tbody>
                </table>
            </div>
    </div>
    <!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category-name" class="form-label">Category Name</label>
                        <input type="text" name="name" id="category-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <x-input-label for="edit-cat-status" :value="__('Category Status')" />
                        <x-select
                            name="status"
                            id="edit-cat-status"
                            :options="['1'=>'Active','0'=>'Inactive']"
                            selected=""
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    $(document).on('click', '.edit-category-btn', function () {
    var selected_btn = $(this);
    var cat_id = selected_btn.attr('category-id');
    var cat_name = selected_btn.attr('category-name');
    var cat_status = selected_btn.attr('category-status');
    var url = '{{ route("categories.update", ":id") }}';
    url = url.replace(':id', cat_id);

    $('#editCategoryForm').attr('action', url);
    $('#category-name').val(cat_name);
     $('#edit-cat-status').val(cat_status).change();

    $('#editCategoryModal').modal('show');
});

</script>
@endpush
</x-app-layout>