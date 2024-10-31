<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="s2baia_container">
    <div id="s2baia_metatabs">


            <ul>
                <li><a href="#s2baia-tabs-1"><?php echo esc_html__('Image Generation', 's2b-ai-aiassistant') ?></a></li>

               
            </ul>
            <?php
            include S2BAIA_PATH . '/views/backend/image/generation.php';
            
            ?>

    </div>
</div>

<script>

    jQuery(function () {
        jQuery("#s2baia_metatabs").tabs();
    });
</script>
<script>

    jQuery(document).ready(function () {
        
        
            
    }
    
    );
    
  
</script>

<style>
    .s2baia_mt10{
        margin-top:10px;
    }
    #s2baia_surpriseme, #s2baia_submit, #s2baia_set_default_setting{
        margin-top: 10px;
    }
    
.s2baia-overlay {
  position: fixed;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: rgb(0 0 0 / 20%);
  top: 0;
  direction: ltr;
  left:0;
  }



.s2baia_modal {
  width: 900px;
  min-height: 100px;
  position: absolute;
  top: 30%;
  background: #fff;
  left: calc(50% - 450px);
  border-radius: 5px;
}

.s2baia_modal {
  top: 5%;
  width: 90%;
  max-width: 900px;
  left: 50%;
  transform: translateX(-50%);
  height: 90%;
  overflow-y: auto;
}


.s2baia_modal_head {
  min-height: 30px;
  border-bottom: 1px solid #ccc;
  display: flex;
  align-items: center;
    padding: 6px 12px;
}

.s2baia_modal_content {
  height: calc(100% - 50px);
  overflow-y: auto;
}

.s2baia_modal_content {
  padding: 10px;
}

.s2baia_grid_form {
  grid-template-columns: repeat(3,1fr);
  grid-column-gap: 20px;
  grid-row-gap: 20px;
  display: grid;
  grid-template-rows: auto auto;
  margin-top: 20px;
}

.s2baia_grid_form_2 {
  grid-column: span 2/span 1;
}

.s2baia_grid_form_1 {
  grid-column: span 1/span 1;
}


.s2baia_modal_close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 30px;
  font-weight: bold;
  cursor: pointer;
}



</style>