<div class="modal-dialog modal-small-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Join Group</h4>
        </div>
        <div class="modal-body">
            {{ Form::open(array('url'=>'ajax/modal/join_group', 'class'=>'join-group-modal')) }}
                <div class="form-group">
                    <div class="alert"></div>
                    <input type="text" name="group-code" id="group_code"
                    class="form-control" placeholder="Group code...">
                </div>
            {{ Form::close() }}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="trigger_join_group">Join group</button>
        </div>
    </div>
</div>
