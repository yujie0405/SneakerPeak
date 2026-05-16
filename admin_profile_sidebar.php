<?php
   
    if(!isset($_SESSION["page"]))
    {
        $_SESSION["page"] =1;
    }
    
    include ("database/Database_Connection.php");

    $page = $_SESSION["page"];
	$errors = array();  
    $admin_id = $_SESSION["AdminID"]; 
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin WHERE id = $admin_id")); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Account Settings UI Design</title>
</head> 
<body>
    <div class="profile-tab-nav border-right">  
	    <div class="p-4">
		    <form class="form" id = "form" action="" enctype="multipart/form-data" method="post">
                <div class="upload">
                    <?php
                        $id = $user["id"];
                        $name = $user["name"];
                        $image = $user["image"];
                    ?>
                    <img src="img/<?php echo $image; ?>" width = 125 height = 125 title="<?php echo $image; ?>">
                    <div class="round">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <input type="file" name="image" id = "image" accept=".jpg, .jpeg, .png">
                        <i class = "fa fa-camera" style = "color: #fff;"></i>
                    </div>
                </div>
            </form>
		    <h4 class="p_name text-center"> <?php echo $admin_name; ?></h4>
	    </div>
		<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" >
            <form method="post" action="admin_profile.php">
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

<?php
if (isset($_FILES["image"]["name"])) 
{
     $id = $_POST["id"];
     $name = $_POST["name"];
 
     $imageName = $_FILES["image"]["name"];
     $imageSize = $_FILES["image"]["size"];
     $tmpName = $_FILES["image"]["tmp_name"];
 
     // Image validation
     $validImageExtension = ['jpg', 'jpeg', 'png'];
     $imageExtension = explode('.', $imageName);
     $imageExtension = strtolower(end($imageExtension));

     if (!in_array($imageExtension, $validImageExtension)) 
     {
     echo "
     <script>
         alert('Invalid Image Extension');
         document.location.href = '../updateimageprofile';
     </script>
     ";
     } 
     else if ($imageSize > 1200000) 
     {
     echo "
     <script>
         alert('Image Size Is Too Large');
         document.location.href = '../updateimageprofile';
     </script>
     ";
     } 
     else 
     {
         $newImageName = $name . " - " . date("Y.m.d") . " - " . date("h.i.sa"); // Generate new image name
         $newImageName .= '.' . $imageExtension;
         $query = "UPDATE admin SET image = '$newImageName' WHERE id = '$id'";

            if (mysqli_query($conn, $query)) 
         {
             // Move the uploaded image to the desired location
             $destination = "img/" . $newImageName;
             move_uploaded_file($tmpName, $destination);
             
             echo "
             <script>
             alert('Image uploaded successfully');
             document.location.href = 'admin_profile.php';
             </script>
             ";
         } 
         else 
         {
             echo "
             <script>
             alert('Error updating image');
             document.location.href = 'admin_profile.php';
             </script>
             ";
         }
     }	
 }
?>
</body>
</html>