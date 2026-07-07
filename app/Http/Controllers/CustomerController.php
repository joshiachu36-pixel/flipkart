<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function showRegister()
    {
        return view('customer.register');
    }

    public function register(Request $request)
    {
            $request->validate([ 
        'name' => 'required|max:100',
        'email' => 'required|email|unique:customers,email',
        'mobile' => 'required|digits:10|unique:customers,mobile',
        'password' => 'required|min:8|confirmed',
        ]);  

         $customer = new Customer();

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->password = Hash::make($request->password);

        $customer->save();

        return redirect()
        ->route('customer.login')
        ->with('success', 'Registration completed successfully. Please login.');
    }

    public function showLogin()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $customer = Customer::where('email', $request->login)
            ->orWhere('mobile', $request->login)
            ->first();

        if (!$customer) {
            return back()
                ->withInput()
                ->with('error', 'Customer not found.');
        }

        if (!Hash::check($request->password, $customer->password)) {
            return back()
                ->withInput()
                ->with('error', 'Incorrect password.');
        }

        Session::put('customer_id', $customer->id);
        Session::put('customer_name', $customer->name);

        return redirect('/shop')
            ->with('success', 'Login successful.');
    }

    public function logout()
    {
        session()->forget('customer_id');
        session()->forget('customer_name');

        return redirect('/login')
            ->with('success', 'Logged out successfully.');
    }

    public function profile()
    {
        $customer = Customer::find(session('customer_id'));

        return view('customer.profile', compact('customer'));
    }
}