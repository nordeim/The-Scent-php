<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('stock', '<=', 10)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'unread_messages' => ContactMessage::where('status', 'unread')->count()
        ];
        
        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();
            
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();
            
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->get();
            
        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'topProducts',
            'monthlyRevenue'
        ));
    }
    
    public function orders()
    {
        $orders = Order::with(['user', 'items.product', 'shippingAddress', 'billingAddress'])
            ->latest()
            ->paginate(20);
            
        return view('admin.orders.index', compact('orders'));
    }
    
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $order->status;
            $order->update(['status' => $request->status]);
            
            // Handle stock updates
            if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
            } elseif ($oldStatus === 'cancelled' && $request->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product->stock < $item->quantity) {
                        throw new \Exception("Insufficient stock for {$item->product->name}");
                    }
                    $item->product->decrement('stock', $item->quantity);
                }
            }
            
            // Send status update notification
            $order->user->notify(new OrderStatusUpdated($order));
            
            DB::commit();
            
            return back()->with('success', 'Order status updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function products()
    {
        $products = Product::with(['category', 'reviews'])
            ->latest()
            ->paginate(20);
            
        return view('admin.products.index', compact('products'));
    }
    
    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }
    
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048',
            'is_featured' => 'boolean',
            'is_customizable' => 'boolean',
            'customization_options' => 'array',
            'customization_options.*.name' => 'required|string',
            'customization_options.*.type' => 'required|in:color,scent,size,shape',
            'customization_options.*.price_adjustment' => 'required|numeric|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = $request->file('image')->store('products', 'public');
            
            // Create product
            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'image' => $imagePath,
                'is_featured' => $request->is_featured ?? false,
                'is_customizable' => $request->is_customizable ?? false
            ]);
            
            // Create customization options
            if ($request->has('customization_options')) {
                foreach ($request->customization_options as $option) {
                    $product->customizationOptions()->create($option);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error creating the product.');
        }
    }
    
    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }
    
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_customizable' => 'boolean',
            'customization_options' => 'array',
            'customization_options.*.name' => 'required|string',
            'customization_options.*.type' => 'required|in:color,scent,size,shape',
            'customization_options.*.price_adjustment' => 'required|numeric|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $product->update(['image' => $imagePath]);
            }
            
            // Update product
            $product->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'is_featured' => $request->is_featured ?? false,
                'is_customizable' => $request->is_customizable ?? false
            ]);
            
            // Update customization options
            if ($request->has('customization_options')) {
                $product->customizationOptions()->delete();
                foreach ($request->customization_options as $option) {
                    $product->customizationOptions()->create($option);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error updating the product.');
        }
    }
    
    public function deleteProduct(Product $product)
    {
        try {
            DB::beginTransaction();
            
            // Delete product image
            Storage::disk('public')->delete($product->image);
            
            // Delete product
            $product->delete();
            
            DB::commit();
            
            return back()->with('success', 'Product deleted successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error deleting the product.');
        }
    }
    
    public function messages()
    {
        $messages = ContactMessage::with('user')
            ->latest()
            ->paginate(20);
            
        return view('admin.messages.index', compact('messages'));
    }
    
    public function updateMessageStatus(Request $request, ContactMessage $message)
    {
        $request->validate([
            'status' => 'required|in:unread,read,replied,archived'
        ]);
        
        $message->update(['status' => $request->status]);
        
        return back()->with('success', 'Message status updated successfully.');
    }
    
    public function replyMessage(Request $request, ContactMessage $message)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Send reply email
            Mail::to($message->email)->send(new ContactReply($message, $request->reply));
            
            // Update message status
            $message->update(['status' => 'replied']);
            
            DB::commit();
            
            return back()->with('success', 'Reply sent successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error sending the reply.');
        }
    }
    
    public function deleteMessage(ContactMessage $message)
    {
        $message->delete();
        
        return back()->with('success', 'Message deleted successfully.');
    }
    
    public function users()
    {
        $users = User::withCount('orders')
            ->latest()
            ->paginate(20);
            
        return view('admin.users.index', compact('users'));
    }
    
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:customer,admin'
        ]);
        
        $user->update(['role' => $request->role]);
        
        return back()->with('success', 'User role updated successfully.');
    }
    
    public function deleteUser(User $user)
    {
        if ($user->orders()->exists()) {
            return back()->with('error', 'Cannot delete user with existing orders.');
        }
        
        $user->delete();
        
        return back()->with('success', 'User deleted successfully.');
    }
} 