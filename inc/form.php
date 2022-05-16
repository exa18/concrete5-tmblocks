<?php defined("C5_EXECUTE") or die("Access Denied.");
/*
    MAKE
*/
$html = [];

if (empty($tmTabs)){

    foreach ($tmFields as $field => $ft){
        $ft = $tmFields[$field];
        if ($ft instanceof \Concrete\Package\Tmblocks\Src\FieldTypes\BlockFieldTypeRepeatable){
            $countfield = ${$field};
            if ( is_countable($countfield) ) {
                $countfield = count($countfield);
            }else{
                $countfield = strlen($countfield);
            }
            $html[] = '<div id="ccm-repeatable-'.$field.'">
                <button type="button" class="btn btn-success ccm-add-'.$field.'-entry">'.t($ft->getAddButtonName()).'</button>
                <div class="ccm-'.$field.'-entries" data-init-max-sort="'.$countfield.'">';
                $i = 0;
                if (isset(${$field})){
                    foreach (${$field} as $childFieldValue){
                        $i++;
                        $html[] = '<div class="repeatable-block">
                            <button type="button" class="btn btn-danger repeatable-remove" id="ccm-remove-'.$field.'-'.$i.'">'.t('Remove').'</button>
                            <i class="fa fa-arrows repeatable-drag"></i>';
                            foreach ($ft->getChildTypes() as $childField => $childFt){
                                $html[] = $childFt->getFormMarkupForRepeatable($form, $view, $childField, $childFieldValue[$childField], $field, $i);
                            }
                        $html[] = '</div>';
                    }
                }
                $html[] = '</div></div>';
            
            $html[] = '<template type="text/template" id="template-'.$field.'">
                <div class="repeatable-block">
                <button type="button" class="btn btn-danger repeatable-remove" id="ccm-remove-'.$field.'-<%= i %>">'.t('Remove').'</button>
                <i class="fa fa-arrows repeatable-drag"></i>';
                foreach ($ft->getChildTypes() as $childField => $childFt){
                    $html[] = $childFt->getFormMarkupForTemplate($form, $view, $childField, null);
                }
            $html[] = '</div></template>';
            $html[] = '<script type="text/javascript"> $("#ccm-repeatable-'.$field.'").tm_repeatable({ '."'field'".': "'.$field.'" }); </script>';
        }else{
            $html[] = '<div class="form-group">'. $ft->getFormMarkup($form, $view, $field, ${$field}).'</div>';
        }
    }

}else{

        $tabs = [];
        $init = true;   # checks first tab active
    foreach ($tmTabs as $tabid => $tab){
        $tabs[] = [ $tabid, $tab["title"], $init ];
        $init = false;
    }

    $html[] = Core::make('helper/concrete/ui')->tabs($tabs);

    foreach ($tmTabs as $tabid => $tab){
        $html[] = '<div id="ccm-tab-content-'.$tabid.'" class="ccm-tab-content">';
        foreach ($tab['fields'] as $field){
            $ft = $tmFields[$field];
            if ($ft instanceof \Concrete\Package\Tmblocks\Src\FieldTypes\BlockFieldTypeRepeatable){
                $countfield = ${$field};
                if ( is_countable($countfield) ) {
                    $countfield = count($countfield);
                }else{
                    $countfield = strlen($countfield);
                }
                $html[] = '<div id="ccm-repeatable-'.$field.'">
                    <button type="button" class="btn btn-success ccm-add-'.$field.'-entry">'.t($ft->getAddButtonName()).'</button>
                    <div class="ccm-'.$field.'-entries" data-init-max-sort="'.$countfield.'">';
                    $i = 0;
                    if (isset(${$field})){
                        foreach (${$field} as $childFieldValue){
                            $i++;
                            $html[] = '<div class="repeatable-block">
                                <button type="button" class="btn btn-danger repeatable-remove" id="ccm-remove-'.$field.'-'.$i.'">'.t('Remove').'</button>
                                <i class="fa fa-arrows repeatable-drag"></i>';
                                foreach ($ft->getChildTypes() as $childField => $childFt){
                                    $html[] = $childFt->getFormMarkupForRepeatable($form, $view, $childField, $childFieldValue[$childField], $field, $i);
                                }
                            $html[] = '</div>';
                        }
                    }
                    $html[] = '</div></div>';
                
                $html[] = '<template type="text/template" id="template-'.$field.'">
                    <div class="repeatable-block">
                    <button type="button" class="btn btn-danger repeatable-remove" id="ccm-remove-'.$field.'-<%= i %>">'.t('Remove').'</button>
                    <i class="fa fa-arrows repeatable-drag"></i>';
                    foreach ($ft->getChildTypes() as $childField => $childFt){
                        $html[] = $childFt->getFormMarkupForTemplate($form, $view, $childField, null);
                    }
                $html[] = '</div></template>';
                $html[] = '<script type="text/javascript"> $("#ccm-repeatable-'.$field.'").tm_repeatable({ '."'field'".': "'.$field.'" }); </script>';
            }else{
                $html[] = '<div class="form-group">'. $ft->getFormMarkup($form, $view, $field, ${$field}).'</div>';
            }
        }
        $html[] = '</div>';
    }

}
$html[] = '<div id="tm-form-dialog"></div>';

# print all (remove whitespaces between ><)
echo trim( preg_replace('/\>\s+\</m', '><', implode('',$html) ) );

?>
