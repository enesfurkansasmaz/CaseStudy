<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiscountRule;

class DiscountRuleController extends Controller
{
    public function index()
    {
        return DiscountRule::with('parameters')->get();
    }

    public function store(Request $request)
    {
        $rule = DiscountRule::create($request->only(['name', 'type']));

        $rule->parameters()->createMany($request->parameters);

        return response()->json($rule->load('parameters'), 200);
    }

    public function show($id)
    {
        return DiscountRule::with('parameters')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $rule = DiscountRule::findOrFail($id);
        $rule->update($request->only(['name', 'type']));

        $rule->parameters()->delete();
        $rule->parameters()->createMany($request->parameters);

        return response()->json($rule->load('parameters'));
    }

    public function destroy($id)
    {
        $rule = DiscountRule::findOrFail($id);
        $rule->delete();

        return response()->json(null, 200);
    }

}
