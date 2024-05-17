<?php
/**
 * JSON parser is extension for Twig templates
 * Used for parse language file and json files
 *
 * @author b.pelko
 */
namespace App\ViewExtensions;

class Twig_JSON extends \Twig_Extension  {
 
    /**
     * Get extension name
     * @return string
     */
    public function getName() {
        return 'Twig_JSON';
    }
    /**
     * Get and  set extension fucntion
     * @return type
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('jsonparser', array($this, 'json')),
            new \Twig_SimpleFunction('jsonparsefromstring', array($this, 'parseFromJsonString'))
        );
    }
    /**
     * Call extension function.
     * Declaration is in method getFunctions
     * @param type $url
     * @return type
     */ 
    public function json($url) {
        return json_decode(file_get_contents($url), true);
    }
    
    public function parseFromJsonString($string) {
        return json_decode($string, true);
    }
}
