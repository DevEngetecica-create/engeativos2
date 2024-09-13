<?php


namespace App\Http\Controllers\Estoque ;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Product;
use App\Models\CadastroObra;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SubcategoryRepositoryInterface;
use App\Repositories\Interfaces\BrandRepositoryInterface;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $productRepository;
    protected $categoryRepository;
    protected $subcategoryRepository;
    protected $brandRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        SubcategoryRepositoryInterface $subcategoryRepository,
        BrandRepositoryInterface $brandRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->subcategoryRepository = $subcategoryRepository;
        $this->brandRepository = $brandRepository;
    }



    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = $this->productRepository->paginate(10, $search);
        return view('products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categoryRepository->all();
        $subcategories = $this->subcategoryRepository->all();
        $brands = $this->brandRepository->all();
        
        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
            
        }else {
            
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }

        return view('products.create', compact('obras','categories', 'subcategories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Remove pontos e substitui vírgula por ponto para converter o valor em um float
        $request->merge(['unit_price' => str_replace(['.', ','], ['', '.'], $request->unit_price)]);


        $request->validate(
            [
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'unit_price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'required|exists:subcategories,id',
                'minimum_stock' => 'required|integer',
                'unit' => 'required|string|max:255',
                'brand_id' => 'required|exists:brands,id',
            ],
            [
                'name.required' => 'Insira o nome do produto',
                'quantity.required' => 'Insira a quantidade',
                'unit_price.required' => 'Valor invlálido',
                'category_id.required' => 'Selecione uma categoria',
                'subcategory_id.required' => 'Selecione uma subcategoria',
                'minimum_stock.required' => 'Insira o estoque minímo',
                'unit.required' => 'Insira a unidade',
                'brand_idrequired' => 'Insira a marca/ fabricante do produto',
            ]
        );

        try {

            if ($request->file('image')) {

                // Valida a extensão da imagem
                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg|max:2048'
                ], [
                    'image.mimes' => 'A imagem enviada possui extensão inválida. O sistema aceita apenas as extensões "png, jpg, jpeg, svg"'
                ]);

                // Obtém o nome da imagem
                $imageName = $request->file('image')->getClientOriginalName();

                $data = $request->all();
                $data['created_by'] = Auth::user()->email;
                $data['image'] = $imageName;

                // Primeiro, cria o produto no banco de dados para obter o ID
                $product = $this->productRepository->create($data);

                // Obtém o objeto do arquivo da imagem
                $imagePath = $request->file('image');

                // O caminho onde será salvo a imagem
                $targetDir = public_path("build/assets/images/product/{$product->id}");

                // Cria o diretório se não existir
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                // Move o upload da imagem para a pasta pública
                $imagePath->move($targetDir, $imageName);
            }

            Log::create(['action' => 'Product created', 'user_email' => Auth::user()->email]);

            return redirect()->route('estoque.index')->with('success', 'Produto cadastrado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('estoque.index')->with('error', 'Erro ao cadastrar produto.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = $this->productRepository->find($id);
        $categories = $this->categoryRepository->all();
        $subcategories = $this->subcategoryRepository->all();
        $brands = $this->brandRepository->all();
        
        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
            
        }else {
            
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }
        
        return view('products.show', compact('obras','product', 'categories', 'subcategories', 'brands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = $this->productRepository->find($id);
        $categories = $this->categoryRepository->all();
        $subcategories = $this->subcategoryRepository->all();
        $brands = $this->brandRepository->all();
        
        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
            
        }else {
            
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }
        
        return view('products.edit', compact('obras', 'product', 'categories', 'subcategories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Remove pontos e substitui vírgula por ponto para converter o valor em um float
        $request->merge(['unit_price' => str_replace(['.', ','], ['', '.'], $request->unit_price)]);

        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'unit_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimum_stock' => 'required|integer',
            'unit' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
        ]);

        try {
            $product = $this->productRepository->find($id);
            $data = $request->all();
            $data['updated_by'] = Auth::user()->email;

            $imagePath = public_path("build/assets/images/product/{$product->id}");

            if ($request->hasFile('image')) {
                // Deletar a imagem antiga se existir
                if ($product->image) {
                    $oldImage = $imagePath . '/' . $product->image;
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

                // Salvar a nova imagem
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($imagePath, $filename);
                $data['image'] = $filename;
            } else {
                // Se não houver nova imagem, mantenha a antiga
                unset($data['image']);
            }

            $this->productRepository->update($product, $data);

            Log::create([
                'action' => 'Product updated',
                'user_email' => Auth::user()->email,
            ]);

            return redirect()->route('estoque.index')->with('success', 'Produto atualizado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('estoque.index')->with('error', 'Erro ao atualizar produto.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = $this->productRepository->find($id);

            // O caminho onde a imagem está salva
            $imagePath = public_path("build/assets/images/product/{$product->id}/" . $product->image);

            // Deletar a imagem se existir
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Deletar o diretório do produto se estiver vazio
            $productDir = public_path("build/assets/images/product/{$product->id}");
            if (is_dir($productDir) && count(scandir($productDir)) == 2) { // diretório está vazio
                rmdir($productDir);
            }

            // Deletar o registro do produto
            $this->productRepository->delete($product);

            Log::create(['action' => 'Product deleted', 'user_email' => Auth::user()->email]);

            return redirect()->route('estoque.index')->with('success', 'Produto excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('estoque.index')->with('error', 'Erro ao excluir produto.');
        }
    }
}
