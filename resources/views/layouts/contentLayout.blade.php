<!doctype html>
<html lang="en">
  <head>
  	<title>LinkTree</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>

  </head>
  <body>

		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
	          <i class="fa fa-bars"></i>
	          <span class="sr-only">Toggle Menu</span>
	        </button>
        </div>
	  		<h1><a href="{{route('dashboard')}}" class="logo">LinkTree</a></h1>
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="{{route('dashboard')}}"><span class="fa fa-home mr-3"></span> Dashboard</a>
          </li>
          <li>
            <a href="{{route('referralTrack')}}"><span class="fa fa-bar-chart mr-3"></span> Referral Track</a>
          </li>
          <li>
            <a href="#" class="trash"><span class="fa fa-trash mr-3"></span> Delete Account</a>
          </li>
          <li>
              <a href="{{route('logout')}}"><span class="fa fa-sign-out mr-3"></span> Logout</a>
          </li>
        </ul>

    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5 pt-5">
        @yield('content-section')
      </div>
		</div>

    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>
    <script src="{{asset('assets/js/popper.js')}}"></script>

    <script>
        $(document).ready(function(){
            $(".trash").click(function(){

                $.ajax({
                    url:"{{route('deleteAccount')}}",
                    type:"GET",
                    success:function(responce){
                        if(responce.success){
                            location.reload();
                        }else{
                            alert(responce.msg);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
        // This block handles any failure in the AJAX request
        console.log('Error: ', textStatus, errorThrown); // Log the error details for debugging
        alert('Something went wrong! Please try again.'); // Display a generic error message
    }


                })

            })
        })
    </script>

  </body>
</html>
