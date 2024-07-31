<?php

if (!defined('ZBP_PATH')) {
  exit('Access denied');
}

$act = GetVars('act', 'GET');

if ($act == 'save') {
    $mapFile = $blogpath . 'sitemap.xml';
    webxml_MakeSitemap($mapFile);

    $zbp->SetHint('good');
    Redirect('./main.php' . "?tab=makexml");
}
?>

<form action="<?php echo BuildSafeURL("main.php?tab=makexml&act=save"); ?>" method="post">
  <table width="100%" class="tableBorder">
    <tr>
      <th width="10%">项目</th>
      <th>内容</th>
    </tr>
    <tr>
      <td>生成说明</td>
      <td>
        点击提交后生成网站地图文件
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <input type="submit" value="提交" />
      </td>
    </tr>
  </table>
</form>

<script>
  $(function() {
    $(".webxml-tab .m-left.webxml-makexml").addClass("m-now");
  });
</script>
