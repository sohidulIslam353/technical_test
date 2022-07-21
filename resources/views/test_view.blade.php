		              

	<div class="product_grid row" id="product_shop">
		<div class="product_grid_border"></div>
			@include('paginate_view')  
	</div>

	

<script>
$(document).ready(function(){

 $(document).on('click', '.pagination a', function(event){
  event.preventDefault(); 
  var page = $(this).attr('href').split('page=')[1];
  fetch_data(page);
 });

 function fetch_data(page)
 {
 	var sorting = $('#sorting').val();
 	var instock = $('#instock').val();
 	var preorder = $('#preorder').val();
 	var upcoming = $('#upcoming').val();
 	var search = $('#search').val();
 	var brand = $('#brand').val();
 	var minimum_price = $('#minimum_price').val();
 	var maximum_price = $('#maximum_price').val();
  $.ajax({
  	url: "{{ url("/pagination/fetch_data?page=") }}"+page,
   data: {sorting:sorting, instock:instock, preorder:preorder, upcoming:upcoming,search:search,brand:brand,minimum_price:minimum_price,maximum_price:maximum_price},
   success:function(data)
   {
    $('#product_shop').html(data);
   }
  });
 }
 
});
</script>