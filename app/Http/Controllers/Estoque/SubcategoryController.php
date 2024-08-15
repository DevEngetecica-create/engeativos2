<?php

namespace App\Http\Controllers\Estoque ;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Log;
use App\Models\Subcategory;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\SubcategoryRepositoryInterface;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $subcategoryRepository;
    protected $categoryRepository;

    public function __construct(SubcategoryRepositoryInterface $subcategoryRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->subcategoryRepository = $subcategoryRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $subcategories = $this->subcategoryRepository->paginate(10, $search);
        return view('products.subcategories.index', compact('subcategories', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.subcategories.create', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:10',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $subcategory = new Subcategory();
            $subcategory->name = $request->name;
            $subcategory->color = $request->color;
            $subcategory->category_id = $request->category_id;
            $subcategory->created_by = Auth::user()->email;
            $subcategory->save();

            Log::create(['action' => 'Subcategory created', 'user_email' => Auth::user()->email]);

            return redirect()->route('subcategories.index')->with('success', 'Subcategoria cadastrada com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('subcategories.index')->with('error', 'Erro ao cadastrar subcategoria.');
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
    public function edit($id)
    {
        $subcategory = Subcategory::find($id);
        $categories = Category::all();
        
        return view('products.subcategories.edit', compact('subcategory', 'categories'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $subcategory->name = $request->name;
            $subcategory->color = $request->color;
            $subcategory->category_id = $request->category_id;
            $subcategory->updated_by = Auth::user()->email;
            $subcategory->save();

            Log::create(['action' => 'Subcategory updated', 'user_email' => Auth::user()->email]);

            return redirect()->route('subcategories.index')->with('success', 'Subcategoria atualizada com sucesso.');

        } catch (\Exception $e) {

            return redirect()->route('subcategories.index')->with('error', 'Erro ao atualizar subcategoria.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        try {
            if ($subcategory->products()->count() > 0) {
                return redirect()->route('subcategories.index')->with('warning', 'Não é possível excluir uma subcategoria com produtos cadastrados.');
            }

            $subcategory->delete();

            Log::create(['action' => 'Subcategory deleted', 'user_email' => Auth::user()->email]);

            return redirect()->route('subcategories.index')->with('success', 'Subcategoria excluída com sucesso.');
        } catch (\Exception $e) {

            return redirect()->route('subcategories.index')->with('error', 'Erro ao excluir subcategoria.');
        }
    }
}
