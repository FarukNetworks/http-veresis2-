<?php

namespace App\ViewExtensions;

/**
 * Description of Twig_renderImage
 *
 * @author b.pelko
 */
class Twig_RenderImage extends \Twig_Extension {
    
    public function getName() {
        return 'Twig_RenderImage';        
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('renderImage', array($this, 'renderImage'))
        );
    }
    
    public function renderImage($base64encodedImage) {
        header("Content-type: image/png");
        echo base64_decoded($base64encodedImage);
    }    
}
