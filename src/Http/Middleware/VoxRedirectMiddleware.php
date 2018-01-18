<?php

namespace VoxRedirects\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Schema;
use VoxRedirects\Models\VoxRedirect;

class VoxRedirectMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Schema::hasTable('vox_redirects')){
            $path = $request->path();
            $redirect = VoxRedirect::where('from', '=', $path)->first();
            if(isset($redirect->id)){
                return redirect($redirect->to, $redirect->type);
            }
        }
        return $next($request);
    }
}
