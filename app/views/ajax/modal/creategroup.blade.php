<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Create Group</h4>
        </div>
        <div class="modal-body">
            {{ Form::open(array('url'=>'ajax/modal/create_group', 'class'=>'create-group-modal')) }}
                <div class="form-group">
                    <div class="alert"></div>
                    <input type="text" name="group-name" id="group_name"
                    class="form-control" placeholder="Name your group">
                </div>
                <div class="form-group">
                    <div class="alert"></div>
                    <select name="group-size" id="group_size"
                    class="form-control">
                        <option value="">Expected Group Size</option>
                        <option value="Not sure">I'm not sure</option>
                        <option value="1-5">1-5</option>
                        <option value="6-10">6-10</option>
                        <option value="11-15">11-15</option>
                        <option value="16-20">16-20</option>
                        <option value="21-25">21-25</option>
                        <option value="26+">26+</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="alert"></div>
                    <textarea name="group-description" id="group_description" class="form-control"
                    placeholder="Describe your group - Max. 260 characters"></textarea>
                </div>
            {{ Form::close() }}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="trigger_create_group">Create group</button>
        </div>
    </div>
</div>
