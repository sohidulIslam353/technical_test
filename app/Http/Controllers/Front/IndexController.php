<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use DB;
class IndexController extends Controller
{
    //root page
    public function index()
    {
        $category=DB::table('categories')->orderBy('category_name','ASC')->get();
        $brand=DB::table('brands')->where('front_page',1)->limit(24)->get();
        $bannerproduct=Product::where('status',1)->where('product_slider',1)->latest()->first();
        $featured=Product::where('status',1)->where('featured',1)->orderBy('id','DESC')->limit(16)->get();
        $todaydeal=Product::where('status',1)->where('today_deal',1)->orderBy('id','DESC')->limit(6)->get();
        $popular_product=Product::where('status',1)->orderBy('product_views','DESC')->limit(16)->get();
        $trendy_product=Product::where('status',1)->where('trendy',1)->orderBy('id','DESC')->limit(8)->get();
        $random_product=Product::where('status',1)->inRandomOrder()->limit(16)->get();
        $review=DB::table('wbreviews')->where('status',1)->orderBy('id','DESC')->limit(12)->get();
        //homepage category
        $home_category=DB::table('categories')->where('home_page',1)->orderBy('category_name','ASC')->get();


        $campaign=DB::table('campaigns')->where('status',1)->orderBy('id','DESC')->first();

        return view('frontend.index',compact('category','bannerproduct','featured','popular_product','trendy_product','home_category','brand','random_product','todaydeal','review','campaign'));
    }

    //singleproduct page calling method
    public function ProductDetails($slug)
    {
        $product=Product::where('slug',$slug)->first();
                 Product::where('slug',$slug)->increment('product_views');
        $related_product=DB::table('products')->where('subcategory_id',$product->subcategory_id)->orderBy('id','DESC')->take(10)->get();
        $review=Review::where('product_id',$product->id)->orderBy('id','DESC')->take(6)->get();

        

        return view('frontend.product.product_details',compact('product','related_product','review'));
    }

    //product quick view
    public function ProductQuickView($id)
    {
        $product=Product::where('id',$id)->first();
        return view('frontend.product.quick_view',compact('product'));
    }


    //categorywise product page
    public function categoryWiseProduct($id)
    {
        $category=DB::table('categories')->where('id',$id)->first();
        $subcategory=DB::table('subcategories')->where('category_id',$id)->get();
        $brand=DB::table('brands')->get();
        $products=DB::table('products')->where('category_id',$id)->paginate(60);
        $random_product=Product::where('status',1)->inRandomOrder()->limit(16)->get();
        return view('frontend.product.category_products',compact('subcategory','brand','products','random_product','category'));

    }

    //subcategorywise product
    public function SubcategoryWiseProduct($id)
    {
        $subcategory=DB::table('subcategories')->where('id',$id)->first();
        $childcategories=DB::table('childcategories')->where('subcategory_id',$id)->get();
        $brand=DB::table('brands')->get();
        $products=DB::table('products')->where('subcategory_id',$id)->paginate(60);
        $random_product=Product::where('status',1)->inRandomOrder()->limit(16)->get();
        return view('frontend.product.subcategory_product',compact('childcategories','brand','products','random_product','subcategory'));
    }

    //childcategory product
    public function ChildcategoryWiseProduct($id)
    {
        $childcategory=DB::table('childcategories')->where('id',$id)->first();
        $categories=DB::table('categories')->get();
        $brand=DB::table('brands')->get();
        $products=DB::table('products')->where('childcategory_id',$id)->paginate(60);
        $random_product=Product::where('status',1)->inRandomOrder()->limit(16)->get();
        return view('frontend.product.childcategory_product',compact('categories','brand','products','random_product','childcategory'));
    }

    //brandwise product
    public function BrandWiseProduct($id)
    {
        $brand=DB::table('brands')->where('id',$id)->first();
        $categories=DB::table('categories')->get();
        $brands=DB::table('brands')->get();
        $products=DB::table('products')->where('brand_id',$id)->paginate(60);
        $random_product=Product::where('status',1)->inRandomOrder()->limit(16)->get();
        return view('frontend.product.brandwise_product',compact('categories','brands','products','random_product','brand'));
    }

    //page view method
    public function ViewPage($page_slug)
    {
        $page=DB::table('pages')->where('page_slug',$page_slug)->first();
        return view('frontend.page',compact('page'));
    }

    //store newsletter
    public function storeNewsletter(Request $request)
    {
        $email=$request->email;
        $check=DB::table('newsletters')->where('email',$email)->first();
        if ($check) {
            return response()->json('Email Already Exist!');
        }else{
              $data=array();
              $data['email']=$request->email;
              DB::table('newsletters')->insert($data);
              return response()->json('Thanks for subscribe us!');  
        }
       

    }


    //__order tracking page
    public function OrderTracking()
    {
        return view('frontend.order_tracking');
    }
   
   
    //__check orer
    public function CheckOrder(Request $request)
    {
        $check=DB::table('orders')->where('order_id',$request->order_id)->first();
        if ($check) {
            $order=DB::table('orders')->where('order_id',$request->order_id)->first();
            $order_details=DB::table('order_details')->where('order_id',$order->id)->get();
            return view('frontend.order_details',compact('order','order_details'));
        }else{
            $notification=array('messege' => 'Invalid OrderID! Try again.', 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
    }

    //constact page
    public function Contact()
    {
        return view('frontend.contact');
    }

    //__blog page
    public function Blog()
    {
        return view('frontend.blog');
    }

    //__campaign products__//
    public function CampaignProduct($id)
    {
        $products=DB::table('campaign_product')->leftJoin('products','campaign_product.product_id','products.id')
                    ->select('products.name','products.code','products.thumbnail','products.slug','campaign_product.*')
                    ->where('campaign_product.campaign_id',$id)
                    ->paginate(32);          
        return view('frontend.campaign.product_list',compact('products'));
    }

    //__campaign product details__//
    public function CampaignProductDetails($slug)
    {
        $product=Product::where('slug',$slug)->first();
                 Product::where('slug',$slug)->increment('product_views');
        $product_price=DB::table('campaign_product')->where('product_id',$product->id)->first();         
        $related_product=DB::table('campaign_product')->leftJoin('products','campaign_product.product_id','products.id')
                    ->select('products.name','products.code','products.thumbnail','products.slug','campaign_product.*')
                    ->inRandomOrder(12)->get();
        $review=Review::where('product_id',$product->id)->orderBy('id','DESC')->take(6)->get();
        return view('frontend.campaign.product_details',compact('product','related_product','review','product_price'));

    }




    //for test exam
    public function test(Request $request)
    {
        
        $brands=DB::table('brands')->orderBy('brand_name','asc')->get();
        return view('test',compact('brands'));
    }

    //test view partial file append
    public function testView(Request $request)
    {
        
        // $products = DB::table('products as p')->join('product_attributes as pa','p.id','=','pa.product_id')->select('p.*','pa.size')->where('p.status', 1)->groupBy('p.id');

        //       if (isset($request->minimum_price) && isset($request->maximum_price)) {
        //           $products->whereBetween('p.price', [$request->minimum_price, $request->maximum_price]);
        //       }
        //       if (isset($request->brand)) {
        //           $products->whereIn('p.brand_id', $request->brand);
        //       }
        //       if (isset($request->cat)) {
        //           $products->whereIn('p.category_id', $request->cat);
        //       }
        //       if (isset($request->orderby)) {
        //         if ($request->orderby == "standardno") {
        //           $products->orderBy('p.id','desc');
        //         }
        //         if ($request->orderby == "istaknute") {
        //           $products->orderBy('p.featured','desc');
        //         }
        //         if ($request->orderby == "novi") {
        //           $products->orderBy('p.id','desc');
        //         }
        //         if ($request->orderby == "cijena1") {
        //           $products->orderBy('p.price','asc');
        //         }
        //         if ($request->orderby == "cijena2") {
        //           $products->orderBy('p.price','desc');
        //         }
        //         if (isset($request->size)) {
        //             $products->whereIn('pa.size', $request->size);
        //         }
        //         if (isset($request->color)) {
        //             $products->whereIn('pa.color', $request->color);
        //         }
        //       }
        //      $proizvodi = $products->paginate(15);
        //       return view('products',compact('proizvodi'));
        $product=Product::join('categories','products.category_id','categories.id')->select('products.*','categories.category_name');
            if ($request->sorting==1) {
                $product->orderBy('selling_price','asc');
            }
            if ($request->sorting==2) {
                $product->orderBy('selling_price','desc');
            }
            if ($request->search) {
                $product->where('name', 'LIKE', '%'.$request->search.'%');
            }

            //__availibility checking__//
            if ($request->instock==1 && $request->preorder=='' && $request->upcoming=='') {
                    $product->where('stock',1);
            }elseif ($request->instock=='' && $request->preorder==2 && $request->upcoming=='') {
                     $product->where('stock',2);
            }elseif ($request->instock=='' && $request->preorder=='' && $request->upcoming==3) {
                     $product->where('stock',3);
            }elseif ($request->instock==1 && $request->preorder==2 && $request->upcoming=='') {
                     $product->whereBetween('stock',[1,2]);
            }elseif ($request->instock==1 && $request->preorder=='' && $request->upcoming==3) {
                     $product->where('stock',1)->orWhere('stock',3);
            }elseif ($request->instock=='' && $request->preorder==2 && $request->upcoming==3) {
                     $product->where('stock',2)->orWhere('stock',3);
            }elseif ($request->instock==1 && $request->preorder==2 && $request->upcoming==3) {
                     
            }

            if (isset($request->minimum_price) && isset($request->maximum_price)) {
                   $product->whereBetween('selling_price', [$request->minimum_price, $request->maximum_price]);
               }

            if ($request->brand) {
                 $product->where('brand_id', $request->brand);
                //return $request->brand;
            }
        $products = $product->paginate(18);

        

        return view('test_view',compact('products'));

    }

    public function fetch_data(Request $request)
    {
        if($request->ajax())
         {
          $product=Product::join('categories','products.category_id','categories.id')->select('products.*','categories.category_name');
            if ($request->sorting==1) {
                $product->orderBy('selling_price','asc');
            }
            if ($request->sorting==2) {
                $product->orderBy('selling_price','desc');
            }
            if ($request->search) {
                $product->where('name', 'LIKE', '%'.$request->search.'%');
            }

            //__availibility checking__//
            if ($request->instock==1 && $request->preorder=='' && $request->upcoming=='') {
                    $product->where('stock',1);
            }elseif ($request->instock=='' && $request->preorder==2 && $request->upcoming=='') {
                     $product->where('stock',2);
            }elseif ($request->instock=='' && $request->preorder=='' && $request->upcoming==3) {
                     $product->where('stock',3);
            }elseif ($request->instock==1 && $request->preorder==2 && $request->upcoming=='') {
                     $product->whereBetween('stock',[1,2]);
            }elseif ($request->instock==1 && $request->preorder=='' && $request->upcoming==3) {
                     $product->where('stock',1)->orWhere('stock',3);
            }elseif ($request->instock=='' && $request->preorder==2 && $request->upcoming==3) {
                     $product->where('stock',2)->orWhere('stock',3);
            }elseif ($request->instock==1 && $request->preorder==2 && $request->upcoming==3) {
                     
            }

            if (isset($request->minimum_price) && isset($request->maximum_price)) {
                   $product->whereBetween('selling_price', [$request->minimum_price, $request->maximum_price]);
               }

            if ($request->brand) {
                $brand = $request->genres;
                $product->where('brand_id', $request->brand);
            }

          $products = $product->paginate(18);
          return view('paginate_view', compact('products'))->render();
         }    
    }


}
