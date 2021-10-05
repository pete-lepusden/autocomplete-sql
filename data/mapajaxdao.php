<?php
include_once 'Dao.php';

class mapdataajax
{
  public function pagefilter($name,$search)
  {
      try {
          $dao = new Dao();
          $db = $dao->openConnection();
          $sql = "SELECT distinct html_page FROM core.formmap
      where (applicationname like '%core%' or applicationname like '%".$name."%') and html_page like '".$search."%'
       order by html_page";

          $query = $db->query($sql);
          // Generate skills data array
          $skillData = array();
          while($row = $query->fetch(PDO::FETCH_ASSOC)){
              $data['id'] = $row['html_page'];
              $data['value'] = $row['html_page'];
              array_push($skillData, $data);
          }
          $dao->closeConnection();
          return $skillData;
      } catch (PDOException $e) {
          echo "There is some problem in connection: " . $e->getMessage();
      }
  }
  public function columnfilter($name,$search)
  {
    try {
        $dao = new Dao();
        $db = $dao->openConnection();
        $sql = "SELECT distinct frm_column FROM core.formmap
    where (applicationname like '%core%' or applicationname like '%".$name."%') and frm_column like '".$search."%'
     order by frm_column";

        $query = $db->query($sql);
        // Generate skills data array
        $skillData = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $data['id'] = $row['frm_column'];
            $data['value'] = $row['frm_column'];
            array_push($skillData, $data);
        }
        $dao->closeConnection();
        return $skillData;
    } catch (PDOException $e) {
        echo "There is some problem in connection: " . $e->getMessage();
    }
  }

  public function delete($guid)
  { try {
      $dao = new Dao();
      $db = $dao->openConnection();
      $sql = "DELETE FROM core.formmap WHERE mapGUID = '.$guid.'";
      $db->query($sql);
      $dao->closeConnection();
    } catch (PDOException $e) {
        echo "There is some problem in connection: " . $e->getMessage();
    }
  }
}

$mapdataajax = new mapdataajax();

// Get search term
$searchTerm = $_POST['search'];
$themethod = $_POST['method'];
$thename = $_POST['name'];
switch ($themethod) {
  case 'pagefilter':
    $nf = $mapdataajax->pagefilter($thename,$searchTerm);
    echo json_encode($nf);
    break;
  case 'columnfilter':
    $nf = $mapdataajax->columnfilter($thename,$searchTerm);
    echo json_encode($nf);
    break;
    case 'countryfilter':
      $country = '[{"value":"Afghanistan"},{"value":"Albania"},{"value":"Algeria"},{"value":"Andorra"},{"value":"Angola"},{"value":"Anguilla"},{"value":"Antigua & Barbuda"},{"value":"Argentina"},{"value":"Armenia"},{"value":"Aruba"},{"value":"Australia"},{"value":"Austria"},{"value":"Azerbaijan"},{"value":"Bahamas"},{"value":"Bahrain"},{"value":"Bangladesh"},{"value":"Barbados"},{"value":"Belarus"},{"value":"Belgium"},{"value":"Belize"},{"value":"Benin"},{"value":"Bermuda"},{"value":"Bhutan"},{"value":"Bolivia"},{"value":"Bosnia & Herzegovina"},{"value":"Botswana"},{"value":"Brazil"},{"value":"British Virgin Islands"},{"value":"Brunei"},{"value":"Bulgaria"},{"value":"Burkina Faso"},{"value":"Burundi"},{"value":"Cambodia"},{"value":"Cameroon"},{"value":"Canada"},{"value":"Cape Verde"},{"value":"Cayman Islands"},{"value":"Central Arfrican Republic"},{"value":"Chad"},{"value":"Chile"},{"value":"China"},{"value":"Colombia"},{"value":"Congo"},{"value":"Cook Islands"},{"value":"Costa Rica"},{"value":"Cote D Ivoire"},{"value":"Croatia"},{"value":"Cuba"},{"value":"Curacao"},{"value":"Cyprus"},{"value":"Czech Republic"},{"value":"Denmark"},{"value":"Djibouti"},{"value":"Dominica"},{"value":"Dominican Republic"},{"value":"Ecuador"},{"value":"Egypt"},{"value":"El Salvador"},{"value":"Equatorial Guinea"},{"value":"Eritrea"},{"value":"Estonia"},{"value":"Ethiopia"},{"value":"Falkland Islands"},{"value":"Faroe Islands"},{"value":"Fiji"},{"value":"Finland"},{"value":"France"},{"value":"French Polynesia"},{"value":"French West Indies"},{"value":"Gabon"},{"value":"Gambia"},{"value":"Georgia"},{"value":"Germany"},{"value":"Ghana"},{"value":"Gibraltar"},{"value":"Greece"},{"value":"Greenland"},{"value":"Grenada"},{"value":"Guam"},{"value":"Guatemala"},{"value":"Guernsey"},{"value":"Guinea"},{"value":"Guinea Bissau"},{"value":"Guyana"},{"value":"Haiti"},{"value":"Honduras"},{"value":"Hong Kong"},{"value":"Hungary"},{"value":"Iceland"},{"value":"India"},{"value":"Indonesia"},{"value":"Iran"},{"value":"Iraq"},{"value":"Ireland"},{"value":"Isle of Man"},{"value":"Israel"},{"value":"Italy"},{"value":"Jamaica"},{"value":"Japan"},{"value":"Jersey"},{"value":"Jordan"},{"value":"Kazakhstan"},{"value":"Kenya"},{"value":"Kiribati"},{"value":"Kosovo"},{"value":"Kuwait"},{"value":"Kyrgyzstan"},{"value":"Laos"},{"value":"Latvia"},{"value":"Lebanon"},{"value":"Lesotho"},{"value":"Liberia"},{"value":"Libya"},{"value":"Liechtenstein"},{"value":"Lithuania"},{"value":"Luxembourg"},{"value":"Macau"},{"value":"Macedonia"},{"value":"Madagascar"},{"value":"Malawi"},{"value":"Malaysia"},{"value":"Maldives"},{"value":"Mali"},{"value":"Malta"},{"value":"Marshall Islands"},{"value":"Mauritania"},{"value":"Mauritius"},{"value":"Mexico"},{"value":"Micronesia"},{"value":"Moldova"},{"value":"Monaco"},{"value":"Mongolia"},{"value":"Montenegro"},{"value":"Montserrat"},{"value":"Morocco"},{"value":"Mozambique"},{"value":"Myanmar"},{"value":"Namibia"},{"value":"Nauro"},{"value":"Nepal"},{"value":"Netherlands"},{"value":"Netherlands Antilles"},{"value":"New Caledonia"},{"value":"New Zealand"},{"value":"Nicaragua"},{"value":"Niger"},{"value":"Nigeria"},{"value":"North Korea"},{"value":"Norway"},{"value":"Oman"},{"value":"Pakistan"},{"value":"Palau"},{"value":"Palestine"},{"value":"Panama"},{"value":"Papua New Guinea"},{"value":"Paraguay"},{"value":"Peru"},{"value":"Philippines"},{"value":"Poland"},{"value":"Portugal"},{"value":"Puerto Rico"},{"value":"Qatar"},{"value":"Reunion"},{"value":"Romania"},{"value":"Russia"},{"value":"Rwanda"},{"value":"Saint Pierre & Miquelon"},{"value":"Samoa"},{"value":"San Marino"},{"value":"Sao Tome and Principe"},{"value":"Saudi Arabia"},{"value":"Senegal"},{"value":"Serbia"},{"value":"Seychelles"},{"value":"Sierra Leone"},{"value":"Singapore"},{"value":"Slovakia"},{"value":"Slovenia"},{"value":"Solomon Islands"},{"value":"Somalia"},{"value":"South Africa"},{"value":"South Korea"},{"value":"South Sudan"},{"value":"Spain"},{"value":"Sri Lanka"},{"value":"St Kitts & Nevis"},{"value":"St Lucia"},{"value":"St Vincent"},{"value":"Sudan"},{"value":"Suriname"},{"value":"Swaziland"},{"value":"Sweden"},{"value":"Switzerland"},{"value":"Syria"},{"value":"Taiwan"},{"value":"Tajikistan"},{"value":"Tanzania"},{"value":"Thailand"},{"value":"Timor L\'Este"},{"value":"Togo"},{"value":"Tonga"},{"value":"Trinidad & Tobago"},{"value":"Tunisia"},{"value":"Turkey"},{"value":"Turkmenistan"},{"value":"Turks & Caicos"},{"value":"Tuvalu"},{"value":"Uganda"},{"value":"Ukraine"},{"value":"United Arab Emirates"},{"value":"United Kingdom"},{"value":"United States of America"},{"value":"Uruguay"},{"value":"Uzbekistan"},{"value":"Vanuatu"},{"value":"Vatican City"},{"value":"Venezuela"},{"value":"Vietnam"},{"value":"Virgin Islands (US)"},{"value":"Yemen"},{"value":"Zambia"},{"value":"Zimbabwe"}]';
      $ca = json_decode($country, true);
      $found = array();
      for ($i = 0; $i < count($ca); $i++)  {
        if (strtoupper(substr($ca[$i]['value'],0,strlen($searchTerm))) == strtoupper($searchTerm)) {
          $data['value'] = $ca[$i]['value'];
         array_push($found,$data);
        }
      }
      echo json_encode($found); 
      break;

  case 'delete':
    $stmtbg = $mapdataajax->delete($mywhere);
    echo json_encode('record deleted');
    break;
  default:
    echo json_encode('method not found');
}
?>
