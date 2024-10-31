
<div class="pmp-container pmp-search-form">

<?php
$form = new PhpFormBuilder();
$form->set_att('action', '');
$form->set_att('add_nonce', 'pet-match-pro-license');
$form->set_att('form_element', true);
$form->set_att('add_submit', true);
//var_dump($options);

/*
 * container should only have max of 3 columns per row
 * divide the FilterOptions
 */



    
$rows = $filterOptions % 3;

if($rows <= 1){
    //one row
    $rowCounter = 1;
}
?>


    <?php 
    foreach ($filterOptions as $item){ ?>
            <?php
            if(in_array($item,array('Location', 'Site', 'Primary Breed', 'Secondary Breed'))){
                $form->add_input($item,array(
                        'type' => 'text',
                        'options' => $filterValueArray[$item],
                        'wrap_class' => array('pmp-search-form-'.$item)
                        )
                        );
            }else{
                $form->add_input($item,array(
                        'type' => 'select',
                        'options' => $filterValueArray[$item],
                        'wrap_class' => array('pmp-search-form-'.$item)
                        )
                        );
            }
            ?>

    <?php
    }
    ?>


<?php $form->build_form(); ?>
</div>  



