<div class="col col--12-of-12 mfc_data">
    <div>
        <ul class="alphabet col col--12-of-12">
            <?php foreach($letters as $k => $letter){
                if(is_numeric($letter)){
                    if(empty($numberFound)){
                        $letter = '0-9';
                        $numberFound = true;
                    } else {
                        continue;
                    }
                }
                echo '<li class="link">' . $letter . '</li>';
            } ?>
        </ul>
        <hr>
    </div>
    <div class=" row mfc_data_holder">
    </div>
</div>



