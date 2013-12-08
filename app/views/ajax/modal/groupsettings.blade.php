<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Group Settings</h4>
        </div>
        <div class="modal-body">
            {{ Form::open(array('url'=>'ajax/modal/update-group-settings', 'class'=>'update-group-settings')) }}
                <div class="form-group">
                    <div class="alert"></div>
                    <input type="text" name="group-name" id="group_name"
                    class="form-control" placeholder="Name your group"
                    value="{{ $group->group_name }}">
                </div>
                <div class="form-group">
                    <div class="alert"></div>
                    <select name="group-size" id="group_size"
                    class="form-control">
                        <option value="">Expected Group Size</option>
                        <option value="Not sure">I'm not sure</option>
                        <option value="1-5"
                        <?php echo($group->group_size == '1-5') ? 'selected' : null; ?>>1-5</option>
                        <option value="6-10"
                        <?php echo($group->group_size == '6-10') ? 'selected' : null; ?>>6-10</option>
                        <option value="11-15"
                        <?php echo($group->group_size == '11-15') ? 'selected' : null; ?>>11-15</option>
                        <option value="16-20"
                        <?php echo($group->group_size == '16-20') ? 'selected' : null; ?>>16-20</option>
                        <option value="21-25"
                        <?php echo($group->group_size == '21-25') ? 'selected' : null; ?>>21-25</option>
                        <option value="26+"
                        <?php echo($group->group_size == '26+') ? 'selected' : null; ?>>26+</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="alert"></div>
                    <textarea name="group-description" id="group_description" class="form-control"
                    placeholder="Describe your group - Max. 260 characters">{{ $group->group_description }}</textarea>
                </div>

                <input type="hidden" name="group-id" value="{{ $group->group_id }}">
            {{ Form::close() }}
        </div>
        <div class="modal-footer">
            <a href="#" class="pull-left" id="confirm-delete-group"
            data-group-id="{{ $group->group_id }}">Delete Group</a>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="trigger_update_group">Save Settings</button>
        </div>
    </div>
</div>
