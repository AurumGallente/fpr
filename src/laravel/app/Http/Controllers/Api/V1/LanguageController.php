<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\LanguagesResource;
use App\Models\Language;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    use ApiResponses;
    /**
     * List of all available languages
     * @group Languages
     * @response 200 {"data":[{"type":"language","id":1,"attributes":{"code":"cs","language":"Czech"}},{"type":"language","id":2,"attributes":{"code":"da","language":"Danish"}},{"type":"language","id":3,"attributes":{"code":"nl","language":"Dutch"}},{"type":"language","id":4,"attributes":{"code":"en","language":"English"}},{"type":"language","id":5,"attributes":{"code":"et","language":"Estonian"}},{"type":"language","id":6,"attributes":{"code":"fi","language":"Finnish"}},{"type":"language","id":7,"attributes":{"code":"fr","language":"French"}},{"type":"language","id":8,"attributes":{"code":"de","language":"German"}},{"type":"language","id":9,"attributes":{"code":"el","language":"Greek"}},{"type":"language","id":10,"attributes":{"code":"it","language":"Italian"}},{"type":"language","id":11,"attributes":{"code":"no","language":"Norwegian"}},{"type":"language","id":12,"attributes":{"code":"pl","language":"Polish"}},{"type":"language","id":13,"attributes":{"code":"pt","language":"Portuguese"}},{"type":"language","id":14,"attributes":{"code":"ru","language":"Russian"}},{"type":"language","id":15,"attributes":{"code":"sl","language":"Slovene"}},{"type":"language","id":16,"attributes":{"code":"es","language":"Spanish"}},{"type":"language","id":17,"attributes":{"code":"sv","language":"Swedish"}},{"type":"language","id":18,"attributes":{"code":"tr","language":"Turkish"}}]}
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return LanguagesResource::collection(Language::all());
    }

    /**
     * Information about language
     * @group Languages
     * @urlParam id integer required The id of the language
     * @response 200 {"data":{"type":"language","id":5,"attributes":{"code":"et","language":"Estonian"}}}
     * @response 404 {"message":"Language not found","status":404}
     * @param $language_id
     * @return \Illuminate\Http\JsonResponse|LanguagesResource
     */
    public function show($language_id): \Illuminate\Http\JsonResponse|LanguagesResource
    {
        $language = Language::where('id', $language_id)->first();
        if(!$language){
            return $this->error('Language not found', 404);
        }
        return new LanguagesResource($language);
    }
}
