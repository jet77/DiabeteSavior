<h2>Add Visit</h2>

<script language="javascript">
    function calculateBmi()
         {
            var wei = document.getElementById("weight_units");
            var hei = document.getElementById("height_units");
            var weivalue = document.getElementById("weight_units").value;
            var heivalue = document.getElementById("height_units").value;
            var val1 = document.getElementById("height").value;
            var val2 = document.getElementById("weight").value;
            var ansD = document.getElementById("bmi");
            if (heivalue == "Cm" && weivalue == "Kg"){
                ansD.value = (10000*val2/val1/val1).toFixed(1);
            }
            if (heivalue == "Inch" && weivalue == "Lb"){
                ansD.value = (703*val2/val1/val1).toFixed(1);
            };
        }
</script>

<div>
<?php
    echo "Patient ID: ".$patient['Patient']['patient_number']."<br>";
    echo "First Name: ".$patient['Patient']['patient_firstname']."<br>";
    echo "Last Name: ".$patient['Patient']['patient_lastname']."<br>";
    echo "DOB: ".$patient['Patient']['dob']."<br>";
?>
</div>
                      
<hr>

<form class="form-horizontal" role="form" action="/visits/add" method="post">
<?php
  echo $this->Html->css('bootstrap-select');
  echo $this->Html->script('bootstrap-select');
?>

<!-- vitals_labs -->
<h3>Vitals and Labs</h3>
<div class="form-group">
  <label class="col-lg-1 control-label" for="weight">Weight</label>
  <div class="col-lg-4">
    <input id="weight" name="weight" type="text" placeholder="weight" class="">
    <select name="weight_units" id="weight_units" onchange="document.all.height_units.options[this.selectedIndex].selected=true;">
        <option value="Lb">Lb</option>
        <option value="Kg">Kg</option>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="height">Height</label>
  <div class="col-lg-4">
    <input id="height" name="height" type="text" placeholder="height" class="">
    <select id="height_units" name="height_units" onchange="document.all.weight_units.options[this.selectedIndex].selected=true;">
        <option value="Inch">Inch</option>
        <option value="Cm">Cm</option>
    </select> 
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="bmi">BMI</label>
  <div class="col-lg-4">
    <input id="bmi" name="bmi" type="text" placeholder="bmi" class="">
    <input name="Sumbit" value="Calculate" onclick="calculateBmi();" type="button">
    <p>BMI = 703&nbsp;* &nbsp;Weight(lb) / Height<sup>2</sup>(inch)</p>
    <p>BMI = Weight(kg) / Height<sup>2</sup>(m)</p>
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="bps">bps</label>
  <div class="col-lg-4">
    <input id="bps" name="bps" type="text" placeholder="bps" class="form-control">   
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="bpd">bpd</label>
  <div class="col-lg-4">
    <input id="bpd" name="bpd" type="text" placeholder="bpd" class="form-control">   
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="A1c">A1c</label>
  <div class="col-lg-4">
    <input id="A1c" name="A1c" type="text" placeholder="A1c" class="form-control">   
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="eGFR">eGFR</label>
  <div class="col-lg-4">
    <input id="eGFR" name="eGFR" type="text" placeholder="eGFR" class="form-control">   
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="notes">Notes</label>
  <div class="col-lg-4">
    <input id="notes" name="notes" type="textarea" placeholder="notes" class="form-control">   
  </div>
</div>

<hr>

<!-- treatments -->
<h3>Therapy Goals</h3>
<div class="form-group">
  <label class="col-lg-1 control-label" for="a1c_goal">A1c Goal</label>
  <div class="col-lg-4">
    <input id="a1c_goal" name="a1c_goal" type="text" placeholder="a1c_goal" class="form-control">   
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="weight_goal">Weight Goal</label>
  <div class="col-lg-4">
    <input id="weight_goal" name="weight_goal" type="text" placeholder="weight_goales" class="form-control">   
  </div>
</div>

<hr>

<!-- medhistory_complaints -->
<h3>Medical History and Complaints</h3>
<div class="form-group">
  <label class="col-lg-1 control-label" for="complaints">Complaints</label>
  <div class="col-lg-4">
    <input id="complaints" name="complaints" type="textarea" placeholder="complaints" class="form-control"> 
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="hypo">Hypo</label>
  <div class="col-lg-4">
    <input name="hypo" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="hypo" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="weight_gain">Weight_gain</label>
  <div class="col-lg-4">
    <input name="weight_gain" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="weight_gain" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="renal_gu">Renal_gu</label>
  <div class="col-lg-4">
    <input name="renal_gu" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="renal_gu" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="gi_sx">Gi_sx</label>
  <div class="col-lg-4">
    <input name="gi_sx" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="gi_sx" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="chf">Chf</label>
  <div class="col-lg-4">
    <input name="chf" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="chf" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="cvd">Cvd</label>
  <div class="col-lg-4">
    <input name="cvd" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="cvd" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="bone">Bone</label>
  <div class="col-lg-4">
    <input name="bone" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="bone" type="radio" value="no">no  
  </div>
</div>

<hr>

<!-- drug_allergies -->
<h3>Drug Allergies</h3>
<div class="form-group">
  <label class="col-lg-1 control-label" for="met">Met</label>
  <div class="col-lg-4">
    <input name="met" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="met" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="dpp_4i">Dpp_4i</label>
  <div class="col-lg-4">
    <input name="dpp_4i" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="dpp_4i" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="glp_1ra">Glp_1ra</label>
  <div class="col-lg-4">
    <input name="glp_1ra" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="glp_1ra" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="tzd">Tzd</label>
  <div class="col-lg-4">
    <input name="tzd" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="tzd" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="agi">Agi</label>
  <div class="col-lg-4">
    <input name="agi" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="agi" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="colsvl">Colsvl</label>
  <div class="col-lg-4">
    <input name="colsvl" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="colsvl" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="bcr_or">Bcr_or</label>
  <div class="col-lg-4">
    <input name="bcr_or" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="bcr_or" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="su_gln">Su_gln</label>
  <div class="col-lg-4">
    <input name="su_gln" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="su_gln" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="insulin">Insulin</label>
  <div class="col-lg-4">
    <input name="insulin" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="insulin" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="sglt_2">Sglt_2</label>
  <div class="col-lg-4">
    <input name="sglt_2" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="sglt_2" type="radio" value="no">no  
  </div>
</div>

<div class="form-group">
  <label class="col-lg-1 control-label" for="praml">Praml</label>
  <div class="col-lg-4">
    <input name="praml" type="radio" value="yes">yes&nbsp;&nbsp; 
    <input name="praml" type="radio" value="no">no  
  </div>
</div>

<!-- diagnoses -->

<div class="control-group">
  <label class="col-lg-1 control-label" for="singlebutton"></label>
  <div class="col-lg-4">
    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Save</button>
  </div>
</div>
</form>