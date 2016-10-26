<div class="paragraphe_type_diaporama">
    <?php if($this->titre) { ?>
    <h2>@@titre@@</h2>
    <?php } ?>
    <div class="">
         <div class="owl-carousel owl-theme">
                <?php 
                foreach($this->obj->IMAGES as $img) { 
                    $img = new row('s_paragraphe_image',$img);
                ?>
                
                <div class="item">
                  
                  <div class="">
                      <img src="<?=$img->paragraphe_image_img->getThumbUrl(1920,1080)?>" alt=""/>
                  </div>
                  <h3><?=$img->paragraphe_image_desc?></h3>
                </div>
                
                <?php } ?>
            </div>
              
    </div>
</div>
