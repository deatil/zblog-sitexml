<?php

if (!defined('ZBP_PATH')) {
  exit('Access denied');
}

$act = GetVars('act', 'GET');
$suc = GetVars('suc', 'GET');

if ($act == 'save') {
  foreach ($_POST as $key => $val) {
    $zbp->Config('webxml')->$key = trim($val);
  }
  $zbp->SaveConfig('webxml');

  $zbp->SetHint('good');
  Redirect('./main.php' . ($suc == null ? '' : "?act={$suc}"));
}

?>
<form action="<?php echo BuildSafeURL("main.php?act=save"); ?>" method="post">
  <table width="100%" class="tableBorder">
    <tr>
      <th width="12%">项目</th>
      <th>内容</th>
      <th width="45%">说明</th>
    </tr>

    <tr>
      <td>文章数量</td>
      <td>
          <?php zbpform::text('count', $zbp->Config("webxml")->count); ?>
      </td>
      <td>生成的地图数据的文章数量</td>
    </tr>

    <tr>
      <td>文章分类</td>
      <td>
          <?php zbpform::text('cates', $zbp->Config("webxml")->cates); ?>
      </td>
      <td>生成的地图数据的分类ID，以半角逗号(,)分割</td>
    </tr>

    <tr>
      <td>包含子分类</td>
      <td><?php zbpform::zbradio("hasCate", $zbp->Config("webxml")->hasCate); ?></td>
      <td>生成的地图数据是否包含子分类数据</td>
    </tr>

    <tr>
      <td>更新频率</td>
      <td><?php zbpform::select('changefreq', array('always'=>'经常', 'hourly'=>'每小时', 'daily'=>'每天', 'weekly'=>'每周', 'monthly'=>'每月'), $zbp->Config("webxml")->changefreq);; ?></td>
      <td>生成的地图数据里的更新频率</td>
    </tr>

    <tr>
      <td></td>
      <td colspan="2"><input type="submit" value="提交" /></td>
    </tr>
  </table>
</form>

<script>
  $(function() {
    $(".webxml-tab .m-left.webxml-setting").addClass("m-now");
  });
</script>
