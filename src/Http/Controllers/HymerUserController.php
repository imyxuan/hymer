<?php

namespace IMyxuan\Hymer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use IMyxuan\Hymer\Facades\Hymer;

class HymerUserController extends HymerBaseController
{
    public function profile(Request $request)
    {
        $route = '';
        $dataType = Hymer::model('DataType')->where('model_name', Auth::guard(app('HymerGuard'))->getProvider()->getModel())->first();
        if (!$dataType && app('HymerGuard') == 'web') {
            $route = route('hymer.users.edit', Auth::user()->getKey());
        } elseif ($dataType) {
            $route = route('hymer.'.$dataType->slug.'.edit', Auth::user()->getKey());
        }

        return Hymer::view('hymer::profile', compact('route'));
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        if (Auth::user()->getKey() == $id) {
            $request->merge([
                'role_id'                              => Auth::user()->role_id,
                'user_belongstomany_role_relationship' => Auth::user()->roles->pluck('id')->toArray(),
            ]);
        }

        return parent::update($request, $id);
    }
}
