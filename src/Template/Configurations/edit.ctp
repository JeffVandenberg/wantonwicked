<?php
use App\Model\Entity\Configuration;
use App\View\AppView;

/* @var AppView $this */
/* @var Configuration[] $configs */
$this->set('title_for_layout', 'Edit Game Configuration');
?>

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
                    <?php echo $this->Form->hidden($i .'.key', array('value' => $config->key)); ?>
                    <?php echo $config->description; ?>
                </td>
                <td>
                <?php if($config->data_type == 'number'): ?>
                    <?php echo $this->Form->text(
                                          $i .'.value',
                                          array(
                                              'value' => $config->value,
                                              'label' => false,
                                              'style' => 'width:50px;'
                                          )
                    ); ?>
                <?php elseif($config->data_type == 'text'): ?>
                    <?php echo $this->Form->textarea(
                                          $i .'.value',
                                          array(
                                              'value' => $config->value,
                                              'label' => false,
                                              'class' => 'tinymce full-editor'
                                          )
                    ); ?>
                <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<button class="button" type="submit">Update</button>
<?php echo $this->Form->end(); ?>
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
