<?php
if (!isset($idModal)) {
    $idModal = "myModal";
}
?>
<!-- Modal -->
<div class="modal fade" id="{{ $idModal }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
<?php if (isset($titleModal)) { ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $titleModal; ?></h4>
                </div>
<?php } ?>
            <div class="modal-body">
