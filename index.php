<?php
require('includes/header.php');
?>
<?php
require('includes/navbar.php');
?>
<div class="container">
	<h2>Tool post bài vào nhóm Facebook</h2>
	<br>
	<?php
	// require('functions/getToken.php');
	if (isset($_GET["error"])) {
		echo	'<div class="alert alert-danger  w-50" role="alert">';
		echo		 $_GET["error"];
		echo	'</div>';
	}
	if (isset($_GET["success"])) {
		echo	'<div class="alert alert-success w-50" role="alert">';
		echo		 $_GET["success"];
		echo	'</div>';
	}
	?>
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="first">Access Token</label>
						<input type="text" class="form-control" placeholder="EAAA..." id="access-token">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="first">Group ID (tách ra bởi dấu ";")</label>
						<textarea type="text" class="form-control" placeholder="3114525392118022;2762118607399849..." id="spam-target"></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="last">Nội dung bài đăng</label>
						<textarea type="text" class="form-control" placeholder="Bài viết..." id="spam-message"></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="last">URL ảnh</label>
						<input type="text" class="form-control" placeholder="Image URL" id="spam-attachment">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="last">Thời gian chờ giữa mỗi lần đăng (giây)</label>
						<input type="text" class="form-control" placeholder="Distance per post..." id="spam-timer" value="10">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6" id="logText">

		</div>
	</div>
	<Br>
	<button id="start-spam" class="btn btn-success mr-5">Đăng</button>
	<button id="clean-spam" class="btn btn-primary mr-5">Xóa Thông Báo</button>
	<button id="group-spam" class="btn btn-info mr-5">Tìm Group Id</button>

</div>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script>
	$("#start-spam").click(e => {
		var counter = 0;
		var countPost = 0;
		let messages = $('#spam-message').val().split('|');
		let targets = $('#spam-target').val().split(';');
		let timer = $('#spam-timer').val();
		targets.forEach(target => {
			counter++;
			setTimeout(() => {
				let mess = messages[~~(Math.random() * messages.length)];
				$.post("https://graph.facebook.com/" + target + "/photos", {
					access_token: $('#access-token').val(),
					message: mess,
					url: $('#spam-attachment').val()
				}).then(dataPost => {
					countPost++;
					var link = "https://www.facebook.com/" + dataPost.post_id;
					$('#logText').append('<span style="color: green;">Posted ' + countPost + ' on <a href="' + link + '" target="_blank">' + dataPost.post_id + '</a></span><br/>');
					if (countPost === targets.length) {
						timeOutDone();
					};
				}).fail(() => {
					countPost++;
					var link = "https://www.facebook.com/" + target;
					$('#logText').append('<span style="color: red;">Failed to post ' + countPost + ' on <a href="' + link + '" target="_blank">' + target + '</a></span><br/>');
					if (countPost === targets.length) {
						timeOutDone();
					};
				});
			}, counter * timer * 1000);
		});
		$('#logText').append('<span style="color: black;font-weight: bold;"> - - - - START - - - -</span><br/>');
	});

	$("#clean-spam").click(e => {
		$('#logText').html("");
	});

	$("#group-spam").click(e => {
		var groupsId = '';
		$('#logText').html("");
		$.get("https://graph.facebook.com/me/groups", {
			headers: {
				'Access-Control-Allow-Origin': '*'
			},
			crossDomain: true,
			access_token: $('#access-token').val()
		}).then(dataGet => {
			console.log(dataGet);
			dataGet.data.forEach(groupId => {
				groupsId += ';' + groupId.id;
			});
			$('#logText').html(groupsId.substring(1, groupsId.length));
		}).fail(() => {
			$('#logText').append('<span style="color: red;"> - - - - Failed - - - -</span><br/>');
		});
	});

	function timeOutDone() {
		$('#logText').append('<span style="color: blue;"> - - - - DONE - - - -</span><br/>');
	};
</script>
</body>

</html>