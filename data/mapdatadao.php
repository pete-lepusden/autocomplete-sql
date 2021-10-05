<?php
//include_once 'Dao.php';

class MapData
{
  /* Get uuid */
  public function getuuid()
  {
      try {
          $dao = new Dao();
          $conn = $dao->openConnection();
          $sql = "SELECT uuid() as siteid";
          $resource = $conn->query($sql);
          $row = $resource->fetch();
          $dao->closeConnection();
      } catch (PDOException $e) {
          echo "There is some problem in connection: " . $e->getMessage();
      }
      if (! empty($row)) {
          return $row['siteid'];
      }
  }

    // getmap
    public function getmap($mywhere=NULL) {
        try {
            $dao = new Dao();
            $conn = $dao->openConnection();
            $sql = "SELECT frm_group
                  ,mapguid
                  ,applicationname
                  ,html_page
                  ,frm_column
                  ,frm_element
                  ,label
                  ,max_width
                  ,style
                  ,type
                  ,attribute
                  ,placeholder
                  ,pattern
                  ,information
                  ,event
                  ,query
                  ,group_order
                FROM core.formmap";
                if (isset($mywhere) and strlen($mywhere) > 0) { $sql .= " where {$mywhere}"; }
                $sql .= " order by frm_group, group_order";
            $resource = $conn->prepare($sql);
            $resource->execute();
            $dao->closeConnection();
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
        if (! empty($resource)) {
            return $resource;
        }
    }    /* Fetch All */

    // getmap
    public function getmapr($mywhere=NULL) {
        try {
            $dao = new Dao();
            $conn = $dao->openConnection();
            $sql = "SELECT frm_group
                  ,mapguid
                  ,applicationname
                  ,html_page
                  ,frm_column
                  ,frm_element
                  ,label
                  ,max_width
                  ,style
                  ,type
                  ,attribute
                  ,placeholder
                  ,pattern
                  ,information
                  ,event
                  ,query
                  ,frm_group
                  ,group_order
                FROM core.formmap";
                if (isset($mywhere) and strlen($mywhere) > 0) { $sql .= " where {$mywhere}"; }
                $sql .= " order by frm_group, group_order";
            $resource = $conn->prepare($sql);
            $resource->execute();
            $dao->closeConnection();
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
        if (! empty($resource)) {
            return $resource;
        }
    }    /* Fetch All */

    // getmaptable
    public function getmaptable($mywhere=NULL) {
        try {
            $dao = new Dao();
            $conn = $dao->openConnection();
            $sql = "SELECT mapguid
                  ,applicationname
                  ,html_page
                  ,frm_column
                  ,frm_element
                  ,label
                  ,attribute
                  ,event
                  ,frm_group
                  ,group_order
                FROM core.formmap";
                if (isset($mywhere) and strlen($mywhere) > 0) { $sql .= " where {$mywhere}"; }
            $resource = $conn->query($sql);
            $dao->closeConnection();
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
        if (! empty($resource)) {
            return $resource;
        }
    }

    // getexport
    public function getexport($sql=NULL) {
        try {
            $dao = new Dao();
            $conn = $dao->openConnection();
            $resource = $conn->query($sql);
            $dao->closeConnection();
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
        if (! empty($resource)) {
            return $resource;
        }
    }

    // write map
    public function wrmap($formArray) {
        try {
            $dao = new Dao();
            $conn = $dao->openConnection();
            $sql = "SELECT mapguid FROM core.formmap where mapguid = '{$_POST['mapguid_1_0']}'";
            $stmt = $conn->query($sql);
            $cnt = $stmt->rowCount();

            if ($cnt == 0){
              $sql = "INSERT INTO core.formmap
                         (mapguid
                         ,applicationname
                         ,html_page
                         ,frm_column
                         ,frm_element
                         ,Label
                         ,max_width
                         ,Style
                         ,Type
                         ,Attribute
                         ,Placeholder
                         ,Pattern
                         ,Information
                         ,Event
                         ,Query
                         ,frm_Group
                         ,group_Order)
                           VALUES
                           (:mapguid
                           ,:applicationname
                           ,:html_page
                           ,:frm_column
                           ,:frm_element
                           ,:label
                           ,:max_width
                           ,:style
                           ,:type
                           ,:attribute
                           ,:placeholder
                           ,:pattern
                           ,:information
                           ,:event
                           ,:query
                           ,:frm_group
                           ,:group_order)
                        ";
              $stmt = $conn->prepare($sql);
            } else {
              $sql = "UPDATE core.formmap
                             set applicationname = :applicationname
                             ,html_page = :html_page
                             ,frm_column = :frm_column
                             ,frm_element = :frm_element
                             ,label = :label
                             ,max_width = :max_width
                             ,style = :style
                             ,type = :type
                             ,attribute = :attribute
                             ,placeholder = :placeholder
                             ,pattern = :pattern
                             ,information = :information
                             ,event = :event
                             ,query = :query
                             ,frm_group = :frm_group
                             ,group_order = :group_order
                          WHERE mapguid = :mapguid
";
              $stmt = $conn->prepare($sql);
            }
            $stmt->execute(array(':mapguid' => $_POST['mapguid_1_0']
            ,':applicationname' => $_POST['applicationname_1_0']
            ,':html_page' => $_POST['html_page_1_0']
            ,':frm_column' => $_POST['frm_column_1_0']
            ,':frm_element' => $_POST['frm_element_1_0']
            ,':label' => $_POST['label_1_0']
            ,':max_width' => $_POST['max_width_1_0']
            ,':style' => $_POST['style_1_0']
            ,':type' => $_POST['type_1_0']
            ,':attribute' => $_POST['attribute_1_0']
            ,':placeholder' => $_POST['placeholder_1_0']
            ,':pattern' => $_POST['pattern_1_0']
            ,':information' => $_POST['information_1_0']
            ,':event' => $_POST['event_1_0']
            ,':query' => $_POST['query_1_0']
            ,':frm_group' => $_POST['frm_group_1_0']
            ,':group_order' => $_POST['group_order_1_0']));
            $dao->closeConnection();
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
        return "Record Saved";
    }
    
    public function delMap() {
      if (isset($_GET["mapguid"]) and isValidUuid($_GET["mapguid"])) {
          try {
              $dao = new Dao();
              $db = $dao->openConnection();
              $sql = "DELETE FROM core.formmap WHERE mapguid = '".$_GET["mapguid"]."'";
              $db->query($sql);
              $dao->closeConnection();
          }
          catch (PDOException $e) { echo "There is some problem in delMap connection: " . $e->getMessage();  }
          if (! empty($result)) { return "Record Deleted"; }
      }
      else { return "Invalid Guid"; }
  }
  // getlist
  public function getlist($sql=NULL) {
    try {
      $dao = new Dao();
      $conn = $dao->openConnection();
      $resource = $conn->prepare($sql);
      $resource->execute();
      $dao->closeConnection();
    } catch (PDOException $e) { echo "There is some problem in getlist connection: " . $e->getMessage(); }
    if (! empty($resource)) { return $resource; }
  }

}

?>
