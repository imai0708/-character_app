<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Request;

    class CharacterRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {

            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */

        public function rules(Request $request)
        {
            $route = $this->route()->getName();


            $rule = [
                'title' => 'required|string|max:30',
                'description' => 'required|string|max:200',
                'category_id' => 'required',
            ];
            if ($route === 'Characters.store' || ($route === 'Characters.update' && $request->file('image'))) {
                $rule['image'] = 'required|file|image|mimes:jpg,png';
            }
            return $rule;
        }
        public function attributes()
        {
            return [
                'title' => 'キャラ名(武器でも可)',
                'description' => 'キャラ詳細/魅力',
                'category_id' => '性別',
                'image_path' => '画像(ファンアート可)'
            ];
        }
    }
