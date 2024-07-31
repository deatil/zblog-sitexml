<?php

#注册插件
RegisterPlugin("webxml","ActivePlugin_webxml");

function ActivePlugin_webxml() {
    Add_Filter_Plugin('Filter_Plugin_Admin_Hint', 'webxml_Admin_Hint');
}

function InstallPlugin_webxml() {
    global $zbp;
    
    if (!$zbp->HasConfig('webxml')) {
        $zbp->Config('webxml')->count = 10;
        $zbp->Config('webxml')->cates = "1";
        $zbp->Config('webxml')->hasCate = 1;
        $zbp->Config('webxml')->changefreq = "weekly";
        $zbp->SaveConfig('webxml');
    }
    
    $zbp->SetHint("tips", "请注意备份数据库！");
}

function UninstallPlugin_webxml() {
    global $zbp;
    $zbp->DelConfig('webxml');
}

function webxml_Admin_Hint() {

}

function webxml_GetArticleCategorys_new($Rows, $CategoryID, $hassubcate) {
    global $zbp;
    $ids = strpos($CategoryID, ',') !== false ? explode(',', $CategoryID) : array($CategoryID);
    $wherearray=array(); 
    foreach ($ids as $cateid) {
      if (!$hassubcate) {
        $wherearray[] = array('log_CateID', $cateid); 
      } else {
        $wherearray[] = array('log_CateID', $cateid);
        foreach ($zbp->categorys[$cateid]->SubCategorys as $subcate) {
          $wherearray[] = array('log_CateID', $subcate->ID);
        }
      }
    }
    
    $where=array( 
      array('array', $wherearray), 
      array('=', 'log_Status', '0'), 
    ); 
    
    $order = array('log_PostTime'=>'DESC'); 
    $articles = $zbp->GetArticleList(array('*'), $where, $order, array($Rows),'');     
    return $articles;
}

function webxml_GetArticleCategorys_hot($Rows, $CategoryID, $hassubcate) {
    global $zbp;
    
    $ids = strpos($CategoryID,',') !== false ? explode(',',$CategoryID) : array($CategoryID);
    $wherearray = array(); 
    foreach ($ids as $cateid) {
      if (!$hassubcate) {
        $wherearray[] = array('log_CateID',$cateid); 
      }else{
        $wherearray[] = array('log_CateID', $cateid);
        foreach ($zbp->categorys[$cateid]->SubCategorys as $subcate) {
          $wherearray[] = array('log_CateID', $subcate->ID);
        }
      }
    }
    
    $where = array( 
      array('array', $wherearray), 
      array('=', 'log_Status', '0'), 
    ); 
    
    $order = array('log_ViewNums' => 'DESC'); 
    $articles = $zbp->GetArticleList(array('*'), $where, $order, array($Rows), '');
    return $articles;
}

function webxml_MakeSitemap($file = '') {
    global $zbp;
    
    if (! file_exists($file)) {
        file_put_contents($file, '');
    }

    $count = $zbp->Config("webxml")->count;
    $categoryID = $zbp->Config("webxml")->cates;
    $hasCate = $zbp->Config("webxml")->hasCate;
    
    $data = webxml_GetArticleCategorys_new($count, $categoryID, $hasCate == 1 ? true : false);

    /**
     foreach ( $data as $article ) {
         $str .= '
         <li>
             <h3><a href="'.$article->Url.'">'.$article->Title.'</a></h3>
             <span><a href="'.$article->Author->Url.'">'.$article->Author->StaticName.'</a></span><i>'.$article->Time('Y-m-d').'</i>
         </li>
         ';
     }
    */
    
    $changefreq = $zbp->Config('webxml')->changefreq;

    require 'lib/Sitemap.php';
    
    $map = new Sitemap();
    foreach ($data as $item) {
        $map->addItem($item->Url, $item->Time('Y-m-d H:i:s'), $changefreq, 1);
    }
    
    $map->saveToFile($file);
}
