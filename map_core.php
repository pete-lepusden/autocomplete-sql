<?php
  session_start();
  if (isset($_GET["init"])) {
    unset($_SESSION['appini']);
    $_SESSION['mapcore.where'] =""; }
  $GLOBALS['current_page'] = 'Map Manager';
  $GLOBALS['navico'] = 'fa fa-1x fa-globe';
  $_SESSION['appini'] = parse_ini_file("conf/app.ini",true);
  if(!isset($_SESSION['login_status'])) {  header("location:index.php"); }

  include_once '../lib/data/getuser.php';
  $dbsec = new SecData();
  if (isset($_SESSION['login_status'])) {
    $mywhere =  "secguid = '".$_SESSION['secguid']."'";
    $stmsec = $dbsec->getuser($mywhere);
    $sec = $stmsec->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $mywhere =  "secguid = '00000000-0000-0000-0000-000000000000'";
    $stmsec = $dbsec->getuser($mywhere);
    $sec = $stmsec->fetchAll(PDO::FETCH_ASSOC);
    $sec[0]["access_type"] = 'User';
  }

  function pdo_debugStrParams($stmt) {
   ob_start();
   $stmt->debugDumpParams();
   $r = ob_get_contents();
   ob_end_clean();
   return $r;
  }

  function isValidUuid( $uuid ) {
    if (!is_string($uuid) || (preg_match('~^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i', $uuid) !== 1)) {
      return false;
    }
    return true;
  }

  include_once '../lib/data/mapdatadao.php';
  $dbclass = new MapData();
  include_once '../lib/buildform.php';

  $message_area ="Select an Action Button";
?>
<html lang="en">
  <?php include_once '../lib/headnav.php'; ?>
    <?php
    $grparray = array();
    $grparray[1][0] = "Form and Label Fields";
    $grparray[1][1] = "Form and Label Fields as they appear on the form";
    $grparray[2][0] = "Depricated Form and Label Fields";
    $grparray[2][1] = "Depricated Form and Label Fields";

    // Process Post data
    if (isset($_POST["Sav1"])) {
      $message_area = $dbclass->wrmap($_POST);
      $tblrender = 0;
    };
    // Process Post data delete
    if (isset($_POST["Delete1"])) {
      $message_area = $dbclass->delMap();
      $tblrender = 0;
    };
    if (!isset($_POST['Sav1']) and !isset($_POST['ssubmit']) and !isset($_GET['mapguid']) and strlen($_SESSION['mapcore.where'])==0 and !isset($_POST['newrec']))
    { $page = 'filter';
      ?>
      <form class="flexform" ACTION="map.php" METHOD="POST" ENABLECAB="Yes" name="selectopt" id="selectopt">
        <fieldset class="flex-container" id="filterdiv">
          <div class="base" style="max-width:13rem">
            <label id="pagefilter_Label2" for="pagefilter">Page Filter
              <i class="fa fa-info-circle" style="margin-left:.3rem;" aria-hidden="true" title="Enter pageFiter name. As letters are typed an updated filtered list appears. Select a name from the filtered list."></i>
            </label>
            <input class="base autocomplete" type="text" name="pagefilter" id="pagefilter" value="">
          </div>
          <div class="base" style="max-width:13rem">
            <label id="columnfilter_Label2" for="columnfilter">Column Filter
              <i class="fa fa-info-circle" style="margin-left:.3rem;" aria-hidden="true" title="Enter columnfilter name. As letters are typed an updated filtered list appears. Select a name from the filtered list."></i>
            </label>
            <input class="base autocomplete" type="text" name="columnfilter" id="columnfilter" value="">
          </div>
        </fieldset>
        <section class="flex-container">
          <input name="newrec" id="newrec" type="submit" value="Enter New Record" autofocus>
          <input name="ssubmit" id="allrec" type="submit" value="Get All Existing Records">
          <input name="filterrec" id="fltrec" type="button" value="Filter Existing Records" onClick="showfilter();">
        </section>
      </form>
      <?php
    }
    $tblrender = 0;
    if (strlen($_SESSION['mapcore.where']) > 0) {
      $mywhere = $_SESSION['mapcore.where'];
      $tblrender = 1;
    }
    if (isset($_POST['newrec']) or isset($_GET['mapguid'])) {
      $tblrender = 0;
    }

    //Process Post data - Create filter
    if (isset($_POST['ssubmit'])) {
      $mywhere = "(applicationname like '%". $_SESSION['appini']['application']['sName'] . "%' or applicationname = 'core')";
      if (strlen($_POST['pagefilter']) > 0) {
         if (strlen($mywhere) > 0) {	$mywhere = $mywhere . " and "; }
        $mywhere = $mywhere . "html_page = '" . $_POST['pagefilter'] . "'";
      }
      if (strlen($_POST['columnfilter']) > 0) {
        if (strlen($mywhere) > 0) {	$mywhere = $mywhere . " and "; }
        $mywhere = $mywhere . "frm_column = '" . $_POST['columnfilter'] . "'";
      }
      $_SESSION['mapcore.where'] = $mywhere;
      $tblrender = 1;
    }
    //Write Table
    if ($tblrender == 1) {
      $page = 'table';
      $stmtbg = $dbclass->getmaptable($mywhere);
      $bg = $stmtbg->fetchAll(PDO::FETCH_ASSOC);
      $rowcnt = $stmtbg->rowCount();
      $colcnt = $stmtbg->columnCount();
      if ($rowcnt == 1) { $_GET["mapguid"] = $bg[0]["mapguid"]; $_SESSION['mapcore.where'] ="";
      }
      else {
        $_SESSION['export'] = print_r($stmtbg,true);
        $_SESSION['export_name'] = 'Map Data.xls';
        ?>
        <script language="javascript">
          document.getElementById('msgbar').innerHTML='<?php echo $rowcnt; ?> Records Found. Click Frm Column column link to View/Edit form fields.<i class="fa fa-info-circle fa-lg" aria-hidden="true" title="Click a column header to sort the column."></i>';
          document.getElementById('task').innerHTML="<a href=\"xls.php\">Export Raw</a>";
        </script>
        <div class="loading" id="spin">
          <i class="fa fa-spinner fa-pulse fa-3x" aria-hidden="true"></i>
        </div>
        <div id="scroll" class="tableFixHead">
          <table style="display:none" id="dataTable" width="100%" cellspacing="0">
            <thead><tr>
                <?php
                for ($i = 0, $j=1; $i < $colcnt; $i++,$j++) {
                  $col = $stmtbg->getColumnMeta($i);
                  echo '<th class="col'.$i.' sorting" onclick="onColumnHeaderClicked(event)">'.str_replace("_"," ",$col['name']).'</th>';
                } ?>
            </tr></thead>
            <tbody>
              <?php
              foreach ($bg as $k => $v) {
                echo '<tr id="'.$bg[$k]["mapguid"].'" class="sort">';
                for ($i = 0; $i < $colcnt; $i++) {
                  $col = $stmtbg->getColumnMeta($i);
                  echo '<td id="'.$col['name'].'"class="col'.$i.'">';
                  switch ($col['name']) {
                    case "frm_column":
                      echo '<a href="map.php?mapguid='.$bg[$k]["mapguid"].'">'.$bg[$k][$col['name']].'</a>';
                      break;
                    default: echo $bg[$k][$col['name']];
                  }
                  echo '</td>';
                }
                echo '</tr>';
              }?>
            </tbody>
          </table>
        </div>
        <?php
    } }
    // View/edit single record
    if (isset($_POST["newrec"]) or isset($_GET["mapguid"])) {
      $page = 'edit';
      if (isset($_GET["mapguid"]) and isValidUuid($_GET["mapguid"])) {  $mywhere =  "mapguid = '".$_GET['mapguid']."'";  }
      else { $mywhere =  "mapguid = '00000000-0000-0000-0000-000000000000'"; }
      $stmtecs = $dbclass->getmap($mywhere);
      $ecs = $stmtecs->fetchAll(PDO::FETCH_ASSOC);
      if (sizeof($ecs) == 0) {
        filldba($stmtecs,$ecs);
        $ecs[0]["mapguid"] = $dbclass->getuuid();
        $ecs[0]["applicationname"] = $GLOBALS['applicationname'];
      }
      foreach ($ecs as $ecsk => $ecsv) {
        ?>
        <script language="javascript">
          var hmsg = "<?php echo 'Edit '.$ecs[$ecsk]["frm_column"]; ?>";
          document.getElementById('msgbar').innerHTML=hmsg;  
        </script>
        <form class="flexform" action="map.php?mapguid=<?php echo $ecs[$ecsk]["mapguid"] ?>" method="post" id="ecform" name="ecform" enctype="multipart/form-data" onsubmit="return submitForm();">
          <?php echo formbuild($ecs,$grparray,'mapmanager'); ?>
          <section class="flex-container">
            <input type="submit" name="Sav1" id="Sav1" value="Save Record">
            <input type="submit" name="Delete1" value="Delete Record" onClick="return confirm('Are you sure you want to delete?')">
            <a class="anchor" href="map.php">Return to Filter</a>
          </section>
        </form>
        <?php
      } } ?>
    <?php
      include_once '../lib//includes/navsec.php';
      include '../lib/includes/footer.php';
    ?>
    <!-- Custom scripts for all pages-->
    <script charset="UTF-8" type="application/javascript" src="./scripts/app.js" crossorigin></script>
    <?php
      switch ($page) {
        case "filter":
          echo '
            <script>
              autocomplete({
                inp: document.getElementById("pagefilter"),
                source: "//lepusden.com/lib/data/mapajaxdao.php",
                dataType: "json",
                data: {
                          method: "pagefilter",
                          name: "'.$_SESSION['appini']['application']['sName'].'"
                      },
              },
              );
        
              autocomplete({
                inp: document.getElementById("columnfilter"),
                source: "//lepusden.com/lib/data/mapajaxdao.php",
                dataType: "json",
                data: {
                          method: "columnfilter",
                          name: "'.$_SESSION['appini']['application']['sName'].'"
                      },
              },
              );
            </script>
          ';
        break;
        case "table":
          echo '<script charset="UTF-8" type="application/javascript" src="./scripts/table.js"></script>
                <script>
                  var dtreserve = 115;
                  var tbl = document.getElementById("dataTable");
                  var spin = document.getElementById("spin");
                  console.log(tbl.offsetHeight);
                  if (!!tbl) {  tbl.style.display = "table"; }
                  if (!!spin) {  spin.style.display = "none"; }
                  var myheight = window.innerHeight;
                  myheight = ((myheight - dtreserve)/myheight)*100;
                  myheight = myheight.toFixed(0) + \'vh\';
                  // Column counter starts at 0 Order counter at 1
                  lps.dataTable(
                    {
                      "colhide": [0],
                      "scrollY": myheight,
                      "scrollCollapse": true,
                      "paging": false,
                      "order": [2,"asc"],
                      "pageLength": 100
                    }
                  )
                </script>
          ';
        break;
        case "edit":
          echo '
          
          ';
        break;
      }
    ?>
  </body>
</html>
