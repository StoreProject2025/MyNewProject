<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Category;

class ProductsController extends Controller {

  use ValidatesRequests;

  public function __construct()
    {
        $this->middleware('auth:web')->except(['list', 'shop']);
    }

  public function boughtProducts(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::find($request->product_id);
        $user = auth()->user();

        if ($user->credit < $product->price) {
            return redirect()->route('insufficient.credit');
        }

        $user->credit -= $product->price;
        $user->save();

        $product->stock -= 1;
        $product->save();

        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'total_price' => $product->price,
            'quantity' => 1,
            'purchased_at' => now()
        ]);

        return redirect()->route('purchases')->with('success', 'Product added to bought products list!');
    }

  public function show(Product $product)
    {
      if (!$product->is_active) {
          abort(404);
      }

      return view('products.show', compact('product'));
    }

  public function list(Request $request) {

    $query = Product::select("products.*");

    $query->when($request->keywords, 
    fn($q)=> $q->where("name", "like", "%$request->keywords%"));

    $query->when($request->min_price, 
    fn($q)=> $q->where("price", ">=", $request->min_price));
    
    $query->when($request->max_price, fn($q)=> 
    $q->where("price", "<=", $request->max_price));
    
    $query->when($request->order_by, 
    fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

    $products = $query->get();

    return view('products.list', compact('products'));
  }

  public function edit(Request $request, Product $product = null) {

    if(!auth()->user()) return redirect('/');

    $product = $product??new Product();
    $categories = Category::where('is_active', true)->get();

    return view('products.edit', compact('product', 'categories'));
  }

  public function save(Request $request, Product $product = null)
  {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0|max:100',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($product) {
        // Update existing product
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($validated);
    } else {
        // Create new product
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        $product = Product::create($validated);
    }

    return redirect()->route('products.index')->with('success', 'Product saved successfully');
  }

  public function delete(Request $request, Product $product) {

    if(!auth()->user()->hasPermissionTo('delete_products')) abort(401);

    $product->delete();

    return redirect()->route('products_list');
  }


  public function hold(Request $request, Product $product)
{
    if (!auth()->user()->hasPermissionTo('edit_products')) {
        abort(401);
    }
	$product->name= 'product not avaliable'; 
    $product->save();

    return redirect()->back()->with('success', 'Product has been hold');}

  public function index()
  {
      $products = Product::with('category')->paginate(10);
      return view('products.index', compact('products'));
  }

  public function create()
  {
      $categories = Category::where('is_active', true)->get();
      return view('products.create', compact('categories'));
  }

  public function store(Request $request)
  {
      $request->validate([
          'name' => 'required|string|max:255',
          'description' => 'required|string',
          'price' => 'required|numeric|min:0',
          'stock' => 'required|integer|min:0',
          'category_id' => 'required|exists:categories,id',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
          'is_active' => 'boolean'
      ]);

      $data = $request->all();
      $data['slug'] = Str::slug($request->name);
      $data['is_active'] = $request->boolean('is_active');
      
      if ($request->hasFile('image')) {
          $imagePath = $request->file('image')->store('products', 'public');
          $data['image'] = $imagePath;
      }

      Product::create($data);

      return redirect()->route('products.index')
          ->with('success', 'Product created successfully.');
  }

  public function update(Request $request, Product $product)
  {
    \Log::info('Update request received', [
        'product_slug' => $product->slug,
        'request_method' => $request->method(),
        'request_data' => $request->all()
    ]);

    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_flash_sale' => 'boolean',
        ]);

        \Log::info('Validation passed', ['validated_data' => $validated]);

        // Handle boolean fields
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_flash_sale'] = $request->boolean('is_flash_sale');

        \Log::info('Boolean fields processed', [
            'is_active' => $validated['is_active'],
            'is_featured' => $validated['is_featured'],
            'is_flash_sale' => $validated['is_flash_sale']
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
            \Log::info('Image uploaded', ['image_path' => $validated['image']]);
        }

        // Update the product
        $updated = $product->update($validated);
        \Log::info('Product update result', ['success' => $updated]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    } catch (\Exception $e) {
        \Log::error('Error updating product', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()
            ->withInput()
            ->with('error', 'Error updating product: ' . $e->getMessage());
    }
  }

  public function destroy(Product $product)
  {
      if ($product->image) {
          Storage::disk('public')->delete($product->image);
      }
      
      $product->delete();

      return redirect()->route('products.index')
          ->with('success', 'Product deleted successfully.');
  }

  public function shop(Request $request)
  {
      \Log::info('Shop request received', [
          'filters' => $request->all()
      ]);

      // First, let's get all active products without filters to verify data
      $allProducts = Product::where('is_active', true)->count();
      \Log::info('Total active products', ['count' => $allProducts]);

      // Debug categories
      $categories = Category::where('is_active', true)->get();
      \Log::info('Available categories', [
          'categories' => $categories->map(function($category) {
              return [
                  'id' => $category->id,
                  'name' => $category->name,
                  'product_count' => Product::where('category_id', $category->id)
                      ->where('is_active', true)
                      ->count()
              ];
          })
      ]);

      $query = Product::where('is_active', true);

      // Filter by price range
      if ($request->filled('min_price')) {
          $minPrice = floatval($request->min_price);
          $query->where('price', '>=', $minPrice);
          \Log::info('Applied min price filter', ['min_price' => $minPrice]);
      }
      if ($request->filled('max_price')) {
          $maxPrice = floatval($request->max_price);
          $query->where('price', '<=', $maxPrice);
          \Log::info('Applied max price filter', ['max_price' => $maxPrice]);
      }

      // Filter by categories (including subcategories)
      if ($request->filled('categories')) {
          $categoryIds = array_map('intval', $request->categories);
          
          // Get all subcategories for the selected categories
          $subcategoryIds = Category::whereIn('parent_id', $categoryIds)
              ->where('is_active', true)
              ->pluck('id')
              ->toArray();
          
          // Combine parent and subcategory IDs
          $allCategoryIds = array_merge($categoryIds, $subcategoryIds);
          
          $query->whereIn('category_id', $allCategoryIds);
          \Log::info('Applied categories filter', [
              'parent_categories' => $categoryIds,
              'subcategories' => $subcategoryIds,
              'all_categories' => $allCategoryIds
          ]);
      }

      // Sort products
      switch ($request->sort) {
          case 'price_asc':
              $query->orderBy('price', 'asc');
              \Log::info('Applied sorting', ['sort' => 'price_asc']);
              break;
          case 'price_desc':
              $query->orderBy('price', 'desc');
              \Log::info('Applied sorting', ['sort' => 'price_desc']);
              break;
          case 'name_asc':
              $query->orderBy('name', 'asc');
              \Log::info('Applied sorting', ['sort' => 'name_asc']);
              break;
          case 'name_desc':
              $query->orderBy('name', 'desc');
              \Log::info('Applied sorting', ['sort' => 'name_desc']);
              break;
          default:
              $query->latest();
              \Log::info('Applied default sorting (latest)');
      }

      // Get the SQL query for debugging
      $sql = $query->toSql();
      $bindings = $query->getBindings();
      \Log::info('SQL Query', [
          'sql' => $sql,
          'bindings' => $bindings
      ]);

      $products = $query->paginate(12);

      \Log::info('Query results', [
          'total_products' => $products->total(),
          'current_page' => $products->currentPage(),
          'per_page' => $products->perPage(),
          'has_products' => $products->isNotEmpty()
      ]);

      // Add debug information to the view
      $debug = [
          'total_active_products' => $allProducts,
          'applied_filters' => [
              'min_price' => $request->min_price,
              'max_price' => $request->max_price,
              'categories' => $request->categories,
              'sort' => $request->sort
          ],
          'sql_query' => $sql,
          'sql_bindings' => $bindings
      ];

      return view('products.shop', compact('products', 'categories', 'debug'));
  }
}