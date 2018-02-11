<form method ='POST' action='php/test1.php'>
      <div class='form-group'>
      <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" id="uname" name="uname">
        <p class='text-danger'>Please enter the correct Login.</p>
      </div>
      <div class='form-group'>
        <label for='pwd'>Password:</label>
        <input type='password' class='form-control' name='pwd' id='pwd'>
        <p class='text-danger'>Please enter the correct Password.</p>
      </div>
      <div class='checkbox'>
        <label><input type='checkbox'> Remember me</label>
      </div>
      <button type='submit' id='loginbutton' class='btn btn-default'>Submit</button>
    </form>
    <br>