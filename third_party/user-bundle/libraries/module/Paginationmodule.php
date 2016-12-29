<?php
/**
 * Created by PhpStorm
 * Date: 19.10.2016
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Paginationmodule
{
    protected $CI;
    private $properties;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->properties = [];
    }
    public function getPagination($pagelength, $pagelimit, $pageactive, $max_page_selector)
    {
        $this->setProperties($pagelength, $pagelimit, $pageactive, $max_page_selector);
        return $this->render();
    }
    private function setProperties($pagelength,$pagelimit,$pageactive,$max_page_selector)
    {
        if($pagelength > $pagelimit && $pagelimit)
        {
            $this->properties = [
                "steps"=>ceil($pagelength/$pagelimit),
                "start"=>0,
                "end"=>0,
                "limit"=>$pagelimit,
                "pageactive"=>$pageactive,
                "max_page_selector"=>$max_page_selector
            ];
            $this->properties["end"] = ($max_page_selector < $this->properties["steps"]) ? $this->properties["start"]+$max_page_selector : $this->properties["steps"];

            if($max_page_selector < $this->properties["steps"])
            {
                $selctsteps =  ceil($this->properties["steps"]/$max_page_selector);
                $selctdiff =  ceil($this->properties["steps"]/$selctsteps);

                if($pageactive >= $selctdiff)
                {
                    $selctmax = ceil(($pageactive)/$selctdiff);
                    $msum = ($selctdiff*($selctmax-1))+$max_page_selector;

                    if($pageactive+1 >= $msum ) {
                        $selctmax++;
                    }

                    $this->properties["start"] = $selctdiff*($selctmax-1);

                    if($this->properties["start"] > $pageactive) {
                        $selctmax--;
                    }

                    $this->properties["start"] = $selctdiff*($selctmax-1);
                    $this->properties["end"] = $this->properties["start"]+$max_page_selector;

                    if($this->properties["end"] >= $this->properties["steps"]) {
                        $this->properties["end"] = $this->properties["steps"];
                    }
                    if($this->properties["start"] > 0 && ($this->properties["end"] - $this->properties["start"]) < $max_page_selector)  {
                        $this->properties["start"] = $this->properties["end"] - $max_page_selector;
                    }
                }
            }
        }
    }
    private function render ()
    {
        $result = '';

        if ( ! empty($this->properties))
        {
            $pageSelector = '';
            $pageSelectorBack = '<span class="ctrl"><&nbsp;</span>';
            $pageSelectorForward = '<span class="ctrl">&nbsp;>&nbsp;</span>';
            $data = $this->properties;

            if ($data["start"] < $data["end"])
            {
                $psstart = $data["start"];
                $psend = $data["end"];
                $pnextdown = ($data["pageactive"]-1 >= 0) ? $data["pageactive"]-1:-1;
                $pnextup = ($data["pageactive"]+1 <= $psend) ? $data["pageactive"]+1:$psend;
                $selectorargs = [];

                if (($data["pageactive"]-5)>=0 && ($data["pageactive"]+4)<=$data["steps"]) {
                    $psstart = $data["pageactive"]-5;
                    $psend = $psstart+10;
                }
                for($i = $psstart; $i<$psend;$i++){
                    if ($i != $data["pageactive"])
                    {
                        $selectorargs[] = '<a class="number" data-page="'.$i.'">'.($i+1).'</a>';
                    }else{
                        $selectorargs[] = '<span class="number">'.($i+1).'</span>';
                    }
                }
                if($pnextdown>=0) {
                    $pageSelectorBack = '<a class="ctrl" data-page="'.$pnextdown.'">&nbsp;<&nbsp;</a>';
                }
                if($pnextup<$psend) {
                    $pageSelectorForward = '<a class="ctrl" data-page="'.$pnextup.'">&nbsp;></a>';
                }
                if(count($selectorargs) > 0) {
                    $pageSelector = implode("&nbsp;l&nbsp;",$selectorargs);
                }
            }
            if ( ! empty($pageSelector)) {
                $result = $pageSelectorBack.$pageSelector.$pageSelectorForward;
            }
        }
        return $result;
    }
}