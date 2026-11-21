<?php
namespace app\pbcode\controller;

use core\basic\Controller;
use core\basic\Config;
use core\basic\Model;

class IndexController extends Controller
{
    public function index()
    {
    	$dsql=new Model;
        global $sitetitle,$indextitle,$sitekeywords,$sitedescription,$siteicp,$sitestatistical,$paydx,$pay_ad;
        $x=$dsql->one("select title,subtitle,keywords,description,icp,statistical from ay_site where id=1 limit 1",1,usedbname());
        $sitetitle=$x[title];
        $indextitle=$x[subtitle];
        $sitekeywords=$x[keywords];
        $sitedescription=$x[description];
        $siteicp=$x[icp];
        $sitestatistical=$x[statistical];
        $siteicp=decode_string($siteicp);
        $sitestatistical=decode_string($sitestatistical);
        $paydx='3e1d2bd1cb8913b460da791d9adbe035';
    	require_once(PB_FILE);
    }
}