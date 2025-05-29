<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Traits\HasRoles;

class UsersController extends Controller {

	use ValidatesRequests, HasRoles;

    public function purchases()
    {
        $user = auth()->user();
        $purchases = Purchase::where('user_id', $user->id)->with('product')->get();

        return view('products.bought_products_list', compact('purchases'));
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.redirect'))
                ->user();
            
            // Check if user exists
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                    'is_admin' => false,
                ]);

                // تعيين دور Customer افتراضياً
                $user->assignRole('Customer');

                // Send verification email
                $user->sendEmailVerificationNotification();
            }

            // Login user
            Auth::login($user);

            return redirect()->intended('/');
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('login')->with('error', 'Something went wrong with Google login');
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            
            $finduser = User::where('facebook_id', $user->id)->first();
            
            if($finduser){
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id'=> $user->id,
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                    'is_admin' => false,
                ]);
                
                // تعيين دور Customer افتراضياً
                $newUser->assignRole('Customer');
                
                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }
    }



















     
    public function insufficientCredit()
    {
        return view('users.insufficient-credit');
    }

    // public function updateCredit(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:users,id',
    //         'credit' => 'required|numeric|min:0',
    //     ]);

    //     $user = User::find($request->id);
    //     $user->credit = $request->credit;
    //     $user->save();

    //     return back()->with('success', 'Credit updated successfully!');
    // }

    public function updateCredit(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->hasPermissionTo('update_credit')) {
            abort(401);
        }
    
        $query = User::query();
    
        if (auth()->user()->hasRole('Employee')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Customer');
            });
        }
        $query->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'Admin');
        });
    
        $query->when(
            $request->keywords,
            fn($q) => $q->where("name", "like", "%$request->keywords%")
        );
    
        $users = $query->get();
        return view('users.update_credit', compact('users')); 
    }

    public function addCredit(Request $request)
    {
        if (!auth()->user()->hasRole('Admin') && !auth()->user()->hasPermissionTo('add_customer_credit')) {
            abort(401);
        }
    
        $request->validate([
            'id' => 'required|exists:users,id',
            'credit' => 'required|numeric|min:0',
        ]);
    
        $customer = User::find($request->id);
        if (!$customer->hasRole('Customer')) {
            return back()->with('error', 'Can only add credit to customers!');
        }
    
        $customer->credit += $request->credit;
        $customer->save();
    
        return back()->with('success', 'Credit added successfully!');
    }

    public function create()
    {

        return view('users.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ], [
            'email.unique' => 'The email has already been taken.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole("Employee");

        return redirect()->route('users')->with('success', 'User created successfully!');
    }

    

    public function list(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->hasPermissionTo('show_users')) {
            abort(401);
        }
    
        $query = User::query();
    
        if (auth()->user()->hasRole('Employee')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Customer');
            });
        }
        $query->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'Admin');
        });
    
        $query->when(
            $request->keywords,
            fn($q) => $q->where("name", "like", "%$request->keywords%")
        );
    
        $users = $query->get();
        return view('users.list', compact('users')); 
    }
	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        $user->assignRole('Customer');

        // Send verification email
        $user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please check your Gmail to verify your account.');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in.',
                ])->withInput();
            }

            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function doLogout(Request $request) {
    	Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {
        // إذا لم يكن المستخدم مسجل الدخول، قم بتوجيهه لصفحة تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = $user ?? auth()->user();
        
        // التحقق من الصلاحيات
        if(auth()->id() != $user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) {
                abort(401);
            }
        }

        // Get all permissions (direct and via roles)
        $permissions = collect();
        
        // Get direct permissions
        $permissions = $permissions->concat($user->getDirectPermissions());
        
        // Get permissions from roles
            foreach($user->roles as $role) {
                    $permissions = $permissions->concat($role->permissions);
        }

        // Remove duplicates and sort by name
        $permissions = $permissions->unique('id')->sortBy('name');

        // Clear cache to ensure fresh role/permission data
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null) {
        $user = $user ?? auth()->user();
        if(auth()->id() != $user->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }
    
        // Get all roles
        $roles = Role::all()->map(function($role) use ($user) {
            $role->taken = $user->hasRole($role->name);
            return $role;
        });

        // Get all permissions
        $permissions = Permission::all()->map(function($permission) use ($user) {
            $permission->taken = $user->hasDirectPermission($permission->name);
            return $permission;
        });

        // Clear cache to ensure fresh role/permission data
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }



    public function save(Request $request, User $user) {

        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $user->name = $request->name;
        $user->save();

        if(auth()->user()->hasPermissionTo('admin_users')) {

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            Artisan::call('cache:clear');
        }

        //$user->syncRoles([1]);
        //Artisan::call('cache:clear');

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function delete(Request $request, User $user) {
        if(!auth()->user()->hasRole('Admin')) abort(401);

        $user->delete();
        return redirect()->route('users')->with('success', 'User deleted successfully!');
    }

    public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function verify(Request $request)
    {
        try {
            $user = User::find($request->id);
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Invalid verification link.');
            }

            if ($user->hasVerifiedEmail()) {
                return redirect()->route('login')->with('info', 'Email already verified.');
            }

            // Check if the user is authorized to verify their email
            if (!hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
                return redirect()->route('login')->with('error', 'Invalid verification link.');
            }

            if ($user->markEmailAsVerified()) {
                return redirect()->route('login')->with('success', 'Email verified successfully! You can now login.');
            }

            return redirect()->route('login')->with('error', 'Email verification failed.');
        } catch (\Exception $e) {
            \Log::error('Email verification error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }
    }

    public function testEmail()
    {
        $user = Auth::user();
        try {
            Mail::to($user->email)->send(new VerificationEmail($user, url('/')));
            return 'Test email sent to ' . $user->email;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Admin verification of user accounts
     */
    public function verifyUserByAdmin(User $user)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->hasRole('Admin')) {
            return redirect()->back()->with('error', 'You do not have permission to verify users.');
        }
        
        // Set email verification timestamp if not already verified
        if (!$user->hasVerifiedEmail()) {
            $user->email_verified_at = now();
            $user->save();
            
            return redirect()->back()->with('success', 'User has been verified successfully.');
        }
        
        return redirect()->back()->with('info', 'This user is already verified.');
    }
    
    /**
     * Unverify a user account (Admin only)
     */
    public function unverifyUser(User $user)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->hasRole('Admin')) {
            return redirect()->back()->with('error', 'You do not have permission to unverify users.');
        }
        
        // Remove email verification timestamp
        $user->email_verified_at = null;
        $user->save();
        
        return redirect()->back()->with('success', 'User verification has been removed.');
    }
} 