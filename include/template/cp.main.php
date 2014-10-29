<div class="row-fluid">


    <div id="picto_grid" class="span9">

        <?= $this->get('pictoGrid'); ?>

    </div>



    <div class="span3">

        <div class="well">
            <?= $this->get('lastActions'); ?>
        </div>
        <?php
        if ($GLOBALS['gs_obj']->can('edit', 'any_table')) {
            ?>
            <div class="well">
                <?= $this->get('globalActions'); ?>
            </div>
            <?php
        }
        ?>
    </div>

</div>