@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('public/frontend') }}/plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/frontend') }}/styles/shop_styles.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/frontend') }}/styles/shop_responsive.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/frontend') }}/styles/range_slider.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<div class="shop">
		<div class="container">
			<div class="row">
				<div class="col-lg-3">
					<!-- Shop Sidebar -->
					<div class="shop_sidebar">
						<div class="sidebar_section">
						  <div class="sidebar_title">Search</div> 
							<ul class="sidebar_categories">
								<input type="text" class="form-control submitable" name="search" id="search" placeholder="product search">
							</ul>
						</div>
						<div class="sidebar_section filter_by_section">
							<div class="sidebar_title">Filter By</div>
							<div class="sidebar_subtitle">Brand</div>
							<ul class="">
								<li class="color">
									<input type="radio"  class="submitable zero"   value="0"> 
									All Brand 
								</li><br>
								@foreach($brands as $brand)
								<li class="color">
									<input type="radio" class="submitable brand"  id="brand" name="brand" value="{{ $brand->id }} "> 
									{{ $brand->brand_name }} 
								</li><br>

								@endforeach
							</ul>

							<div class="sidebar_subtitle">Price:  <h5 class="range-slider"></h5> </div>
							<div class="range-slider">
							  <span class="rangeValues"></span><br>
							  <input value="0" min="0" max="50000" step="100" type="range" class="submitable">
							  <input value="500000" min="100" max="500000" step="100" type="range" class="submitable">

							  <input type="hidden" name="minimum_price" id="minimum_price">
							  <input type="hidden" name="maximum_price" id="maximum_price">
							  <input type="hidden" name="brand_id" id="brand_id">

							</div>
						</div>
						<div class="sidebar_section">
							<div class="sidebar_subtitle  color_subtitle">Availability</div>
							<ul class="">
								<li class="color"><input type="checkbox" class="submitable" id="instock" name="stock" value=""> Instock </li><br>
								<li class="color"><input type="checkbox" class="submitable" id="preorder" name="stock" value=""> Preorder </li><br>
								<li class="color"><input type="checkbox" class="submitable" id="upcoming" name="stock" value=""> Upcoming </li><br>	
							</ul>
						</div>
					</div>
				</div>

				<div class="col-lg-9">
					
					<!-- Shop Content -->
					<div class="shop_bar clearfix">
		  				  <div class="shop_product_count"> All Products</div>
		  					<div class="shop_sorting">
		  						<span>Sort by:</span>
		  						<ul>
		  							<li>
		  								<select class="form-control submitable" id="sorting" name="sorting" style="min-width: 120px;">
		  									<option value="1">Low To High</option>
		  									<option value="2">High To Low</option>
		  								</select>
		  							</li>
		  						</ul>
		  					</div>
		  				</div>
					<div class="shop_content">
		                

						<!-- Shop Page Navigation -->

						

					</div>

				</div>
			</div>
		</div>
	</div>


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog  modal-lg  modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="quick_view_body">
   
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('public/frontend') }}/js/shop_custom.js"></script>

{{-- range slider script --}}
<script type="text/javascript">
	function getVals(){
	  // Get slider values
	  let parent = this.parentNode;
	  let slides = parent.getElementsByTagName("input");
	    let slide1 = parseFloat( slides[0].value );
	    let slide2 = parseFloat( slides[1].value );
	  // Neither slider will clip the other, so make sure we determine which is larger
	  if( slide1 > slide2 ){ let tmp = slide2; slide2 = slide1; slide1 = tmp; }
	  
	  let displayElement = parent.getElementsByClassName("rangeValues")[0];
	      displayElement.innerHTML = "{{ $setting->currency }}" + slide1 + " - {{ $setting->currency }}" + slide2;
	      $('#minimum_price').val(slide1);
	      $('#maximum_price').val(slide2);
	}

	window.onload = function(){
	  // Initialize Sliders
	  let sliderSections = document.getElementsByClassName("range-slider");
	      for( let x = 0; x < sliderSections.length; x++ ){
	        let sliders = sliderSections[x].getElementsByTagName("input");
	        for( let y = 0; y < sliders.length; y++ ){
	          if( sliders[y].type ==="range" ){
	            sliders[y].oninput = getVals;
	            // Manually trigger event first time to display values
	            sliders[y].oninput();
	          }
	        }
	      }
	}
</script>
<script type="text/javascript">
	//__brand wise filtering
        $(document).ready(function(){
	        $(".brand").change(function(){
	        	$("#brand_id").val($(this).val());
	        	$('.zero').prop('checked', false);
	           
	        });
	    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
    	//__instock checkbox check value pass 1
        $("#instock").click(function(){
            if($(this).prop("checked") == true){
                $("#instock").val(1);
            }
            else if($(this).prop("checked") == false){
                $("#instock").val('');
            }
        });
        //__preroder checkbox check value pass 1
        $("#preorder").click(function(){
            if($(this).prop("checked") == true){
                $("#preorder").val(2);
            }
            else if($(this).prop("checked") == false){
                $("#preorder").val('');
            }
        });
        //__upcoming checkbox check value pass 1
        $("#upcoming").click(function(){
            if($(this).prop("checked") == true){
                $("#upcoming").val(3);
            }
            else if($(this).prop("checked") == false){
                $("#upcoming").val('');
            }
        });

    });



	function filter_data(){
		

	    $('.shop_content').html('<div id="loading"></div>');
	    var sorting = $('#sorting').val();
	    var instock = $('#instock').val();
	    var preorder = $('#preorder').val();
	    var upcoming = $('#upcoming').val();
	    var search = $('#search').val();
	    if ($('.zero').is(':checked')) {
	    	var brand = 0;
	    	$('.brand').prop('checked', false);
	    }else{
	    	var brand = $('#brand_id').val();
	    }
	    
	  
		
	    var minimum_price = $('#minimum_price').val();
	    var maximum_price = $('#maximum_price').val();
	    $.ajax({
	            headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
	            url: "{{ url("/test-view") }}",
	            type: 'get',
	            data: {sorting:sorting, instock:instock, preorder:preorder, upcoming:upcoming,search:search,brand:brand,minimum_price:minimum_price,maximum_price:maximum_price},
	            success:function(data){
	                $('.shop_content').html(data);
	            }
	    });
	}


 	$(document).ready(function(e) {
   		filter_data();
 	});


//submitable class call for every change
  $(document).on('change','.submitable', function(){
     filter_data();
  });

</script>

@endsection