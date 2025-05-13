<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\CouponController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Auth\UserRegisterController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\ChatbotController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\WishlistController;
use App\Http\Controllers\Public\ProductController as P;
use App\Http\Controllers\Public\ReviewController as UserReviewController;
use Illuminate\Support\Facades\Mail;

// Public routes

Route::post('/pc-recommendation', [ChatBotController::class, 'recommend']);
Route::get('/chatbot', [ChatbotController::class, 'showChatInterface'])->name('chatbot.interface');
Route::post('/chatbot/message', [ChatbotController::class, 'processMessage'])->name('chatbot.message');
Route::get('/generate-slug', function(Request $request) {
    return response()->json(['slug' => Str::slug($request->text)]);
});
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [P::class, 'index'])->name('p.index');
Route::get('/products/{slug}', [P::class, 'show'])->name('p.show');
Route::get('/category/{slug}', [P::class, 'category'])->name('products.category');


// Guest routes (unauthenticated users)
Route::middleware('guest')->group(function () {
    // User authentication
    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserLoginController::class, 'login'])->name('user.login');
    Route::get('/register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [UserRegisterController::class, 'register']);


});
// Admin authentication
Route::prefix('admin')->middleware(['guest:admin'])->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('adminn.login');
});


// Authenticated user routes
Route::middleware('auth')->group(function () {



    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');
    Route::post('/', [UserReviewController::class, 'store'])->name('reviews.store');
    // Cart
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/clear', [WishlistController::class, 'clearAll'])->name('wishlist.clear');
    Route::post('/wishlist/{wishlist}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/my-orders/{order}', [ProfileController::class, 'show'])->name('orders.user.show');


    // Checkout

    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success'); 
    Route::get('/checkout/stripe/success/{order}', [CheckoutController::class, 'stripeSuccess'])->name('checkout.stripe.success');
    Route::get('/checkout/stripe/cancel/{order}', [CheckoutController::class, 'stripeCancel'])->name('checkout.stripe.cancel');


    Route::post('/buy-now', [CartController::class, 'buyNow'])->name('cart.buyNow');
    //////////////////

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

});

// Admin routes
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::delete('/products/{product}/images/{image}', [ImageController::class, 'destroyImage'])
        ->name('products.image.delete');
    // Make sure your route looks like this:

    // Resources
    Route::resource('/products', ProductController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/admins', AdminController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/brands', BrandController::class);
    Route::resource('/shipping-methods', ShippingMethodController::class);




    // Orders
    Route::resource('/orders', OrderController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Reviews
    Route::resource('/reviews', ReviewController::class)->only(['index', 'show', 'destroy']);
    Route::patch('/reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
});
// // In a test route or controller:
// Mail::raw('Test email from Laravel app', function($message) {
//     $message->to('test@example.com')
//             ->subject('Test Email');
// });
