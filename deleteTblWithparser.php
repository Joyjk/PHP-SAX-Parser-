<?php

echo "<title>Simple API XML Delete Backup</title>";
   
   $customersData   = array();
   $elements   = null;
   
  
   function startElements($parser, $name, $attrs) {
      global $customersData, $elements;
      
      if(!empty($name)) {
         if ($name == 'CUSTOMER') {
           
            $customersData []= array();
         }
         $elements = $name;
      }
   }
   
   
   function endElements($parser, $name) {
      global $elements;
      
      if(!empty($name)) {
         $elements = null;
      }
   }
   

   function characterData($parser, $data) {
      global $customersData, $elements;
      
      if(!empty($data)) {
         if ($elements == 'CUSTOMER_NAME' || $elements == 'CUSTOMER_COUNTRY') {
            $customersData[count($customersData)-1][$elements] = trim($data);
         }
      }
   }
   
   
   $parser = xml_parser_create(); 
   
   xml_set_element_handler($parser, "startElements", "endElements");
   xml_set_character_data_handler($parser, "characterData");
   
   
   if (!($handle = fopen('http://localhost:8012/xmltry/deleteTblXML.php', "r"))) {
      die("could not open XML input");
   }
   //http://localhost/trydropdown/retrivedata.asp
   while($data = fread($handle, 4096)) {
      xml_parse($parser, $data); 
   }
   
   xml_parser_free($parser); 
  
   echo "<h3>After Delete Backup</h3>";  
   echo "<table border = '1'>";
   echo "<tr>";
   
   echo "<th>Customer Name</th>";
   echo "<th>Customer Country</th>";
   
   echo "</tr>";
   
   foreach($customersData as $customer) {
       echo "<tr>";
       
       echo "<td>".$customer['CUSTOMER_NAME']."</td>";
       echo "<td>".$customer['CUSTOMER_COUNTRY']."</td>";
       echo "</tr>";
  
   }
   echo "</table>"
?>