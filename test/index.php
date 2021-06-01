<form name="frmCheck" method="post">
    <label class="clabel" for="rowno">Number of Rows:</label>
<?php 
$rowno = isset($_POST['rowno'])?$_POST['rowno']:10;
if($rowno == 10) $rowno = isset($_GET['limit'])?$_GET['limit']:10;
?>
    <select class="name" id="rowno" name="rowno" onchange="window.location='viewreg.php?limit='+this.value+'&currentpage=1'">
        <option value='all' hidden disabled>All</option>
        <option value='10' <?=$rowno==10?'selected':''?>>10</option>
        <option value='15' <?=$rowno==15?'selected':''?>>15</option>
        <option value='20' <?=$rowno==20?'selected':''?>>20</option>
        <option value='25' <?=$rowno==25?'selected':''?>>25</option>
        <option value='30' <?=$rowno==30?'selected':''?>>30</option>
    </select>

<?php
    $currentpage = isset($_GET['currentpage']) ? $_GET['currentpage'] : 1;

    $no_of_records_per_page = $rowno;
    $startfrom = ($currentpage - 1) * $no_of_records_per_page;

    //get total number of records in database
    $sqlcount = "SELECT * FROM cusregtbl";
    $stmt = $con->prepare($sqlcount);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_rows = $result->num_rows;
    $totalpages = ceil($num_rows/$no_of_records_per_page);
    
    //query for pagination
    $sqllimit = "SELECT * FROM cusregtbl LIMIT $startfrom, $no_of_records_per_page";
    if ($stmt = $con->prepare($sqllimit))
    {
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        if ($num_rows>0)
        {
            echo "<table id='t01'' class='viewtbl' width='100%'>
            <tr id='tblhead'>
            <th class='th'>Account Number</th>
            <th class='th'>Customer Name</th>
            <th class='th'>Residence Type</th>
            <th class='th'>Customer Address</th>
            <th class='th'>Email Address</th>
            </tr>";
            while ($rows = $result->fetch_assoc())
            {
                $accno = $rows['AccountNo'];
                $name = $rows['Fullname'];
                $restype = $rows['ResidenceType'];
                $adrs = $rows['CustomerAddress'];
                $email = $rows['Email'];
                
                // output data of each row
                echo "<tr>
                <td class='th'>". $accno."</td>
                <td class='th'>". $name."</td>
                <td class='th'>" . $restype. "</td>
                <td class='th'>" . $adrs. " </td>
                <td class='th'>". $email. "</td>
                </tr>";
            }
            echo "</table>";

            if($currentpage >= 2)
            {
                echo "<a class='nav_a' href='viewreg.php?limit=".$rowno."&currentpage=".($currentpage - 1)."'>-Previous-</a>";
            }

            for($i = 1; $i <= $totalpages; $i++)
            {
                if($i == $currentpage)
                {
                    echo '<a class="nav_a" href = "viewreg.php?limit='.$rowno.'&currentpage='.$i.'">'."| ".$i."  ".'</a>';
                }
                else
                {
                    echo '<a class="nav_a" href = "viewreg.php?limit='.$rowno.'&currentpage='.$i.'">'."| ".$i."  ".'</a>';
                }
            }

            if($currentpage < $totalpages)
            {
                echo "<a class='nav_a' href='viewreg.php?limit=".$rowno."&currentpage=".($currentpage + 1)."'>-Next-</a>";
            }
        }
        else
        {
        echo "Oops! No records found.";
        }
    }
?>

//javascript code for select option
    <script type="text/javascript">
        <?php printf("let rownum='%s'", empty($_POST['rowno']) ? 0 : $_POST['rowno']); ?>

        let myForm=document.forms.frmCheck;
        let mySelect=myForm.rowno;
        let myCheck=myForm.check;
        
        if(rownum)
        {
            if(rownum=='all') myCheck.checked=true;
            Array.from(mySelect.querySelectorAll('option')).some(option=>
            {
                if(rownum==Number(option.value) || rownum=='all')
                {
                    option.selected=true;
                    return true;
                }
            });
        }
                
        // listen for changes on the checkbox
        myCheck.addEventListener('click',function(e)
        {
            if(myCheck.checked)
            {

                var msg = confirm('Do you really want to see all of the \nrows? For a big table this could crash \nthe browser.');
                if(!msg)
                {
                    myCheck.checked=false;
                    return false;
                }
            }

            if(mySelect.firstElementChild.value=='all')
            {
                mySelect.firstElementChild.selected=this.checked;
                mySelect.firstElementChild.disabled=!this.checked;
            }
            myForm.submit();
        });

        // listen for changes on the select  
        mySelect.addEventListener('change',function(e)
        {
            if(myCheck.checked) myCheck.checked=false;
            
            myForm.submit();
        });
    </script>
</form>