<?php
// Start the session
session_start();
?>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">PASCAL</a>
    <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
   
    <div class="collapse navbar-collapse" id="navbarsExampleDefault"> <!--  This is what makes it responsive-->
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link customlink" href="teacher-dashboard.php">&#8962  &nbsp Dashboard
            <span class="sr-only">(current)</span>   
          </a>
        </li>
        <li class="nav-item customlink">
          <a class="nav-link" href="classlist.php">&#128100 &nbsp Class List&nbsp</a>
        </li>
        <li class="nav-item customlink">
          <a class="nav-link" href="assignAssessmentTeacher.php">	&#9998 &nbsp Assign Assessments&nbsp</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"> Help</a>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <li class="nav-item customlink">
      <a class="nav-link" href="php/logout.php">&#128274 &nbsp Logout &nbsp</a>
      <li class="nav-item">
          <a class="nav-link" href="#">  &nbsp  <?php print_r( $_SESSION['user']); ?></a>
    </ul>
    </div>
  </nav>
 
