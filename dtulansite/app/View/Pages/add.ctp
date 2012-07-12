<h1>Add Page</h1>
<?php
echo $this->Form->create('Page');

echo $this->Form->input('title');


$options = array(0 => 'Text', 1 => 'URI');
$attributes = array('legend' => false);
echo $this->Form->radio('command', $options, $attributes);

echo $this->Form->input('text', array('rows' => '6'));
echo $this->Form->end('Save Page');
?>