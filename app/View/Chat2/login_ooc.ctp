<?php
/* @var View $this */
$this->set('title_for_layout', 'Wanton Wicked Chat');

?>
<iframe
    src="https://discordapp.com/widget?id=108688223261175808&theme=dark&username=<?php echo AuthComponent::user('username'); ?>" style="width:100%;height:800px;" allowtransparency="true" frameborder="0"></iframe>