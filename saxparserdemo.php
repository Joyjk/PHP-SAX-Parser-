<html>


<body>
<?php

echo "<title>Simple API XML</title>";
   
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
         if ($elements == 'CUSTOMERID' || $elements == 'NAME' || $elements == 'COUNTRY') {
            $customersData[count($customersData)-1][$elements] = trim($data);
         }
      }
   }
   
   
   $parser = xml_parser_create(); 
   
   xml_set_element_handler($parser, "startElements", "endElements");
   xml_set_character_data_handler($parser, "characterData");
   
   
   if (!($handle = fopen('http://localhost:8012/xmltry/xmldatafromsqlserver.php', "r"))) {
      die("could not open XML input");
   }
   //http://localhost/trydropdown/retrivedata.asp
   while($data = fread($handle, 4096)) {
      xml_parse($parser, $data); 
   }
   
   xml_parser_free($parser); 
   //fclose();
   //$i = 1;
   echo "<h3>Customer Table</h3>";  
   echo "<table border = '1' style='text-align:center;'>";
   echo "<tr>";
   echo "<th> ID</th>";
   echo "<th> Name</th>";
   echo "<th> Country</th>";
   // echo "<th>Update</th>";
   echo "<th>Delete</th>";
   
   echo "</tr>";
   
   foreach($customersData as $customer) {
       echo "<tr>";
       echo "<td>".$customer['CUSTOMERID']."</td>";
       echo "<td>".$customer['NAME']."</td>";
       echo "<td>".$customer['COUNTRY']."</td>";
      //  echo "<td> <a href ='updateQueryPage.php?c_id=".$customer['CUSTOMERID']."'>Update</a></td>";
       //echo "<td> <form method = 'post' action='updateQueryPage.php'> <input type = button value = ".$customer['CUSTOMERID']." ></form>";
       echo "<td> <a href ='deleteQueryPage.php?c_id=".$customer['CUSTOMERID']."'>Delete</a></td>";
       echo "</tr>";
   
   }
   echo "</table>";

   echo "<br />";
   echo "<span style='color:'>Edit Customer: </span>";
   echo "<select name='customerdropdown' id='customerdropdown'>";
      foreach($customersData as $customer){
         echo "<option value=".$customer["CUSTOMERID"].">".$customer["NAME"]."</option>";
      }
   echo "</select>";

   echo "<br />";


   echo "<a href='deleteTblWithparser.php'>After Delete Backup data</a> <br />";

   echo "<a href='page1.html'>Insert into Customer Table</a> <br />";

   echo "<a href='updatelogtbl.php'>Update Log</a> <br /> ";





?>

<form method="post" id="form1">
  Customer Id: <input type="text" id="CustomerId" name="CustomerId" ><br/>
  Name: <input type="text" id="Name" name="Name" ><br/>
  Country: <input type="text" id="Country" name="Country" ><br/><br/>
  <input type="submit" value="Submit">
</form>
<hr id="hrid"/>
<textarea id="xmlSrc" cols="70" rows="5"></textarea>

<div id="results">

</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script>
   $("#form1").hide();
   $("#hrid").hide();
   $("#xmlSrc").hide();
   $("#results").hide();

   $("#customerdropdown").change(function(){
      console.log(this.value)

      $.ajax({
                    type: "POST",
                    url: "updateQueryPage.php",
                    dataType: "xml",
                    data:{
                        customerid: this.value
                    },
                    success: function (xml) {
                        $(xml).find('customer').each(function () {

                           $("#form1").show();
                           $("#hrid").show();
                           $("#xmlSrc").show();
                           $("#results").show();

                            

                            $("#CustomerId").val($(this).find('customerid').text());
                            $("#Name").val( $(this).find('name').text());
                            $("#Country").val($(this).find('country').text());
                            

                        });
                    }

                })
   });



   function buildXmlFromForm(form) {
  var xml = $('<XMLDocument />');
  xml.append (
    $('<Customer />').append(
      $('<CustomerId />').text(form.find("input[name='CustomerId']").val())
    ).append(
      $('<Name />').text(form.find("input[name='Name']").val())
    ).append(
      $('<Country />').text(form.find("input[name='Country']").val())
    )
  );

  return xml.html();
}

// you should use POST or PUT method (not GET) to post xml-data to server side
$( "#form1" ).submit(function(event) {
  event.preventDefault();
  $("#results").append("<ul></ul>");
  var xmlString = buildXmlFromForm($(this));
  
  $("#xmlSrc").val(xmlString);
  $.ajax({
    type: "POST",
    dataType: 'xml',
   
   // url: 'myfile.asp',
   // data: xmlString,
    
  });
  $.post("updateCustomer.php",{
      file:xmlString
  }).done(()=>{
   window.location.replace("saxparserdemo.php");
  })
  console.log(xmlString)
});
</script>

   
</body>
</html>