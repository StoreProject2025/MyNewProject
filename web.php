<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\WishlistController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Web\DashboardController;
<<<<<<< HEAD
use App\Http\Controllers\Web\DeliveryController;
use App\Http\Middleware\CheckDeliveryRole;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\PermissionController;
=======
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::post('/logout', [UsersController::class, 'doLogout'])->name('logout');
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');
Route::post('users/update_credit/{user}', [UsersController::class, 'updateCredit'])->name('update_credit');
Route::get('verify', [UsersController::class, 'verify'])->name('verify');

Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
Route::post('/users/store', [UsersController::class, 'store'])->name('users.store');
Route::post('/update-credit', [UsersController::class, 'updateCredit'])->name('update.credit');
Route::post('/add-credit', [UsersController::class, 'addCredit'])->name('add.credit');
Route::get('/purchases', [UsersController::class, 'purchases'])->name('purchases');

// Cart Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{slug}', [CartController::class, 'add'])->name('cart.add');
<<<<<<< HEAD
    Route::delete('/cart/remove/{slug}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{slug}', [CartController::class, 'update'])->name('cart.update');
=======
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
});

// Order Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
<<<<<<< HEAD
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
=======
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
});

// Product Routes
Route::get('/products', [ProductsController::class, 'shop'])->name('products.shop');
<<<<<<< HEAD
Route::get('/products/manage', [ProductsController::class, 'index'])->name('products.index')->middleware('auth')->middleware('can:manage_products');
Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create')->middleware('auth')->middleware('can:add_product');
Route::post('/products', [ProductsController::class, 'store'])->name('products.store')->middleware('auth')->middleware('can:add_product');
Route::get('/products/insufficient_credit', [ProductsController::class, 'show'])->name('insufficient.credit');
Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit')->middleware('auth')->middleware('can:edit_product');
Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update')->middleware('auth')->middleware('can:edit_product');
Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.destroy')->middleware('auth')->middleware('can:delete_product');
Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->name('products.show');

// Product Discount Routes
Route::get('/products/{product}/discount', [ProductsController::class, 'editDiscount'])->name('products.discount.edit')->middleware(['auth', 'can:manage_discounts']);
Route::put('/products/{product}/discount', [ProductsController::class, 'updateDiscount'])->name('products.discount.update')->middleware(['auth', 'can:manage_discounts']);

=======
Route::get('/products/manage', [ProductsController::class, 'index'])->name('products.index')->middleware('can:manage_products');
Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create')->middleware('can:add_products');
Route::post('/products', [ProductsController::class, 'store'])->name('products.store')->middleware('can:add_products');
Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit')->middleware('can:edit_products');
Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update')->middleware('can:edit_products');
Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.destroy')->middleware('can:delete_products');
Route::get('/products/insufficient_credit', [ProductsController::class, 'show'])->name('insufficient.credit');
Route::get('/products/{product:slug}', [ProductsController::class, 'show'])->name('products.show');

>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::find($id);
    
    if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        throw new \Illuminate\Auth\Access\AuthorizationException;
    }

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('login')->with('info', 'Email already verified.');
    }

    if ($user->markEmailAsVerified()) {
        return redirect()->route('login')->with('success', 'Email verified successfully! You can now login.');
    }

    return redirect()->route('login')->with('error', 'Email verification failed.');
})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent!');
})->middleware(['throttle:6,1'])->name('verification.send');

Route::get('/', function () {
    $bestSellers = \App\Models\Product::where('is_active', 1)->take(8)->get();
    $categories = \App\Models\Category::where('is_active', 1)->whereNull('parent_id')->get();
    return view('welcome', compact('bestSellers', 'categories'));
})->name('home');

Route::get('/welcome', function () {
    $bestSellers = \App\Models\Product::where('is_active', 1)->take(8)->get();
    $categories = \App\Models\Category::where('is_active', 1)->whereNull('parent_id')->get();
    return view('welcome', compact('bestSellers', 'categories'));
})->name('welcome');

Route::get('/test', function () {
    return view('test');
});

Route::get('/test-email', [UsersController::class, 'testEmail'])->name('test.email');

// Social Login Routes
Route::get('auth/google', [UsersController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [UsersController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('auth/facebook', [UsersController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [UsersController::class, 'handleFacebookCallback'])->name('facebook.callback');

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
<<<<<<< HEAD
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware(['auth', 'role:Admin']);
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')->middleware(['auth', 'role:Admin']);
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware(['auth', 'role:Admin']);
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware(['auth', 'role:Admin']);
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware(['auth', 'role:Admin']);
=======
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
Route::get('/categories/{category}/subcategories', [CategoryController::class, 'subcategories'])->name('categories.subcategories');

// Wishlist Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/{user?}', [UsersController::class, 'profile'])->name('profile');
    Route::get('/profile/edit/{user?}', [UsersController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update/{user}', [UsersController::class, 'save'])->name('profile.update');
    Route::get('/profile/password/{user?}', [UsersController::class, 'editPassword'])->name('profile.password');
    Route::post('/profile/password/{user}', [UsersController::class, 'savePassword'])->name('profile.password.update');
<<<<<<< HEAD
=======
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Static Pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// Language Switch Route
Route::get('language/{lang}', [LanguageController::class, 'switchLang'])->name('language.switch');

// Temporary route to assign Admin role
Route::get('/assign-admin', function() {
    try {
        // Clear cache first
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $user = \App\Models\User::where('email', 'hanenmahmoud0@gmail.com')->first();
        if ($user) {
            // Remove any existing roles
            $user->roles()->detach();
            
            // Assign Admin role
            $user->assignRole('Admin');
            
            // Update is_admin flag
            $user->is_admin = true;
            $user->save();
            
            // Clear cache again
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            return 'Admin role assigned successfully! Please refresh your profile page.';
        }
        return 'User not found';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/deals', function () {
    return view('deals');
<<<<<<< HEAD
})->name('deals');

// Temporary route to check user permissions
Route::get('/check-user-permissions', function () {
    if (!auth()->check()) {
        return 'Not logged in';
    }
    
    $user = auth()->user();
    $hasPermission = $user->hasPermissionTo('manage_discounts');
    
    return [
        'username' => $user->name,
        'roles' => $user->getRoleNames(),
        'has_manage_discounts_permission' => $hasPermission,
        'all_permissions' => $user->getAllPermissions()->pluck('name')
    ];
});

// Temporary route to debug cart
Route::get('/debug-cart', function () {
    if (!auth()->check()) {
        return 'لم يتم تسجيل الدخول';
    }
    
    $user = auth()->user();
    $cartItems = $user->cart()->with('product')->get();
    
    return [
        'user_id' => $user->id,
        'username' => $user->name,
        'cart_count' => $cartItems->count(),
        'cart_items' => $cartItems->map(function($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'created_at' => $item->created_at
            ];
        })
    ];
});

// Temporary route to clear cart
Route::get('/debug-clear-cart', function () {
    if (!auth()->check()) {
        return 'لم يتم تسجيل الدخول';
    }
    
    $user = auth()->user();
    $count = $user->cart()->count();
    $user->cart()->delete();
    
    return [
        'status' => 'تم مسح سلة التسوق',
        'items_removed' => $count
    ];
});

// Delivery Management Routes
Route::middleware(['auth', CheckDeliveryRole::class])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/', [DeliveryController::class, 'index'])->name('index');
    Route::get('/orders/{order}', [DeliveryController::class, 'show'])->name('show');
    Route::post('/orders/{order}/status', [DeliveryController::class, 'updateStatus'])->name('update-status');
    Route::post('/orders/{order}/collect-cash', [DeliveryController::class, 'collectCash'])->name('collect-cash');
});

// User Verification Routes (Admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users/{user}/verify', [UsersController::class, 'verifyUserByAdmin'])->name('admin.verify.user');
    Route::get('/admin/users/{user}/unverify', [UsersController::class, 'unverifyUser'])->name('admin.unverify.user');
});

// Add Employee Permissions Route
Route::get('/assign-employee-permissions', function() {
    try {
        // Clear cache first
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Find the employee by email
        $employee = \App\Models\User::where('email', request('email'))->first();
        if (!$employee) {
            return 'Employee not found. Please check the email address.';
        }
        
        // Check if user is an employee
        if (!$employee->hasRole('Employee')) {
            return 'This user is not an Employee. Please assign the Employee role first.';
        }
        
        // List all permissions to check what is available
        $allPermissions = \Spatie\Permission\Models\Permission::pluck('name')->toArray();
        echo "Available permissions: " . implode(', ', $allPermissions) . "<br>";
        
        // Assign product-related permissions (both singular and plural forms)
        if (in_array('add_product', $allPermissions)) {
            $employee->givePermissionTo('add_product');
        }
        if (in_array('add_products', $allPermissions)) {
            $employee->givePermissionTo('add_products');
        }
        
        if (in_array('edit_product', $allPermissions)) {
            $employee->givePermissionTo('edit_product');
        }
        if (in_array('edit_products', $allPermissions)) {
            $employee->givePermissionTo('edit_products');
        }
        
        if (in_array('delete_product', $allPermissions)) {
            $employee->givePermissionTo('delete_product');
        }
        if (in_array('delete_products', $allPermissions)) {
            $employee->givePermissionTo('delete_products');
        }
        
        if (in_array('view_products', $allPermissions)) {
            $employee->givePermissionTo('view_products');
        }
        
        if (in_array('manage_products', $allPermissions)) {
            $employee->givePermissionTo('manage_products');
        }
        
        // Assign credit-related permissions
        if (in_array('add_customer_credit', $allPermissions)) {
            $employee->givePermissionTo('add_customer_credit');
        }
        
        // User permissions
        if (in_array('show_users', $allPermissions)) {
            $employee->givePermissionTo('show_users');
        }
        if (in_array('edit_users', $allPermissions)) {
            $employee->givePermissionTo('edit_users');
        }
        
        // Clear cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        return 'Permissions assigned successfully to employee: ' . $employee->name;
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Temporary route to assign Delivery Manager role
Route::get('/assign-delivery-manager', function() {
    try {
        // Clear cache first
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $email = request('email');
        if (!$email) {
            return 'Please provide an email parameter';
        }
        
        $user = \App\Models\User::where('email', $email)->first();
        if ($user) {
            // Assign Delivery Manager role
            $user->assignRole('Delivery Manager');
            
            // Clear cache again
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            return 'Delivery Manager role assigned successfully to: ' . $user->name;
        }
        return 'User not found';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Route to list all users
Route::get('/list-users', function() {
    $users = \App\Models\User::select('id', 'name', 'email')->get();
    
    $output = '<h1>Users List</h1>';
    $output .= '<table border="1" cellpadding="5">';
    $output .= '<tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>';
    
    foreach($users as $user) {
        $output .= '<tr>';
        $output .= '<td>' . $user->id . '</td>';
        $output .= '<td>' . $user->name . '</td>';
        $output .= '<td>' . $user->email . '</td>';
        $output .= '<td><a href="/assign-delivery-manager?email=' . $user->email . '">Make Delivery Manager</a></td>';
        $output .= '</tr>';
    }
    
    $output .= '</table>';
    return $output;
});

// Admin Routes
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Users Management
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

    // Roles Management
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Permissions Management
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Categories Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Products Management
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.destroy');

    // Orders Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});

// Temporary route to check admin status
Route::get('/check-admin-status', function() {
    try {
        $user = \App\Models\User::where('email', 'hanenmahmoud0@gmail.com')->first();
        if (!$user) {
            return 'User not found';
        }

        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'is_admin' => $user->is_admin,
            'has_admin_role' => $user->hasRole('Admin')
        ];
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Temporary route to reset and reassign Admin role
Route::get('/reset-admin', function() {
    try {
        // Clear cache first
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $user = \App\Models\User::where('email', 'hanenmahmoud0@gmail.com')->first();
        if (!$user) {
            return 'User not found';
        }

        // Remove all roles
        $user->roles()->detach();
        
        // Delete existing Admin role if exists
        \Spatie\Permission\Models\Role::where('name', 'Admin')->delete();
        
        // Create new Admin role
        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        
        // Assign Admin role
        $user->assignRole($adminRole);
        
        // Update is_admin flag
        $user->is_admin = true;
        $user->save();
        
        // Clear cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        return [
            'status' => 'success',
            'message' => 'Admin role has been reset and reassigned',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'is_admin' => $user->is_admin
            ]
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
});
=======
})->name('deals');
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
