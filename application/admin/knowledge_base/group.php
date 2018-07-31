<div class="modal fade" id="kb_group_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('knowledge_base/group'),array('id'=>'kb_group_form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_kb_group'); ?></span>
                    <span class="add-title"><?php echo _l('new_group'); ?></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','kb_group_add_edit_name'); ?>
                        <div id="kb_group_slug" class="hide">
                            <?php echo render_input('group_slug', 'kb_article_slug'); ?>
                        </div>
                        <?php echo render_color_picker('color',_l('kb_group_color')); ?>
                        <?php echo render_textarea('description','kb_group_add_edit_description'); ?>
                        <?php echo render_input('group_order','kb_group_order',total_rows('tblknowledgebasegroups') + 1,'number'); ?>

                        <div class="form-check mt-4">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="disabled" id="disabled" />
                                <span class="checkbox-icon"></span>
                                <span><?php echo _l('kb_group_add_edit_disabled'); ?></span>
                            </label>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    window.addEventListener('load',function(){

    // Validating the knowledge group form
    _validate_form($('#kb_group_form'), {
        name: 'required'
    }, manage_kb_groups);

    // On hidden modal reset the values
    $('#kb_group_modal').on("hidden.bs.modal", function(event) {
        $('#kb_group_slug').addClass('hide');
        $('#kb_group_slug input').rules('remove', 'required');
        $('#additional').html('');
        $('#kb_group_modal input').not('[type="hidden"]').val('');
        $('#kb_group_modal textarea').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
        $('#kb_group_modal input[name="group_order"]').val($('table tbody tr').length + 1);
    });
});
// Form handler function for knowledgebase group
function manage_kb_groups(form) {
    var data = $(form).serialize();
    var url = form.action;
    var articleAddEdit = $('body').hasClass('kb-article');
    if(articleAddEdit) {
        data+='&article_add_edit=true';
    }
    $.post(url, data).done(function(response) {
        if(!articleAddEdit) {
           window.location.reload();
        } else {
            response = JSON.parse(response);
            if(response.success == true){
                if(typeof(response.id) != 'undefined') {
                    var group = $('#articlegroup');
                    group.find('option:first').after('<option value="'+response.id+'">'+response.name+'</option>');
                    group.selectpicker('val',response.id);
                    group.selectpicker('refresh');
                }
            }
            $('#kb_group_modal').modal('hide');
        }
    });
    return false;
}

// New knowledgebase group, opens modal
function new_kb_group() {
    $('#kb_group_modal').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit KB group, 2 places groups view or articles view directly click on kanban
function edit_kb_group(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#kb_group_slug').removeClass('hide');
    $('#kb_group_slug input').rules('add', {required:true});
    $('#kb_group_slug input').val($(invoker).data('slug'));
    $('#kb_group_modal input[name="name"]').val($(invoker).data('name'));
    $('#kb_group_modal textarea[name="description"]').val($(invoker).data('description'));
    $('#kb_group_modal .colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
    $('#kb_group_modal input[name="group_order"]').val($(invoker).data('order'));
    $('input[name="disabled"]').prop('checked', ($(invoker).data('active') == 0 ? true : false));
    $('#kb_group_modal').modal('show');
    $('.add-title').addClass('hide');
}

</script>
