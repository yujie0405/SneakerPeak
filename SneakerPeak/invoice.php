<?php
 include('database/config.php');
 session_start();
 
 $Ordercode=$_SESSION['Ordercode'];
 $method=$_SESSION['payment_method'];
 $UID=$_SESSION["CustomerID"] ; 
$query="SELECT * FROM oder WHERE Order_Code='$Ordercode'AND UID='$UID'";
$result=mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result);

$shipping=$row['ShippingFeed'];
$amount=$row['Order_TotalAmmount'];
$all=$shipping+$amount;

$quer="SELECT * FROM oder_details WHERE Order_Code='$Ordercode'AND UID='$UID'";
$resu=mysqli_query($conn,$quer);

$qu="SELECT * FROM customer WHERE id='$UID'";
$re=mysqli_query($conn,$qu);
$roll = mysqli_fetch_assoc($re);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<title>Invoice</title>

		<!-- Favicon -->
		<link rel="icon" href="./images/favicon.png" type="image/x-icon" />

		<!-- Invoice styling -->
		<style>
			body {
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				text-align: center;
				color: #777;
			}

			body h1 {
				font-weight: 300;
				margin-bottom: 0px;
				padding-bottom: 0px;
				color: #000;
			}

			body h3 {
				font-weight: 300;
				margin-top: 10px;
				margin-bottom: 20px;
				font-style: italic;
				color: #555;
			}

			body a {
				color: #06f;
			}

			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				border-collapse: collapse;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}
      .BTN
      {
        height: 30px;
        padding-left: 6px;
        font-family: Arial, Helvetica, sans-serif;
      }
      .button
      {
        display: flex;
        justify-content: center;
        padding-left: 6px;
      }
		</style>
	</head>

	<body>
		

		<div class="invoice-box">
			<table>
				<tr class="top">
					<td colspan="5">
						<table>
							<tr>
								<td class="title">
									<img src="picture/logo/logo.png" alt="Company logo" style="width: 30%; max-width: 300px" />
								</td>

								<td>
									Invoice #: <?= $Ordercode?><br />

									Payment Date:<?php echo $row['Order_Date']?><br />
								
								</td>
							</tr>
						</table>
            
					</td>
				</tr>
       
				<tr class="information">
					<td colspan="5">
						<table>
							<tr>
                <hr>
								<td>
                  From:<br />
									Sneaker Peak, Inc.<br />
									Jalan Ayer Keroh Lama<br />
									75450 Bukit Beruang, Melaka
								</td>

								<td>
                  TO:<br />
									<?php echo $roll['name'] ?><br />
									<?php echo $roll['email'] ?><br/>
									<?php echo $roll['Customer_ContractNumber'] ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading" >
					<td colspan="5">Payment Method</td>

					
				</tr>

				<tr class="details">
					<td colspan="5"><?=$method?></td>

					
				</tr>

				<tr class="heading">
					<td>Item</td>
					<td>Size</td>
					<td>Price</td>
					<td>QTY</td>
					<td>Subtotal</td>
				</tr>
				<?php  
					while($ro=mysqli_fetch_assoc($resu)){
					?>
				<tr class="item">
					
					<td><?php echo $ro['Order_Product']?></td>
          			<td><?php echo $ro['Order_Size']?></td>
					<td><?php echo number_format(  $ro['Order_ProdcutP'],2); ?></td>
          			<td><?php echo $ro['Order_qty']?></td>
          			<td><?php echo number_format( ($ro['Order_ProdcutP']*$ro['Order_qty']) ,2); ?></td>
					 
				</tr>
				<?php	};	?>
				<tr class="total">
					<td colspan="3"></td>
					
					<td>Shipping Fee:</td>
					<td>RM <?php echo number_format($shipping,2)?></td>

					
				</tr>
				
				<tr class="total">
					<td colspan="3"></td>
					
					<td>Total:</td>
					<td>RM <?php echo number_format($all,2)?></td>

					
				</tr>
			</table>
      <br>
      <br>
      <br>
      <div class="button">
        <button class="BTN" onclick="backtomenu()" id="hide1">Back To Menu</button>
        <br>
        <button class="BTN" id="hide2" onclick="print_content()">Print as PDF</button>
      </div>
		</div>
    <script>
		function backtomenu()
		{
			window.location = "menu.php";

		}	
      function print_content()
    {
       
        var hide1 = document.getElementById("hide1");
        var hide2 = document.getElementById("hide2");
    
        hide1.style.display = "none";
        hide2.style.display = "none";
        
        window.print();

        hide1.style.display = "block";
        hide2.style.display = "block";
    }
    </script>
	</body>
</html>