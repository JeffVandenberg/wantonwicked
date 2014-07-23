<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Edit Game Configuration'); ?>
<?php $this->start('script'); ?>
<?php echo $this->Html->script('tinymce/tinymce.min'); ?>
<?php echo $this->Html->script('tinymce/jquery.tinymce.min'); ?>
<?php $this->end(); ?>

    <h2><?php echo __('Edit Configuration'); ?></h2>
<?php echo $this->Form->create('Configuration'); ?>
    <table>
        <thead>
        <tr>
            <th>
                Setting
            </th>
            <th>
                Value
            </th>
        </tr>
        </thead>
        <?php foreach($configs as $i => $config): ?>
            <tr>
                <td style="vertical-align: middle;">
                    <?php echo $this->Form->hidden($i .'.Configuration.key', array('value' => $config['Configuration']['key'])); ?>
                    <?php echo $config['Configuration']['description']; ?>
                </td>
                <td>
                <?php if($config['Configuration']['data_type'] == 'number'): ?>
                    <?php echo $this->Form->text(
                                          $i .'.Configuration.value',
                                          array(
                                              'value' => $config['Configuration']['value'],
                                              'label' => false,
                                              'style' => 'width:30px;'
                                          )
                    ); ?>
                <?php elseif($config['Configuration']['data_type'] == 'text'): ?>
                    <?php echo $this->Form->textarea(
                                          $i .'.Configuration.value',
                                          array(
                                              'value' => $config['Configuration']['value'],
                                              'label' => false,
                                              'class' => 'tinymce full-editor'
                                          )
                    ); ?>
                <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php echo $this->Form->end('Update'); ?>
<script>
    tinymce.init({
        selector: "textarea.full-editor",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        height: 600
    });
</script>