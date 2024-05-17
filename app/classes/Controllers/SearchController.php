<?php
namespace App\Controllers;

use APP\Databases\DbConnectionProvider;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use APP\ModelsSupport\ProductRepository;
use APP\ModelsSupport\SearchRepository;

/**
 * Description of SearchController
 *
 * @author b.pelko
 */
class SearchController {

    private $templatePrefix = "search/search.";
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var DbConnectionProvider
     */
    private $dbConnProv;
    
    /**
     * @var ProductRepository 
     */
    private $productRepository;
    
    /**
     * @var SearchRepository 
     */
    private $searchRepository;

    function __construct(DbConnectionProvider $dbConnProv, Twig $view) {
        
        $this->dbConnProv = $dbConnProv;
        $this->view = $view;
        $this->productRespository = new ProductRepository($dbConnProv);
        $this->searchRepository = new SearchRepository($dbConnProv);
    }
    
    public function searchView(Request $request, Response $response) {
        
        $searchString = $request->getParam("srchString");
                        
        $renderData = [
            'seacrhString' => $searchString,
            'searchResults' => $this->searchRepository->executeSearch($searchString)
        ];
                
        return $this->view->render($response, $this->templatePrefix."index.twig", $renderData);
    }
    
    /**
     * Autocomplete fucntion
     */
    public function getProductList() {
        $this->productRepository->getProductList();
    }       
    
}