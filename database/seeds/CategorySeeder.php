<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\SubCategory;
use App\SubCategoryOption;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Categoria 1
        $category = new Category();
        $category->name = 'Vehiculo';
        $category->save();

        // Categoria 1 - Sub-categoria 1
        $subCategory = new SubCategory();
        $subCategory->name = 'Marca';
        $subCategory->category_id = $category->id;
        $subCategory->save();
        // Categoria 1 - Sub-categoria 1 - Opcion 1
        $subCategoryOption = new SubCategoryOption();
        $subCategoryOption->name = 'Nissan';
        $subCategoryOption->sub_category_id = $subCategory->id;
        $subCategoryOption->save();
        // Categoria 1 - Sub-categoria 1 - Opcion 2
        $subCategoryOption = new SubCategoryOption();
        $subCategoryOption->name = 'Ford';
        $subCategoryOption->sub_category_id = $subCategory->id;
        $subCategoryOption->save();

        // Categoria 1 - Sub-categoria 2
        $subCategory = new SubCategory();
        $subCategory->name = 'Color';
        $subCategory->category_id = $category->id;
        $subCategory->save();
        // Categoria 1 - Sub-categoria 2 - Opcion 1
        $subCategoryOption = new SubCategoryOption();
        $subCategoryOption->name = 'Rojo';
        $subCategoryOption->sub_category_id = $subCategory->id;
        $subCategoryOption->save();
        // Categoria 1 - Sub-categoria 2 - Opcion 2
        $subCategoryOption = new SubCategoryOption();
        $subCategoryOption->name = 'Azul';
        $subCategoryOption->sub_category_id = $subCategory->id;
        $subCategoryOption->save();

        // Categoria 2
        $category = new Category();
        $category->name = 'Vivienda';
        $category->save();

        // Categoria 2 - Sub-categoria 1
        $subCategory = new SubCategory();
        $subCategory->name = 'Tipo';
        $subCategory->category_id = $category->id;
        $subCategory->save();
        // Categoria 2 - Sub-categoria 1 - Opcion 1
        $subCategoryOption = new SubCategoryOption();
        $subCategoryOption->name = 'Casa';
        $subCategoryOption->sub_category_id = $subCategory->id;
        $subCategoryOption->save();
        // Categoria 2 - Sub-categoria 1 - Opcion 2
        $subCategoryOption = new SubCategoryOption();
        $subCategoryOption->name = 'Apartamento';
        $subCategoryOption->sub_category_id = $subCategory->id;
        $subCategoryOption->save();

    }
}
