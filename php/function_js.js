	$('.myBtn2').hide();	
	let mybutton = document.getElementById("myBtn");
	window.onscroll = function() {scrollFunction()};
	function scrollFunction() {
	  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		mybutton.style.display = "block";
	  } else {
		mybutton.style.display = "none";
	  }
	}	
	function topFunction() {
	  document.body.scrollTop = 0;
	  document.documentElement.scrollTop = 0;
	}
	
	function searchdata(){
		$.post('apps/search_eqm.php',{
            sdt: $('#ssearch').val()            
          },function(data){
            $('#mainshow').html(data);
          });
		
	}
	function search_data(x){
		//alert(x);
		$('#mainshow').load('app/search.php');
	}

	var i = 0;
	var txt = 'UPMS';
	var speed = 75;
	function typeWriter() {
	  if (i < txt.length) {
		//document.getElementById("demo").innerHTML += txt.charAt(i);
		$('#demo').html($('#demo').html()+txt.charAt(i));		
		//$('#demo').html(function(){	return $('#demo').html()+txt.charAt(i);	});
		
		i++;
		setTimeout(typeWriter, speed);
	  }
	}
	typeWriter()
	
	
	var j = 0;
	var txt2 = 'UPMS';
	var speed2 = 150;
	function typeWriter2() {
	  if (j < txt2.length) {
		document.getElementById("demo2").innerHTML += txt2.charAt(j);
		
		j++;
		setTimeout(typeWriter2, speed2);
	  }
	}
	typeWriter2()
	
	