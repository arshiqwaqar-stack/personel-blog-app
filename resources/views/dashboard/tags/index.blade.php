<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tags') }}
        </h2>
    </x-slot>

    <div class="container">
        <div>
            <h1 class="text-white">Categories</h1>
        </div>
            <x-button class="float-right m-2" type="button" href="{{ route('tags.create') }}" variant="primary">
                    Add Tags
            </x-button>
         <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
    </div>
    <!-- Edit tag Modal -->
<div class="modal fade" id="edittagModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edittagForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Edit tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tag-name" class="form-label">tag Name</label>
                        <input type="text" name="name" id="tag-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <x-input-label for="edit-tag-status" :value="__('tag Status')" />
                        <x-select
                            name="status"
                            id="edit-tag-status"
                            :options="['1'=>'Active','0'=>'Inactive']"
                            selected=""
                        />
                    </div>
                    <div class="mb-3">
                        <x-input-label for="edit-tag-cat" :value="__('Category')" />
                        <x-select
                            name="category_id"
                            id="edit-tag-cat"
                            :options="$categories"
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
    $(document).on('click', '.edit-tag-btn', function () {
    var selected_btn = $(this);
    // var cat_id = selected_btn.attr('tag-id');
    var cat_name = selected_btn.attr('tag-name');
    var cat_status = selected_btn.attr('tag-status');
    var tag_category = selected_btn.attr('category-id');
    var url = selected_btn.attr('data-url');
    // url = url.replace(':id', cat_id);
    $('#edittagForm').attr('action', url);
    $('#tag-name').val(cat_name);
    $('#edit-tag-status').val(cat_status).change();
    $('#edit-tag-cat').val(tag_category).change();

    $('#edittagModal').modal('show');
});

</script>
<script>
    $(document).ready(function () {
        
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.tags.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                // {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'category', name: 'category'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
        $('.dataTables_filter input').attr('placeholder', 'Search tags...');
    });
</script>
@endpush
</x-app-layout>