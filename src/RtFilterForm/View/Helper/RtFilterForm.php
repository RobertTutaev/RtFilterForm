<?php
namespace RtFilterForm\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RtFilterForm extends AbstractHelper
{    
    private $vhm;

    public function __construct($sm) {
        $this->vhm = $sm->get('viewhelpermanager');
    }

    public function __invoke($form, $redirect=Null) {
        $form ->prepare();        
        $h_form         =$this->vhm->get('form');
        $h_form_label   =$this->vhm->get('formlabel');
        $h_form_elem    =$this->vhm->get('formelement');
        $h_form_elem_err=$this->vhm->get('formElementerrors');
        $h_translate    =$this->vhm->get('translate');        
        
        echo $h_form->openTag($form).
            '<dl class="zend_form">
                <font color="green">
                    <table class="MWPFILTER">  
                        <tr>';
        
        foreach ($form as $element)
            echo '<td>'.($element->getLabel()!= null?$h_translate($h_form_label($element)).'<br>':'').
                $h_form_elem($element).$h_translate($h_form_elem_err($element)).
                ($redirect?'<input type="hidden" name="redirect" value="'.$redirect.'" />':'').
                '</td>';
        
        echo '          <td align="right">
                            <input class="btn btn-danger"  type="button" value="'.$h_translate('Clear').
                                '" onclick=\''.
                                    'document.getElementById("f_value").value="";'.
                                    'document.getElementById("f_exact").checked=false;'.
                                    'document.getElementById("f_form").submit();'.
                                '\' />
                            <input class="btn btn-success" type="submit" value="'.$h_translate('Apply').'" />
                        </td>
                    <tr>     
                </table>
                </font>
            </dl>';
        
        echo $h_form->closeTag();
    }
}