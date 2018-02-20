<?php

use App\Model\Entity\Request;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var bool $isRequestManager
 */

$this->set('title_for_layout', 'Request: ' . $request->title);

echo $this->cell('Request', [$request]);
?>
<?php $this->start('script'); ?>
<script>
    $(function () {
        $(document).on('click', '.ajax-link', (function (e) {
            var url = $(this).attr('href');
            $("#modal-subview-content")
                .load(
                    url,
                    null,
                    function () {
                        $("#modal-subview").foundation('open');
                    }
                );
            e.preventDefault();
        }));
    });
</script>
<?php $this->end(); ?>

