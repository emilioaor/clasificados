<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Requests\Admin\AddCategoryRequest;
use App\SubCategory;
use App\SubCategoryOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    /**
     * Vista principal de administrador
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        return view('admin.index', [
            'categories' => $categories,
            'collapse' => isset($request->collapse) ? $request->collapse : 1,
        ]);
    }

    /**
     * Agrega una nueva categoria
     *
     * @param AddCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCategory(AddCategoryRequest $request)
    {
        $category = new Category($request->all());
        $category->save();

        $this->sessionMessages('Categoria agregada');

        return redirect()->route('admin.index', [
            'collapse' => isset($request->collapse) ? $request->collapse : 1,
        ]);
    }

    /**
     * Actualiza una nueva categoria
     *
     * @param AddCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCategory(AddCategoryRequest $request, $id)
    {
        $category = Category::find($id);
        $category->update($request->all());

        $this->sessionMessages('Categoria actualizada');

        return redirect()->route('admin.index', [
                'collapse' => isset($request->collapse) ? $request->collapse : 1,
            ]);
    }

    /**
     * Agrega una nueva sub categoria
     *
     * @param AddCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addSubCategory(AddCategoryRequest $request)
    {
        $category = new SubCategory($request->all());
        $category->save();

        $this->sessionMessages('Sub-categoria agregada');

        return redirect()->route('admin.index', [
            'collapse' => isset($request->collapse) ? $request->collapse : 1,
        ]);
    }

    /**
     * Actualiza una sub categoria
     *
     * @param AddCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSubCategory(AddCategoryRequest $request, $id)
    {
        $category = SubCategory::find($id);
        $category->update($request->all());

        $this->sessionMessages('Sub-categoria actualizada');

        return redirect()->route('admin.index', [
            'collapse' => isset($request->collapse) ? $request->collapse : 1,
        ]);
    }

    /**
     * Agrega una nueva opcion permitida para una subcategoria
     *
     * @param AddCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addOption(AddCategoryRequest $request)
    {
        $option = new SubCategoryOption($request->all());
        $option->save();

        $this->sessionMessages('Opción agregada');

        return redirect()->route('admin.index', [
            'collapse' => isset($request->collapse) ? $request->collapse : 1,
        ]);
    }

    /**
     * Actualiza una opcion
     *
     * @param AddCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOption(AddCategoryRequest $request, $id)
    {
        $option = SubCategoryOption::find($id);
        $option->update($request->all());

        $this->sessionMessages('Opción actualizada');

        return redirect()->route('admin.index', [
            'collapse' => isset($request->collapse) ? $request->collapse : 1,
        ]);
    }
}
