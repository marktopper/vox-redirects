<?php

namespace VoxRedirects\Http\Controllers;

use Illuminate\Http\Request;
use VoxRedirects\Models\VoyagerRedirect;

class VoxRedirectController extends \App\Http\Controllers\Controller
{

    public function browse(Request $request, VoxRedirect $redirects){
    	$filter = $request->filter;
    	$sorting = $request->sorting;
    	if(isset($request->filter)){
    		$sorting = isset($sorting) ? $sorting : 'asc';
    		$redirects = $redirects->orderBy($filter, $sorting);
    	}
    	$redirects = $redirects->paginate(20);
    	return view('vox.redirects::browse', compact('redirects', 'filter', 'sorting'));
    }

    public function edit($id){
    	$redirect = VoxRedirect::find($id);
    	return view('vox.redirects::edit-add', compact('redirect'));
    }

    public function edit_post(Request $request){
    	$redirect = VoxRedirect::find($request->id);
    	$redirect->from = trim(trim($request->from), '/');
    	$redirect->to = trim(trim($request->to), '/');
    	$redirect->type = $request->type;
    	$redirect->save();
    	return redirect()->back()->with(['message' => __('Successfully Updated Redirect'), 'alert-type' => 'success']);
    }

    public function add(){
    	return view('vox.redirects::edit-add');
    }

    public function add_post(Request $request){
    	$redirect = new VoxRedirect;
    	$redirect->from = trim(trim($request->from), '/');
    	$redirect->to = trim(trim($request->to), '/');
    	$redirect->type = $request->type;
    	$redirect->save();
    	return redirect()->route('vox.redirects.edit', $redirect->id)->with(['message' => __('Successfully Created Redirect'), 'alert-type' => 'success']);
    }

    public function delete(Request $request){
        $id = $request->id;
        $data = VoxRedirect::destroy($id) ? ['message' => __('Successfully Deleted Redirect'), 'alert-type' => 'success'] : ['message' => __('Sorry it appears there was a problem deleting this redirect'), 'alert-type' => 'error'];
        return redirect()->route("vox.redirects")->with($data);
    }
}
