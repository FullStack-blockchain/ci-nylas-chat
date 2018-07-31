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
                        <div class="card col-md-7 p-0">
                            <div class="card-header"><h5><?php echo $article->subject; ?></h5></div>
                            <div class="card-body">
                                <?php echo $article->description; ?>
                                <hr/>
                                <h4 class="mt-4 f-20"><?php echo _l('clients_knowledge_base_find_useful'); ?></h4>
                                <div class="answer_response"></div>
                                <div class="btn-group mtop15 article_useful_buttons" role="group">
                                    <input type="hidden" name="articleid" value="<?php echo $article->articleid; ?>">
                                    <button type="button" data-answer="1"
                                            class="btn btn-success"><?php echo _l('clients_knowledge_base_find_useful_yes'); ?></button>
                                    <button type="button" data-answer="0"
                                            class="btn btn-danger"><?php echo _l('clients_knowledge_base_find_useful_no'); ?></button>
                                </div>
                            </div>
                        </div>

                        <?php if (count($related_articles) > 0) { ?>
                            <div class="card col-md-7 p-0">
                                <div class="card-header"><h6><?php echo _l('related_knowledgebase_articles'); ?></h6></div>
                                <div class="card-body">
                                    <ul class="mtop10 articles_list">
                                        <?php foreach ($related_articles as $rel_article_article) { ?>
                                            <li>
                                                <i class="fa fa-file-text-o"></i>
                                                <a href="<?php echo admin_url('knowledge_base/view/' . $rel_article_article['slug']); ?>"
                                                   class="article-heading"><?php echo $rel_article_article['subject']; ?></a>
                                                <div class="text-muted mtop10"><?php echo strip_tags(mb_substr($rel_article_article['description'], 0, 100)); ?>
                                                    ...
                                                </div>
                                            </li>
                                            <hr/>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
<?php init_tail(); ?>
<script>
    $(function () {
        $('.article_useful_buttons button').on('click', function (e) {
            e.preventDefault();
            var data = {};
            data.answer = $(this).data('answer');
            data.articleid = '<?php echo $article->articleid; ?>';
            $.post(admin_url + 'knowledge_base/add_kb_answer', data).done(function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $(this).focusout();
                }
                $('.answer_response').html(response.message);
            });
        });
    });
</script>
</body>
</html>
