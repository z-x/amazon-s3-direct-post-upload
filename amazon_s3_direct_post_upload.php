<?php
// THIS YOU CHANGE
$aws_access_key = ''; // your acces key to Amazon services (get if from https://portal.aws.amazon.com/gp/aws/securityCredentials)
$aws_secret_key = ''; // secret access key (get it from https://portal.aws.amazon.com/gp/aws/securityCredentials)

$bucket = 'example'; // the name you've chosen for the bucket
$key = 'upload/${filename}'; // the folder and adress where the file will be uploaded; ${filename} will be replaced by original file name (the folder needs to be public on S3!)
$success_action_redirect = 'http://example.com/success.html'; // URL that you will be redirected to when the file will be successfully uploaded
$content_type = ''; // limit accepted content types; empty will disable the filter; for example: 'image/', 'image/png'
$acl = 'private'; // private or public-read







// THIS YOU DON'T
$year = date(Y) + 10;

$policy = '{ "expiration": "'.$year.'-12-01T12:00:00.000Z",
	"conditions": [
		{"bucket": "tylkoteatr"},
		["starts-with", "$key", "'.str_replace('${filename}', '', $key).'"],
		{"acl": "'.$acl.'"},
		{"success_action_redirect": "'.$success_action_redirect.'"},
		["starts-with", "$Content-Type", "'.$content_type.'"],
		{"x-amz-meta-uuid": "14365123651274"},
		["starts-with", "$x-amz-meta-tag", ""]
	]
}';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Amazon S3 Direct Upload</title>
</head>
<body>
	<h2>Your code</h2>
	<code style="word-wrap: break-word;"><pre>
	<?php
	echo htmlentities('
<form action="http://'.$bucket.'.s3.amazonaws.com/" method="post" enctype="multipart/form-data">
	<input type="hidden" name="key" value="'.$key.'">
	<input type="hidden" name="acl" value="'.$acl.'">
	<input type="hidden" name="success_action_redirect" value="'.$success_action_redirect.'">
	<input type="hidden" name="Content-Type" value="'.$content_type.'">
	<input type="hidden" name="x-amz-meta-uuid" value="14365123651274">
	<input type="hidden" name="x-amz-meta-tag" value="">
	<input type="hidden" name="AWSAccessKeyId" value="'.$aws_access_key.'">
	<input type="hidden" name="Policy" value="'.base64_encode($policy).'">
	<input type="hidden" name="Signature" value="'.base64_encode(hash_hmac('sha1', base64_encode($policy), $aws_secret_key, true)).'">
	<input type="file" name="file">
	<input type="submit" name="submit" value="Upload to Amazon S3">
</form>
	');
	?>
	</pre></code>

	<h2 style="margin-top: 200px;">Working example</h2>
	<form action="http://<?php echo $bucket; ?>.s3.amazonaws.com/" method="post" enctype="multipart/form-data">
		<input type="hidden" name="key" value="<?php echo $key; ?>">
		<input type="hidden" name="acl" value="<?php echo $acl; ?>">
		<input type="hidden" name="success_action_redirect" value="<?php echo $success_action_redirect; ?>">
		<input type="hidden" name="Content-Type" value="<?php echo $content_type; ?>">
		<input type="hidden" name="x-amz-meta-uuid" value="14365123651274">
		<input type="hidden" name="x-amz-meta-tag" value="">
		<input type="hidden" name="AWSAccessKeyId" value="<?php echo $aws_access_key; ?>">
		<input type="hidden" name="Policy" value="<?php echo base64_encode($policy); ?>">
		<input type="hidden" name="Signature" value="<?php echo base64_encode(hash_hmac('sha1', base64_encode($policy), $aws_secret_key, true)); ?>">
		<input type="file" name="file">
		<input type="submit" name="submit" value="Upload to Amazon S3">
	</form>
</body>
</html>