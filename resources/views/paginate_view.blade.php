@foreach($products as $row)
			<div class="product_item is_new col-lg-2">
				<div class="product_border"></div>
				<div class="product_image d-flex flex-column align-items-center justify-content-center"><img src="{{ asset('public/files/product/'.$row->thumbnail) }}" alt=""></div>
				<div class="product_content">
					{{-- @if($row->discount_price==NULL)
					 <div class="product_price">{{ $setting->currency }}{{ $row->selling_price }}</div>
					@else
					 <div class="product_price">{{ $setting->currency }}{{ $row->discount_price }}<span>{{ $setting->currency }}{{ $row->selling_price }}</span></div>
					@endif --}}
					<div class="product_price">{{ $setting->currency }}{{ $row->selling_price }}</div>
					<div class="product_name"><div><a href="{{-- {{ route('product.details',$row->slug) }} --}}" tabindex="0">{{ $row->name }}</a></div></div>
				</div>
				{{-- <a href="{{ route('add.wishlist',$row->id) }}">
				  <div class="product_fav"><i class="fas fa-heart"></i></div>
				</a> --}}
				<ul class="product_marks">
					{{-- <li class="product_mark product_new quick_view" id="{{ $row->id }}" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-eye"></i></li> --}}
					@if($row->stock==1)
					<span class="badge badge-success">Instock</span>
					@elseif($row->stock==2)
					<span class="badge badge-primary">Preorder</span>
					@else
					<span class="badge badge-danger">Upcoming</span>
					@endif
				</ul>
			</div>
		@endforeach

    <div class="shop_page_nav d-flex flex-row">
		<ul class="page_nav d-flex flex-row">
			{{ $products->links() }}
		</ul>
	</div>
