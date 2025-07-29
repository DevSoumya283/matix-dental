<!-- Backup  Modal -->
<div id="exportRangeModal" class="modal modal--m">
    <div class="modal__wrapper modal__wrapper--transition padding--l">
        <a href="javascript:void(0);" class="modal__close modal--toggle icon icon--x"><svg class="icon icon--x"><use xlink:href="#icon-x"></use></svg></a>
        <div class="modal__header center center--h align--left">
            <h2 class="fontSize--l">Backup Products</h2>
            
        </div>
        <div class="modal__body center center--h align--left cf">
            <div class="modal__content">
                <form id="exportRangeForm" method="POST" action="<?php echo base_url(); ?>Backup-Products">
                <label for="rangeSelect">Choose a product range:</label>
               <select id="rangeSelect" name="range" class="input input__text" required>
                    <?php
                    $step = 10000;
                    for ($i = 0; $i < $total_count; $i += $step) {
                        $start = $i;
                        $end = min($i + $step, $total_count);
                        $selected = ($i === 0) ? 'selected' : '';
                        echo "<option value='{$start}-{$end}' {$selected}>Export {$start} to {$end}</option>";
                    }
                    ?>
                </select>
                <div class="modal__footer" style="margin-top: 10px;">
                    <button type="submit" class="btn btn--m btn--primary btn--block user--profile" style="margin: 0px 0px 5px;">Export</button>
                    <button type="button" class="btn btn--m btn--primary btn--block user--profile">Cancel</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <div class="modal__overlay modal--toggle"></div>
</div>
<!-- /Upload Catalog Modal -->
