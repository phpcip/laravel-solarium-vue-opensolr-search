<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Solarium\Client;

class SolrController extends Controller
{
    // Solr API Controller
    protected $solr;
    protected $hl_fl = "description_text,text_text,paragraphs,title_text,uri_text";
    protected $hl_size = 120;
    protected $facet_fl = "category";
    protected $query_fields = "title_text^149 title_tags^120 tags^6 title^150 headings1^5 description_text^70 text_text^39 uri_text^75 filename_text^2 paragraphs^15";



    public function __construct(){
        $this->solr = new Client();
    }

    public function search(){
        $query = request("query") ?? "*:*";

        $this->solr->setEndpoints(config("solarium")["endpoint"]);
        $end = $this->solr->getEndpoint("opensolr");
        $end->setTimeout(10000);
        $this->solr->setDefaultEndpoint($end);
        $select = $this->solr->createSelect();

        // Add Search Params
        $select->addParam("defType", "edismax");
        $select->addParam("qf", $this->query_fields);
        $select->addParam("q", str_replace(" ", "%20", $query));
        $select->addParam("rows", 20);

        // Enable solr highlighting
        $hl = $select->getHighlighting();
        $hl->setFields($this->hl_fl);
        $hl->setSimplePrefix("<opensolr class='bold-blue'>");
        $hl->setSimplePostfix('</opensolr>');
        $hl->setFragSize(350);
        $hl->setSnippets(1);
        $hl->setMergeContiguous(FALSE);

        // Create a facet
        $select->addParam("facet.sort", "index");
        $select->addParam("facet.limit", "-1");
        $select->addParam("facet.mincount", "1");
        $facetSet = $select->getFacetSet();
        $facetSet->createFacetField($this->facet_fl)->setField($this->facet_fl);
        $resultset = $this->solr->select($select);
        $facet = $resultset->getFacetSet()->getFacet($this->facet_fl);

        $out = [];
        $facets = [];
        $results = [];
        $highlights = $resultset->getHighlighting();
        foreach($facet as $item => $count){
            $facets[$item] = $count;
        }

        foreach($resultset->getDocuments() as $res){
            $results[] = $res->getFields();
        }

        // Build highlights
        $hl_fields = explode(",", $this->hl_fl);
        foreach($results as $k=>$res){
            foreach($hl_fields as $field) {
                if(isset($highlights->getResult($res["id"])->getFields()[$field])) {
                    if (is_array($highlights->getResult($res["id"])->getFields()[$field]) && count($highlights->getResult($res["id"])->getFields()[$field]) > 0) {
                        $results[$k]["highlight_" . $field] = $highlights->getResult($res["id"])->getFields()[$field][0];
                    }
                }
            }

        }

        $out["results"] = $results;
        $out["facets"] = $facets;

        return response($out, 200, ["Content-Type:application/json"]);
    }

}
