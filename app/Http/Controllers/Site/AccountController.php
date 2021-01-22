<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AccountController extends Controller
{
    public function show(User $user)
    {
       

        return view('site.pages.account.show',compact('user'));
    }

    public function edit(Request $request , User $user)
    {
       
        $user=User::findOrFail($user->id);
   

        return view('site.pages.account.edit',compact('user'));
    }
    public function update(Request $request, User $user)
    {
        request()->validate([
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            
            
        ]);
        $user = User::findOrFail($user->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        //$user->email = $request->email;
        $user->save();

        return back()->with(['status' => 'success', 'message' => 'Account updated successfully.']);
    }
    public function getOrders()
    {
        $orders = auth()->user()->orders;

        return view('site.pages.account.orders', compact('orders'));
    }
}

