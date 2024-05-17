<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
/**
 * Description of SearchRepository
 *
 * @author b.pelko
 */
class SearchRepository {

    /**
     * @var DbConnectionProvider
     */
    private $dbConn;
    private $logger;
    private $searchString;

    function __construct(DbConnectionProvider $dbConn) {
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }
    
    public function executeSearch($searchString) {
        $ret = array();
        $this->searchString = $searchString;
        $conditionals = $this->buildSearchConditional();
        $codeSearch = $this->buildProductCodeSearchConditional();
        $results = $this->getSearchResults($conditionals, $codeSearch);
        //$ret = $this->buildResultsHightlist($results, $searchString);
        $ret = $results;
        return $ret;
    }
    
    private function getSearchResults($conditionals, $codeSearch) {
        try {
                                   
            $conditionalString = implode(" OR ", $conditionals);
            $codeSearchString = implode(" OR ", $codeSearch);
            //echo $conditionalString;
            
            $sql = "select p.code, p.code as code_search, p.name, p.slug, p.labeling_purpose, p.additional_requirements, p.placement_conditions,
                    catpar.name as catpar_name, catpar.slug as catpar_slug, subcat.name as subcat_name, subcat.slug as subcat_slug,
                    ($codeSearchString) as pos, pimg.width  
                    from tsa_product p
                    left join tsa_productgroup subcat on subcat.Id = p.product_groupId
                    left join tsa_productgroup catpar on catpar.Id = subcat.parentId
                    left join tsa_productimage pimg on p.id = pimg.product_Id
                    where 1=1 and ($conditionalString) and (pimg.picture is null or pimg.width=144) order by pos desc, p.code";
            //echo $sql;
            
            $products = $this->dbConn->fetch_custom($sql, array(), \PDO::FETCH_ASSOC);
            
            return (count($products) > 0 && (!empty($this->searchString))) ? $products : array();
        }
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->addError($ex->getMessage()."\n".$ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }
    
    private function buildResultsHightlist($results, $searchString) {
        //$srchWords = explode(' ', trim($searchString));
        $srchWords = "110";
                
        $ret = array();
        $i = 0;
        
        foreach($results as $result) {
            $ret[$i] = $result;
            $ret[$i]["code_search"] = $this->hightlighttext($result["code_search"], $srchWords);
            $ret[$i]["name"] = $this->hightlighttext($result["name"], $srchWords);
            $ret[$i]["labeling_purpose"] = $this->hightlighttext($result["labeling_purpose"], $srchWords);
            $ret[$i]["additional_requirements"] = $this->hightlighttext($result["additional_requirements"], $srchWords);
            $ret[$i]["placement_conditions"] = $this->hightlighttext($result["placement_conditions"], $srchWords);
            $i++;
        }
        
        return $ret;
    }

    function hightlighttext($body_text,$searh_letter){     //function for highlight the word in body of your page or paragraph or string
        $startHightlight = "<span class='hightlight'>";
        $endHightlight = "</span>";
        $prevPos = 0;
        $retval = $body_text;
        // find the first occurence of oldValue
        $pos = stripos($retval, $searh_letter);

        while ($pos !== false)
        {
            // save old value from the string
            $findOld = substr($retval, $pos, strlen($searh_letter));

            $retval = substr_replace($retval, "", $pos, strlen($searh_letter));
                    //retval.Remove(pos, srchString.Length);

            $newValue = $startHightlight.$findOld.$endHightlight;

            // insert newValue in it's place
            $retval = substr_replace($retval, $newValue, $pos);

            // check if oldValue is found further down
            $prevPos = $pos. strlen($newValue);
            $pos = stripos($retval, $searh_letter, $prevPos); //retval.IndexOf(srchString, prevPos, StringComparison.InvariantCultureIgnoreCase);
        }

        return retval;
    }
    
    public function getSearchString() {
        return $this->searchString;
    }
        
    private function buildSearchConditional() {
        $srchWords = explode(' ', trim($this->searchString));
        
        $cond = array();
        foreach($srchWords as $word) {
            $cond[] = "(product_search like '%{$word}%')";            
        }
        
        return $cond;        
    }

    private function buildProductCodeSearchConditional() {
        $srchWords = explode(' ', trim($this->searchString));
        
        $cond = array();
        foreach($srchWords as $word) {
            $cond[] = "(IF(INSTR(p.code, '{$word}') > 0, 1,0))";
            //$cond[] = "(product_search like '%{$word}%')";            
        }
        
        return $cond;
    }
}
