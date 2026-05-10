<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Association;
use Illuminate\Http\Request;

class MyAssociationsController extends Controller
{
    public function index(Request $request)
    {
        $query = Association::where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('association_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $associations = $query->orderBy('created_at', 'desc')->get();

        // Stats
        $totalAssociations = Association::where('status', 'approved')->count();
        $recentAssociations = Association::where('status', 'approved')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        $categoriesCount = Association::where('status', 'approved')
            ->select('category', \DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');
        
        return view('my-associations', compact('associations', 'totalAssociations', 'recentAssociations', 'categoriesCount'));
    }

    public function destroy($id)
    {
        $association = Association::findOrFail($id);
        $association->delete();
        
        return redirect()->route('my-associations.index')->with('success', 'تم حذف الجمعية بنجاح.');
    }
}
