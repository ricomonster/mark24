<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Add Forum Category</h4>
        </div>
        <div class="modal-body">
            {{ Form::open(array('url'=>'ajax/modal/add-forum-category', 'class'=>'add-category-modal')) }}
                <div class="form-group">
                    <label for="category-name">Category Name</label>
                    <div class="alert"></div>
                    <input type="text" name="category-name" class="form-control"
                    placeholder="Category Name" id="category_name">
                </div>

                <div class="form-group">
                    <label for="category-description">Description</label>
                    <div class="alert"></div>
                    <textarea name="category-description" class="form-control"
                    placeholder="Description" id="category_description"></textarea>
                </div>
            {{ Form::close() }}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="trigger_add_category">Add Category</button>
        </div>
    </div>
</div>
