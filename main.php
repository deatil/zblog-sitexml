<?php

require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('webxml')) {$zbp->ShowError(48);die();}

if (count($_POST) > 0) {
    CheckIsRefererValid();
}

$blogtitle='网站地图';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

$tab = GetVars('tab', "GET", "setting");
?>

<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle;?></div>
  <div class="SubMenu webxml-tab">
    <a href="main.php" title="设置"><span class="m-left webxml-setting">设置</span></a>
    <a href="main.php?tab=makexml" title="生成地图"><span class="m-left webxml-makexml">生成地图</span></a>
  </div>
  <div id="divMain2">
    <?php if ($tab === "setting") {
      include 'tab/setting.php';
    } else {
      include 'tab/makexml.php';
    } ?>
  </div>
</div>

<script>
  $(function() {
    $("#nav_plugin").addClass("on");
  });
</script>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>