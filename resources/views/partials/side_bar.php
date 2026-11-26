    <div class="profile-tab-nav border-right">  
	    <div class="p-4">
		    <form class="form" id = "form" action="" enctype="multipart/form-data" method="post">
                <input type="hidden" name="profile_token" value="<?= htmlspecialchars($token) ?>">
                <div class="upload">
                    <?php
                        $id = $user["id"];
                        $name = $user["name"];
                        $image = $user["image"];
                    ?>
                    <img src="img/<?php echo $image; ?>" width = 125 height = 125 title="<?php echo $image; ?>">
                    <div class="round">
                        <input type="file" name="image" id = "image" accept=".jpg, .jpeg, .png">
                        <i class = "fa fa-camera" style = "color: var(--color-surface);"></i>
                    </div>
                </div>
            </form>
		    <h4 class="p_name text-center"> <?php echo $cus_name; ?></h4>
	    </div>
		<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" >
            <form method="post" action="profile_1.php">
                <?php
                 if($page == 1)
                 {
                    ?>
                    <button class="account active-button" type="submit" name="page" value="1" id="account-tab" >
                    <?php
                 }
                 else
                 {
                    ?>
                    <button class="account active-button-borderless" type="submit" name="page" value="1" id="account-tab" >
                    <?php 
                 }
                ?>
                    <i class="distance fa fa-home text-center mr-1"></i> Account
                </button>
                <?php 
                if($page==2)
                {
                    ?>
                    <button class="password active-button" type="submit" name="page" value="2" id="password-tab" onclick="highlightButton(this)">
                    <?php
                }
                else{
                    ?>
                    <button class="password active-button-borderless" type="submit" name="page" value="2" id="password-tab" onclick="highlightButton(this)">
                    <?php 
                }
                ?>
                    <i class="distance fa fa-key text-center mr-1"></i> Password
                </button>
            </form>
            <button class="logout" type="submit" id="notification-tab" onclick="confirmLogout();">
                    <i class="distance1 fa-solid fa-right-from-bracket"></i>Logout
            </button>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
      document.getElementById("image").onchange = function()
      {
          document.getElementById("form").submit();
      };
      function confirmLogout() 
      {
		Swal.fire(
        {
			title: 'Are you sure?',
			text: "You will be logged out.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, logout!'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = "Logout.php";
			}
		});
	}   
    
    function highlightButton(button) {
    document.getElementById("password-tab").classList.remove("active-button-borderless");
    document.getElementById("password-tab").classList.add("active-button");
    document.getElementById("account-tab").classList.remove("active-button");
    document.getElementById("account-tab").classList.add("account active-button-borderless");

    // // Remove the active button class from both buttons
    // accountButton.classList.remove("active-button");
    // accountButton.classList.remove("active-button-borderless");
    // passwordButton.classList.remove("active-button");
    // passwordButton.classList.remove("active-button-borderless");

    // // Add the active button class to the clicked button
    // button.classList.add("active-button");
    // button.classList.add("active-button-borderless");
}
</script>

