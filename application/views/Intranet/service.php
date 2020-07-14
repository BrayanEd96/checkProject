<body>
	<div id="container">
		<div id="title">
			<h1>Start to work</h1>
		</div>
		<div id="body">
			<form id="formCheck">
				<div class="row">
					<label for="uPhoneNumber">Phone Number</label>
					<input type="text" name="userPhoneNumber" id="uPhoneNumber" placeholder="Number">
				</div>
				<div class="row">
					<label for="nEst">Establishment</label>
					<select name="location" id="nEst">
					</select>
				</div>
				<div class="row">
					<button type="button" id="bCheckIn">Check In</button>
				</div>
			</form>
			<div id="stopWatch">
				<div id="contStopWatch"> 00 : 00 : 00 </div>
				<div id="contentCButtons">
					<button id="bCheckOut">Check Out</button>
				</div>
			</div>	
		</div>
		<div id="contentMessages">
			<div id="message"></div>
			<div id="loading">Cargando...</div>
		</div>
	</div>
</body>
<style>
	#container {
		width: 100%;
		height: 100%;
		font-family: Arial, Helvetica, sans-serif;
	}
	#title{
		display: flex;
	}
	h1 {
		margin: 30px auto;
	}
	#body{
		width: 30%;
		margin: 0 auto;
		border-radius: 5px;
		padding: 30px;
		background-color: #f2f2f2
	}
	#contentMessages {
		width: 20%;
		margin: 0 auto;
	}
	#message {
		margin: 10px;
		padding: 20px;
		background-color: #ffffcc;
		border-radius: 5px;
	}
	#loading {
		margin: 10px auto;
		padding: 10px;
		background-color: #7cd1f9;
		border-radius: 5px;
		text-align: center;
		width: 50%;
	}
	.row {
		margin: 10px 0;
	}
	input, select {
		width: 60%;
		border: 2px solid #ccc;
		border-radius: 5px;
		display: block;
		margin: 5px;
		padding: 7px 10px;
	}
	label {
		margin: 5px;
	}
	button {
		font-size: 12px;
		border-radius: 5px;
		padding: 10px 24px;
		margin-top: 20px;
	}
	#bCheckIn, #bCheckOut {
		border: 1px solid #4CAF50;
		background-color: #4CAF50;
	}
	#contStopWatch {
		font-size: 80px;
		text-align: center;
	}

 	#loading, #message, #stopWatch, #bStopClock{
    	display: none;
	}
</style>
<script type="text/javascript">

	window.onload = function() {

		axios.get("<?php echo site_url('location') ?>",{
			responseType: 'json'
		}).then(function(res) {
			if (res.status == 200) {
				var cont = "<option value=0>Select Establishment</option>";
				for(var obj of res.data) {
					cont += "<option value="+obj.n_IdEstablishment+">"+obj.t_NameEst+"</option>";
				}
				document.getElementById('nEst').innerHTML = cont;
			}

		}).catch(function(err) {
			console.log(err);
		});
    }

    var message = document.getElementById('message');	
    var button = document.getElementById('bCheckIn');

    var phone;
	var estab;
    
    button.addEventListener('click', function() {

    	phone = document.getElementById('uPhoneNumber').value;
		estab = document.getElementById('nEst').value;

    	if (validation()) {
    		request();
       	}

    });	

    function validation(){

    	if (phone == "") {
    		message.innerHTML = "Insert the number phone";
    		phone.innerHTML = "";
    		message.style.display = 'block';
    		setTimeout(function() {
    			message.style.display = 'none';
    		}, 5000);
			return false;
		}else if (estab == 0) {
			message.innerHTML = "Select a Establishment";
    		estab.value = 0;
			message.style.display = 'block';
			setTimeout(function() {
    			message.style.display = 'none';
    		}, 5000);
			return false;
		}else{
			return true;
		}
    }


	var loading = document.getElementById('loading');
	var stopwatch = document.getElementById('stopWatch');
    var bodyForm = document.getElementById('formCheck');
    var buttonStop = document.getElementById('bCheckOut');
    var chronometer;
    var initialTime;
    var idService;

    function request(){
    	var data = {
			UPhone: phone,
          	NEst: estab
		}
    	
      	loading.style.display = 'block';

      	axios.post("<?php echo site_url('checkIn') ?>", data
	  	).then(function(res) {

	  		if (res.data) {

	  			stopwatch.style.display = 'block';
	  			buttonStop.style.display = 'block';
	  			button.style.display = 'none';
	  			bodyForm.style.display = 'none';

	  			initialTime = Date.now();
	  			chronometer = setInterval('start()', 1000);

	  			idService = res.data;
	  			
	  			phone.innerHTML = "";
				estab.value = 0;

	  		}else{
	  			message.innerHTML = "Your name or your phone went wrong";
	  		}

	  	}).catch(function(err) {

	      	console.log(err);

	  	}).then(function() {

	      	loading.style.display = 'none';

	    });
    }

    var HH = 0;
	var mm = 0;
	var ss = 0;
	var difference = 0;
	var time = 0;
	var currentTime;
	var totalHours;

	function start(){
		currentTime = Date.now();
		difference = currentTime - initialTime;
		time = new Date(difference);

		HH = time.getUTCHours();
		mm = time.getUTCMinutes();
		ss = time.getUTCSeconds();

		var count = (HH<10 ?  "0"+HH : HH)+" : "+(mm<10 ? "0"+mm : mm)+" : "+(ss<10 ? "0"+ss : ss);

		document.getElementById('contStopWatch').innerHTML = count;

		totalHours = HH+":"+mm+":"+ss;
	}

	buttonStop.addEventListener('click', function(){

		stop();
		clearInterval(chronometer);
	});

	function stop(){
		var data = {
			NServ: idService,
			NTotal: totalHours
		}

		axios.post("<?php echo site_url('checkOut') ?>", data
		).then(function(res) {

			if(res.data){
				swal({
					text: "Check Out Successfully",
					icon: "success"
				});
			}else{
				swal({
					text: "Something went wrong",
					icon: "error"
				});
			}

		}).catch(function(err){
			console.log(err);
		});
	}

</script>