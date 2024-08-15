<?php


namespace App\Http\Controllers\Estoque ;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Brand::where('name', 'like', "%{$search}%")->paginate(10);

        return view('categories.index', compact('categories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
        ]);

        try {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->supplier = $request->supplier;
            $brand->save();

            Log::create(['action' => 'Brand created', 'user_email' => Auth::user()->email]);

            return redirect()->route('brands.index')->with('success', 'Marca cadastrada com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('brands.index')->with('error', 'Erro ao cadastrar marca.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
        ]);

        try {
            $brand->name = $request->name;
            $brand->supplier = $request->supplier;
            $brand->save();

            Log::create(['action' => 'Brand updated', 'user_email' => Auth::user()->email]);

            return redirect()->route('brands.index')->with('success', 'Marca atualizada com sucesso.');

        } catch (\Exception $e) {

            return redirect()->route('brands.index')->with('error', 'Erro ao atualizar marca.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();

            Log::create(['action' => 'Brand deleted', 'user_email' => Auth::user()->email]);

            return redirect()->route('brands.index')->with('success', 'Marca excluída com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('brands.index')->with('error', 'Erro ao excluir marca.');
        }
    }
}
