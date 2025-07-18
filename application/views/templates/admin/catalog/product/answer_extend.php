<div class="row">
    <div class="col col--1-of-8">
        <span class="fontSize--s fontWeight--2"></span>
    </div>
    <div class="col col--7-of-8">
        <ul class="list list--answers">
            <?php
            if ($answers != null) {
                for ($j = 0; $j < count($answers); $j++) {
                    ?>
                    <li class="answer">
                        <?php print_r($answers[$j]->answer); ?>
                        <span class="voting__meta">
                            <span class="fontWeight--2"></span>
                            <a class="link fontSize--s fontWeight--2 is--neg">Delete</a>
                        </span>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
</div>