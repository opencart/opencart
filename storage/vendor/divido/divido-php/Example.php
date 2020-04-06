<?php
$apiKey = "";

$amount = 1200;
$sandbox = true;

require_once('./lib/Divido.php');
Divido::setMerchant($apiKey);

if ($_POST) {
	$response = Divido_CreditRequest::create(array(
	  "country" => "GB",
	  "finance" => $_POST['finance'],
	  "reference"=>$_POST['reference'],
	  "customer" => array(
		"title" => $_POST['title'],
		"first_name" => $_POST['firstName'],
		"middle_name" => $_POST['middleName'],
		"last_name" => $_POST['lastName'],
		"email" => $_POST['email'],
		"mobileNumber" => $_POST['mobileNumber'],
		"phoneNumber" => $_POST['phoneNumber'],
		"postcode" => $_POST['postCode'],
		"country" => $_POST['country'],
	  ),
	  "metadata"=>array(
	  	"orderNumber"=>10001,
	  	"checksum"=>md5('10001:mySecret')
	  ),
	  "products"=>array(
	  	array(
		  	"type"=>"product",
		  	"text"=>"Gibson Les Paul Studio Raw Guitar",
		  	"quantity"=>1,
		  	"value"=>1153.00,
		),
		array(
		  	"type"=>"service",
		  	"text"=>"Restring Upgrade",
		  	"quantity"=>1,
		  	"value"=>100.00,
		),
	  ),
	  "response_url"=>"http://www.webshop.com/callback.php",
	  'checkout_url'=>'http://www.webshop.com/checkout',
	  "redirect_url"=>"http://www.webshop.com/success.php",
	));

	if ($response->status == 'ok') {
		header("Location:".$response->url);
	} else {
		print_r($response->__toArray());
	}

} else {
	?>
		<form method="post">
			
			<table width="400">
				<tr>
					<td>Finance:</td>
					<td><select name="finance">
						<?php
						$response = Divido_Finances::all();

						if ($response->status == 'ok') {
							foreach($response->finances as $finance) {
								print '<option value="'.$finance->id.'">'.$finance->text.' ('.$finance->interest_rate.'% APR)</option>';
							}
						}
						?>
					</select></td>
				</tr>
				<tr>
					<td>Reference:</td>
					<td><input type="text" name="reference" value="11001"></td>
				</tr>
				<tr>
					<td>Amount:</td>
					<td>&pound; <?php print number_format($amount); ?></td>
				</tr>
				<tr>
					<td>Deposit:</td>
					<td>&pound; <input type="text" name="deposit" value="100"></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Title:</td>
					<td><select name="title">
						<option value="Mr">Mr</option>
						<option value="Mrs" selected>Mrs</option>
					</select></td>
				</tr>
				<tr>
					<td>First Name:</td>
					<td><input type="text" name="firstName" value="Ann"></td>
				</tr>
				<tr>
					<td>Middle Name:</td>
					<td><input type="text" name="middleName" value=""></td>
				</tr>
				<tr>
					<td>Last Name:</td>
					<td><input type="text" name="lastName" value="Heselden"></td>
				</tr>
				<tr>
					<td>Phone:</td>
					<td><input type="text" name="phoneNumber" value="0201234567"></td>
				</tr>
				<tr>
					<td>Mobile:</td>
					<td><input type="text" name="mobileNumber" value="0701234567"></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><input type="text" name="email" value="hallsten@me.com"></td>
				</tr>
				<tr>
					<td>Post Code:</td>
					<td><input type="text" name="postCode" value="BA13 3BN"></td>
				</tr>
				<tr>
					<td>Country:</td>
					<td><select name="country">
						<option value="GB" selected>GB</option>
					</select></td>
				</tr>
			</table>



			<input type="submit" name="test" value="Continue to Divido" />

		</form>
	<?
}