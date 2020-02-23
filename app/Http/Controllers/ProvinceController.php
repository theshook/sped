<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    protected $province;

    public function __construct(Province $province)--
    public function update(Request $request, Province $province)
    {
        $province->update(['name' => $request->name]);
        return redirect(route('provinces.index'))->with('success', 'Province successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province)
    {
        $province->delete();
        return redirect(route('provinces.index'))->with('success', 'Province successfully remove');
    }
}
