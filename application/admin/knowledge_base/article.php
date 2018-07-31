<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/knowledge-base.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content custom-scrollbar">

                <div id="articles-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="col-md-2"></div>
                        <div class="card col-md-8 p-0" style="margin: 0 auto">
                            <div class="card-body">
                                <?php echo form_open($this->uri->uri_string(), array('id' => 'article-form')); ?>
                                <h4 class="no-margin">
                                    <?php echo $title; ?>
                                    <?php if (isset($article)) { ?>
                                        <br/>
                                        <small>
                                            <?php if ($article->staff_article == 1) { ?>
                                                <a href="<?php echo admin_url('knowledge_base/view/' . $article->slug); ?>"
                                                   target="_blank"><?php echo admin_url('knowledge_base/view/' . $article->slug); ?></a>
                                            <?php } else { ?>
                                                <a href="<?php echo site_url('knowledge-base/article/' . $article->slug); ?>"
                                                   target="_blank"><?php echo site_url('knowledge-base/article/' . $article->slug); ?></a>
                                            <?php } ?>
                                        </small>
                                    <?php } ?>
                                </h4>
                                <?php if (isset($article)) { ?>
                                    <p>
                                        <small>
                                            <?php echo _l('article_total_views'); ?>
                                            : <?php echo total_rows('tblviewstracking', array('rel_type' => 'kb_article', 'rel_id' => $article->articleid)); ?>
                                        </small>
                                        <?php if (has_permission('knowledge_base', '', 'create')) { ?>
                                            <a href="<?php echo admin_url('knowledge_base/article'); ?>"
                                               class="btn btn-success pull-right"><?php echo _l('kb_article_new_article'); ?></a>
                                        <?php } ?>
                                        <?php if (has_permission('knowledge_base', '', 'delete')) { ?>
                                            <a href="<?php echo admin_url('knowledge_base/delete_article/' . $article->articleid); ?>"
                                               class="btn btn-danger _delete pull-right mright5"><?php echo _l('delete'); ?></a>
                                        <?php } ?>
                                    <div class="clearfix"></div>
                                    </p>
                                <?php } ?>
                                <hr class="hr-panel-heading"/>

                                <div class="clearfix"></div>
                                <?php $value = (isset($article) ? $article->subject : ''); ?>
                                <?php $attrs = (isset($article) ? array() : array('autofocus' => true)); ?>
                                <?php echo render_input('subject', 'kb_article_add_edit_subject', $value, 'text', $attrs); ?>
                                <?php if (isset($article)) {
                                    echo render_input('slug', 'kb_article_slug', $article->slug, 'text');
                                } ?>
                                <?php $value = (isset($article) ? $article->articlegroup : ''); ?>
                                <?php if (has_permission('knowledge_base', '', 'create')) {
                                    echo render_select_with_input_group('articlegroup', get_kb_groups(), array('groupid', 'name'), 'kb_article_add_edit_group', $value, '<a href="#" onclick="new_kb_group();return false;"><i class="fa fa-plus"></i></a>');
                                } else {
                                    echo render_select('articlegroup', get_kb_groups(), array('groupid', 'name'), 'kb_article_add_edit_group', $value);
                                }
                                ?>

                                <div class="form-check mt-4">
                                    <label class="form-check-label">
                                        <input type="checkbox" id="staff_article" class="form-check-input"
                                               name="staff_article" <?php if (isset($article) && $article->staff_article == 1) {
                                            echo 'checked';
                                        } ?>>
                                        <span class="checkbox-icon"></span>
                                        <span><?php echo _l('internal_article'); ?></span>
                                    </label>
                                </div>

                                <div class="form-check mb-4">
                                    <label class="form-check-label">
                                        <input type="checkbox" id="disabled" class="form-check-input"
                                               name="disabled" <?php if (isset($article) && $article->active_article == 0) {
                                            echo 'checked';
                                        } ?>>
                                        <span class="checkbox-icon"></span>
                                        <span><?php echo _l('kb_article_disabled'); ?></span>
                                    </label>
                                </div>

                                <p class="bold"><?php echo _l('kb_article_description'); ?></p>
                                <?php $contents = '';
                                if (isset($article)) {
                                    $contents = $article->description;
                                } ?>
                                <?php echo render_textarea('description', '', $contents, array(), array(), '', 'tinymce'); ?>

                                <?php echo form_close(); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php if ((has_permission('knowledge_base', '', 'create') && !isset($article)) || has_permission('knowledge_base', '', 'edit') && isset($article)) { ?>
                <nav id="footer" class="bg-white text-auto row no-gutters align-items-center px-6">
                    <div class="col-md-12">
                        <button type="submit" id="add_new_article"
                                class="btn btn-secondary text-capitalize pull-right ml-4"><?php echo _l('submit'); ?></button>
                    </div>
                </nav>
            <?php } ?>

        </div>
    </div>
</main>
<?php $this->load->view('admin/knowledge_base/group'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        _validate_form($('#article-form'), {subject: 'required', articlegroup: 'required'});

        $("#add_new_article").on("click", function (e) {
            e.preventDefault();
            $("#article-form").submit();
        });

    });
</script>
</body>
</html>
