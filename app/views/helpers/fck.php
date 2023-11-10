<?php
class FckHelper extends Helper
{
	var $Width = 500;
	var $Height = 300;

    function load($id, $toolbar = 'Default', $width=null, $height=null) {
    	$did='';
        foreach (explode('/', $id) as $v) {
             $did .= ucfirst($v);
        }

        if($width){ $this->Width = $width; }
		if($height){ $this->Height = $height; }

        return <<<FCK_CODE
<script type="text/javascript">
fckLoader_$did = function () {
    var bFCKeditor_$did = new FCKeditor('$did');
    bFCKeditor_$did.BasePath = '/js/';
    bFCKeditor_$did.ToolbarSet = '$toolbar';

    bFCKeditor_$did.Width = $this->Width;
	bFCKeditor_$did.Height = $this->Height;

    bFCKeditor_$did.ReplaceTextarea();
}
fckLoader_$did();

</script>
FCK_CODE;
    }
}
?>
