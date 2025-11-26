<nav class="navbar navbar-expand-lg bg-body-tertiary rounded" aria-label="Thirteenth navbar example">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center me-auto" href="index.php">
      <img src="../images/home.png" alt="Logo" height="30" class="me-2" />
      Escapify
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample11"
      aria-controls="navbarsExample11" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-lg-flex" id="navbarsExample11">
      <ul class="navbar-nav col-lg-6 justify-content-lg-center">
        <li class="nav-item"><a class="nav-link" href="/index.php#Rooms">Rooms</a></li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li>
              <a class="dropdown-item" href="#">Another action</a>
            </li>
            <li>
              <a class="dropdown-item" href="#">Something else here</a>
            </li>
          </ul>
        </li>
      </ul>
	  <!-- If user is logged in -->
	  <?php if (isset($_SESSION['user_id'])): ?>
	  <?php 
	    // Check if current page is manage_account.php
	    $current_page = basename($_SERVER['PHP_SELF']);
	    $is_manage_account = ($current_page === 'manage_account.php');
	  ?>
	  
	  <div class="d-lg-flex col-lg-3 justify-content-lg-end">
	    <?php if ($is_manage_account): ?>
	      <a class="btn btn-primary" href="../index.php">Home</a>
	    <?php else: ?>
	      <a class="btn btn-primary" href="../manage_account.php">My Account</a>
	    <?php endif; ?>
    </div>
	  
	  <div class="d-lg-flex col-lg-3 justify-content-lg-end">
        <a class="btn btn-primary" href="../logout.php">Logout</a>
    </div>
	  
	  <!-- If user is not logged in -->
	  <?php else: ?>
	  <div class="d-lg-flex col-lg-3 justify-content-lg-end">
        <a class="btn btn-primary" href="login.php">Login</a>
    </div>
	  <?php endif ?>
    </div>
  </div>
</nav>
