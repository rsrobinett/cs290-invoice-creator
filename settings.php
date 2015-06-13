<?php $pagetitle = Settings;?>

<?php include 'shared/head.php';?>
<?php include 'shared/navigation.php';?>
<?php include 'shared/header.php';?>
<?php
   
    $username = $_SESSION['username'];
    //$companyid = getCompanyIDbyUsername($username);
    
    $company = getCompanySettingsbyUsername($username);
?>
<script>
    window.onload = function(){document.getElementById('state').value="<?php echo $company['state']; ?>";}
</script>

<div id="errortext"></div>
<form class="form-horizontal" role="form" id="form" action="auth.php" method="post" autocomplete="off" onsubmit="ajaxCall(this, 'updatesettings'); return false;">
 <div class="form-group">
     <label class="col-sm-2 control-label">Name or company</label>
     <div class="col-sm-4">
         <input type="text" class="form-control" placeholder="Company" id="companyname" name="companyname" value="<?php echo $company['name']; ?>" required>
     </div>
 </div>
 <div class="form-group">
     <label class="col-sm-2 control-label">Street</label>
     <div class="col-sm-4">
         <input type="text" class="form-control" placeholder="Street Address" id="streetaddress" name="streetaddress" value="<?php echo $company['streetaddress']; ?>" required>
     </div>
 </div>
 <div class="form-group">
     <label class="col-sm-2 control-label">City</label>
     <div class="col-sm-4">
         <input type="text" class="form-control" placeholder="City" id="city" name="city" value="<?php echo $company['city']; ?>" required>
     </div>
 </div>
 <div class="form-group">
     <label class="col-sm-2 control-label">State</label>
     <div class="col-sm-4">
         <select class="form-control m-b" name="state" id="state" required>
             <option value="">Select A State</option>
             <option value="AL">Alabama</option>
             <option value="AK">Alaska</option>
             <option value="AZ">Arizona</option>
             <option value="AR">Arkansas</option>
             <option value="CA">California</option>
             <option value="CO">Colorado</option>
             <option value="CT">Connecticut</option>
             <option value="DE">Delaware</option>
             <option value="DC">District Of Columbia</option>
             <option value="FL">Florida</option>
             <option value="GA">Georgia</option>
             <option value="HI">Hawaii</option>
             <option value="ID">Idaho</option>
             <option value="IL">Illinois</option>
             <option value="IN">Indiana</option>
             <option value="IA">Iowa</option>
             <option value="KS">Kansas</option>
             <option value="KY">Kentucky</option>
             <option value="LA">Louisiana</option>
             <option value="ME">Maine</option>
             <option value="MD">Maryland</option>
             <option value="MA">Massachusetts</option>
             <option value="MI">Michigan</option>
             <option value="MN">Minnesota</option>
             <option value="MS">Mississippi</option>
             <option value="MO">Missouri</option>
             <option value="MT">Montana</option>
             <option value="NE">Nebraska</option>
             <option value="NV">Nevada</option>
             <option value="NH">New Hampshire</option>
             <option value="NJ">New Jersey</option>
             <option value="NM">New Mexico</option>
             <option value="NY">New York</option>
             <option value="NC">North Carolina</option>
             <option value="ND">North Dakota</option>
             <option value="OH">Ohio</option>
             <option value="OK">Oklahoma</option>
             <option value="OR">Oregon</option>
             <option value="PA">Pennsylvania</option>
             <option value="RI">Rhode Island</option>
             <option value="SC">South Carolina</option>
             <option value="SD">South Dakota</option>
             <option value="TN">Tennessee</option>
             <option value="TX">Texas</option>
             <option value="UT">Utah</option>
             <option value="VT">Vermont</option>
             <option value="VA">Virginia</option>
             <option value="WA">Washington</option>
             <option value="WV">West Virginia</option>
             <option value="WI">Wisconsin</option>
             <option value="WY">Wyoming</option>
         </select>
     </div>
 </div>
 <div class="form-group">
      <label class="col-sm-2 control-label">Zipcode</label>
      <div class="col-sm-4">
            <input type="number" class="form-control" placeholder="Zipcode" id="zip" name="zip" min="10000" max="99999" value="<?php echo $company['zip']; ?>" required>
      </div>
 </div>
 <div class="hr-line-dashed"></div>
 <div class="form-group">
     <div class="col-sm-4 col-sm-offset-2">
         <button class="btn btn-primary" type="submit">Save</button>
     </div>
 </div>
 </form>
<?php include 'shared/footer.php';?>