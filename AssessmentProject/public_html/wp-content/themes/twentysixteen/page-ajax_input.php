<?php
/*
  Template Name: Input Submission Page
 */
get_header();
?>
<div class="assess-form">
    <h2>Assessment Form</h2>

    <div class="control-group">  
        <form id="assessform" >
            <label for="pupil_class"<h3>Hello, please enter the details: </h3><br /></label>
            <label>Name: <input type="text" name="pupil_name" /></label>
            <label>Class: <input type="text"  name="pupil_class" /></label>
            <label><input type="checkbox" name="q1" value="1">answer1</label><br />
            <label><input type="checkbox" name="q1" value="1">answer2</label><br />
            <label><input type="checkbox" name="q1" value="1">answer3</label><br />
            <button class="btn btn-large" id="next">Add Pupil Data</button>
            <button class="btn btn-large" id="search">Search Pupil Data</button>
            <button class="btn btn-large" id="reset">Table RESET</button>
            <input type="reset" value="Reset Form">
            
        </form>
    </div>
</div>


<!--- processing data message --->
<div id="processing">
    

</div>
<!--- DATA results output here in table form --->
<div id="results">
    <table id="results_table">
        
    </table>
</div>

<?php
get_footer();
