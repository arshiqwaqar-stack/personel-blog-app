<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('api.categories.store') }}"  id="storeCategoryForm" enctype="multipart/form-data">
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
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>    
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
                <input type="hidden" name="category_id">
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

    // $('#editCategoryForm').attr('action', url);
    $('[name="category_id"]').val(cat_id);
    $('#category-name').val(cat_name);
    $('#edit-cat-status').val(cat_status).change();

    $('#editCategoryModal').modal('show');
});


</script>
<script>
    //Using simple ajax
    // $(document).ready(function () {
        
    //     var table = $('.data-table').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: "{{ route('categories.create') }}",
    //         columns: [
    //             { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    //             // {data: 'id', name: 'id'},
    //             {data: 'name', name: 'name'},
    //             {data: 'status', name: 'status'},
    //             {data: 'action', name: 'action', orderable: false, searchable: false},
    //         ]
    //     });
    //     $('.dataTables_filter input').attr('placeholder', 'Search categories...');
    // });

    //using axios
    $(document).ready(function () {
        loadCategory();        
    });
    function loadCategory(){

        if ( ! $.fn.DataTable.isDataTable('.data-table') ) {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: function (data, callback, settings) {
                    axios.get("{{ route('api.categories.index') }}", {
                        params: data 
                    })
                    .then(function (response) {
                        callback(response.data); 
                    })
                    .catch(function (error) {
                        console.error("Error loading data:", error);
                    });
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        } else {
            $('.data-table').DataTable().ajax.reload(null, false); // just reload if already initialized
        }

        $('.dataTables_filter input').attr('placeholder', 'Search categories...');
    }
    $("#editCategoryForm").on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
        var cat_id = $(this).find('[name="category_id"]').val()
        var url = '{{ route("api.categories.update", ":id") }}';
        url = url.replace(':id', cat_id);
        formData.append('method','PATCH')
        var formObject = Object.fromEntries(formData.entries());
        axios.post(url, formObject, {
            params:formObject
        }).then(function (response) {
                Swal.fire({
                icon:response.data.type,
                text:response.data.message,
            })
            loadCategory();
            $('#editCategoryModal').modal('hide');
            })
            .catch(function (error) {
                Swal.fire({
                    icon:error.response.data.type,
                    text:error.response.data.message,
                })
                loadCategory();
                // console.error("Error loading data:", error);
            });
    });

    $("#storeCategoryForm").on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
        var formObject = Object.fromEntries(formData.entries());
        axios.post("{{ route('api.categories.store') }}" , formObject, {
            params:formObject
        }).then(function (response) {
                Swal.fire({
                icon:response.data.type,
                text:response.data.message,
            })
            document.getElementById("storeCategoryForm").reset();
            loadCategory();
            })
            .catch(function (error) {
                Swal.fire({
                    icon:error.response.data.type,
                    text:error.response.data.message,
                })
                loadCategory();
                // console.error("Error loading data:", error);
            });
    });
     function deleteCategory(categoryId,button) {
        axios.delete(`/api/categories/delete/${categoryId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(function (response) {
            Swal.fire({
                icon:response.data.type,
                text:response.data.message,
            })
            let categoryDiv = button.closest('tr');
            if (categoryDiv) {
                categoryDiv.remove();
            }

        })
        .catch(function (error) {
            if (error.response) {
                Swal.fire({
                    icon:error.response.data.type,
                    text:error.response.data.message,
                })
                // alert("Error: " + (error.response.data.message || "Something went wrong"));
            }
        });
    }
</script>
@endpush
</x-app-layout>